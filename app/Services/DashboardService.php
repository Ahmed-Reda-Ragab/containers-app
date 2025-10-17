<?php

namespace App\Services;

use App\Models\Container;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\ContractContainerFill;
use App\Models\Discharge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get top 5 clients based on container activity
     */
    public function getTopClients(): array
    {
        $topClients = [];
        Customer::with(['contracts.contractContainerFills'])
            ->withCount(['contracts as total_contracts'])
            ->withCount(['contracts.contractContainerFills as total_fills'])
            ->orderBy('total_fills', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                $mostActiveMonth = $this->getMostActiveMonthForCustomer($customer->id);
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'company_name' => $customer->company_name,
                    'total_contracts' => $customer->total_contracts,
                    'total_fills' => $customer->total_fills,
                    'most_active_month' => $mostActiveMonth,
                ];
            });

        return $topClients->toArray();
    }

    /**
     * Get most active month for a specific customer
     */
    private function getMostActiveMonthForCustomer(int $customerId): string
    {
        $monthlyActivity = ContractContainerFill::join('contracts', 'contract_container_fills.contract_id', '=', 'contracts.id')
            ->where('contracts.customer_id', $customerId)
            ->selectRaw('MONTH(contract_container_fills.created_at) as month, COUNT(*) as activity_count')
            ->groupBy('month')
            ->orderBy('activity_count', 'desc')
            ->first();

        if ($monthlyActivity) {
            return Carbon::create()->month($monthlyActivity->month)->format('F');
        }

        return 'No activity';
    }

    /**
     * Get today's containers summary
     */
    public function getTodaysSummary(): array
    {
        $today = Carbon::today();

        // Containers scheduled for unloading today
        $scheduledForUnloading = ContractContainerFill::whereDate('discharge_date', $today)
        //scheduled_date
            // ->where('status', 'scheduled')
            ->count();

        // Containers actually unloaded today
        $actuallyUnloaded = Discharge::whereDate('discharge_date', $today)->count();

        // Available containers
        $availableContainers = Container::where('status', 'available')->count();

        // Unavailable containers
        $unavailableContainers = Container::where('status', '!=', 'available')->count();

        return [
            'scheduled_for_unloading' => $scheduledForUnloading,
            'actually_unloaded' => $actuallyUnloaded,
            'available_containers' => $availableContainers,
            'unavailable_containers' => $unavailableContainers,
        ];
    }

    /**
     * Get container status distribution for donut chart
     */
    public function getContainerStatusDistribution(): array
    {
        $statusDistribution = Container::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => ucfirst(str_replace('_', ' ', $item->status)),
                    'value' => $item->count,
                    'status' => $item->status,
                ];
            });

        return $statusDistribution->toArray();
    }

    /**
     * Get container activity by month for bar chart
     */
    public function getContainerActivityByMonth(): array
    {
        $monthlyActivity = ContractContainerFill::selectRaw('
                MONTH(created_at) as month,
                YEAR(created_at) as year,
                COUNT(*) as activity_count
            ')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'activity' => $item->activity_count,
                ];
            });

        return $monthlyActivity->toArray();
    }

    /**
     * Get daily unloading trends for current month
     */
    public function getDailyUnloadingTrends(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $dailyTrends = Discharge::selectRaw('DATE(discharge_date) as date, COUNT(*) as count')
            ->whereBetween('discharge_date', [$currentMonth, $endOfMonth])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count,
                ];
            });

        return $dailyTrends->toArray();
    }

    /**
     * Get unavailable containers details
     */
    public function getUnavailableContainers(): array
    {
        return Container::where('status', '!=', 'available')
            ->with('size')
            ->get()
            ->map(function ($container) {
                return [
                    'id' => $container->id,
                    'code' => $container->code,
                    'size' => $container->size->name ?? 'N/A',
                    'status' => $container->status->value,
                    'status_label' => $container->status->label(),
                    'description' => $container->description,
                ];
            })
            ->toArray();
    }

    /**
     * Get all dashboard data
     */
    public function getAllDashboardData(): array
    {
        return [
            'top_clients' =>[], // $this->getTopClients(),
            'todays_summary' => $this->getTodaysSummary(),
            'container_status_distribution' =>[], //$this->getContainerStatusDistribution(),
            'monthly_activity' =>[], //$this->getContainerActivityByMonth(),
            'daily_trends' =>[], //$this->getDailyUnloadingTrends(),
            'unavailable_containers' =>[],// $this->getUnavailableContainers(),
        ];
    }
}
