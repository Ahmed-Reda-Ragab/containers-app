<?php

namespace App\Services;

use App\Enums\ContainerStatus;
use App\Models\Container;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\ContractContainerFill;
use App\Models\Offer;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get top 5 clients based on container activity
     */
    public function getTopClients(): array
    {
        $topClients = Customer::select([
                'customers.id',
                'customers.name',
                'customers.company_name',
            ])
            ->leftJoin('contracts', 'contracts.customer_id', '=', 'customers.id')
            ->leftJoin('contract_container_fills', 'contract_container_fills.contract_id', '=', 'contracts.id')
            ->groupBy('customers.id', 'customers.name', 'customers.company_name')
            ->selectRaw('COUNT(DISTINCT contracts.id) as total_contracts')
            ->selectRaw('COUNT(contract_container_fills.id) as total_fills')
            ->orderByDesc('total_fills')
            ->orderByDesc('total_contracts')
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                $mostActiveMonth = $this->getMostActiveMonthForCustomer($customer->id);
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'company_name' => $customer->company_name,
                    'total_contracts' => (int) $customer->total_contracts,
                    'total_fills' => (int) $customer->total_fills,
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

        $scheduledForUnloading = ContractContainerFill::whereDate('expected_discharge_date', $today)->count();
        $actuallyUnloaded = ContractContainerFill::whereDate('discharge_date', $today)->count();
        $availableContainers = Container::where('status', ContainerStatus::AVAILABLE->value)->count();
        $unavailableContainers = Container::where('status', '!=', ContainerStatus::AVAILABLE->value)->count();

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
        $counts = Container::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return collect(ContainerStatus::cases())
            ->map(function (ContainerStatus $status) use ($counts) {
                return [
                    'label' => $status->label(),
                    'value' => (int) ($counts[$status->value] ?? 0),
                    'status' => $status->value,
                    'color' => $status->color(),
                ];
            })
            ->toArray();
    }

    /**
     * Get container activity by month for bar chart
     */
    public function getContainerActivityByMonth(): array
    {
        $end = Carbon::now()->startOfMonth();
        $start = (clone $end)->subMonths(11);

        $activity = ContractContainerFill::selectRaw('DATE_FORMAT(COALESCE(deliver_at, created_at), "%Y-%m-01") as bucket, COUNT(*) as total')
            ->whereBetween(DB::raw('COALESCE(deliver_at, created_at)'), [$start, $end->copy()->endOfMonth()])
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        $series = [];
        foreach (CarbonPeriod::create($start, '1 month', $end) as $month) {
            $key = $month->format('Y-m-01');
            $series[] = [
                'month' => $month->format('M Y'),
                'activity' => (int) ($activity[$key] ?? 0),
            ];
        }

        return $series;
    }

    /**
     * Get daily unloading trends for current month
     */
    public function getDailyUnloadingTrends(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $dailyCounts = ContractContainerFill::selectRaw('DATE(discharge_date) as day_bucket, COUNT(*) as total')
            ->whereBetween('discharge_date', [$currentMonth, $endOfMonth])
            ->whereNotNull('discharge_date')
            ->groupBy('day_bucket')
            ->pluck('total', 'day_bucket');

        $period = CarbonPeriod::create($currentMonth, $endOfMonth);
        $series = [];
        foreach ($period as $day) {
            $dateKey = $day->format('Y-m-d');
            $series[] = [
                'date' => $day->format('M d'),
                'count' => (int) ($dailyCounts[$dateKey] ?? 0),
            ];
        }

        return $series;
    }

    public function getDailyLoadingTrends(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $dailyCounts = ContractContainerFill::selectRaw('DATE(deliver_at) as day_bucket, COUNT(*) as total')
            ->whereBetween('deliver_at', [$currentMonth, $endOfMonth])
            ->whereNotNull('deliver_at')
            ->groupBy('day_bucket')
            ->pluck('total', 'day_bucket');

        $period = CarbonPeriod::create($currentMonth, $endOfMonth);
        $series = [];
        foreach ($period as $day) {
            $dateKey = $day->format('Y-m-d');
            $series[] = [
                'date' => $day->format('M d'),
                'count' => (int) ($dailyCounts[$dateKey] ?? 0),
            ];
        }

        return $series;
    }

    /**
     * Get unavailable containers details
     */
    public function getUnavailableContainers(): array
    {
        return Container::where('status', '!=', ContainerStatus::AVAILABLE->value)
            ->with('size')
            ->get()
            ->map(function ($container) {
                $statusEnum = $container->status instanceof ContainerStatus
                    ? $container->status
                    : ContainerStatus::tryFrom($container->status);

                return [
                    'id' => $container->id,
                    'code' => $container->code,
                    'size' => $container->size->name ?? '',
                    'status' => $statusEnum?->value ?? (string) $container->status,
                    'status_label' => $statusEnum?->label() ?? ucfirst(str_replace('_', ' ', (string) $container->status)),
                    'description' => $container->description,
                ];
            })
            ->toArray();
    }

    public function getContractAndQuotationStats(): array
    {
        $activeBusinessContracts = Contract::where('type', Contract::BUSINESS_TYPE)
            ->where('status', 'active')
            ->count();

        $nearExpiryContracts = Contract::where('type', Contract::BUSINESS_TYPE)
            ->where('status', 'active')
            ->whereDate('end_date', '<=', Carbon::now()->addDays(15))
            ->count();

        $companyQuotations = Offer::where('status', 'active')->count();
        $expiredQuotations = Offer::whereDate('valid_until', '<', Carbon::today())->count();

        return [
            'active_contracts' => $activeBusinessContracts,
            'near_expiry_contracts' => $nearExpiryContracts,
            'active_quotations' => $companyQuotations,
            'expired_quotations' => $expiredQuotations,
        ];
    }

    /**
     * Get all dashboard data
     */
    public function getAllDashboardData(): array
    {
        return [
            'top_clients' => $this->getTopClients(),
            'todays_summary' => $this->getTodaysSummary(),
            'container_status_distribution' => $this->getContainerStatusDistribution(),
            'monthly_activity' => $this->getContainerActivityByMonth(),
            'daily_trends' => $this->getDailyUnloadingTrends(),
            'daily_loads' => $this->getDailyLoadingTrends(),
            'unavailable_containers' => $this->getUnavailableContainers(),
            'contract_stats' => $this->getContractAndQuotationStats(),
        ];
    }
}
