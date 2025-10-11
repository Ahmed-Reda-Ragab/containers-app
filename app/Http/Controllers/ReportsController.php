<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Container;
use App\Models\Contract;
use App\Models\DailyBooking;
use App\Models\Receipt;
use App\Models\Delivery;
use App\Models\Discharge;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display container status report
     */
    public function containerStatus()
    {
        $containers = Container::with(['type', 'contractContainers.contract'])
            ->orderBy('code')
            ->get();

        $containerStats = [
            'total' => $containers->count(),
            'available' => $containers->where('status', 'available')->count(),
            'in_use' => $containers->where('status', 'in_use')->count(),
            'filled' => $containers->where('status', 'filled')->count(),
            'maintenance' => $containers->where('status', 'maintenance')->count(),
            'out_of_service' => $containers->where('status', 'out_of_service')->count(),
        ];

        return view('reports.container-status', compact('containers', 'containerStats'));
    }

    /**
     * Display daily report
     */
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        // Get daily statistics
        $dailyStats = [
            'deliveries' => Delivery::whereDate('delivery_date', $selectedDate)->count(),
            'discharges' => Discharge::whereDate('discharge_date', $selectedDate)->count(),
            'bookings' => DailyBooking::whereDate('booking_date', $selectedDate)->count(),
            'receipts_issued' => Receipt::whereDate('issue_date', $selectedDate)->count(),
            'receipts_collected' => Receipt::whereDate('collection_date', $selectedDate)->count(),
        ];

        // Get monthly statistics
        $monthlyStats = $this->getMonthlyStats($selectedDate->year, $selectedDate->month);

        return view('reports.daily-report', compact('dailyStats', 'monthlyStats', 'selectedDate'));
    }

    /**
     * Display monthly report
     */
    public function monthlyReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $monthlyStats = $this->getMonthlyStats($year, $month);
        $yearlyStats = $this->getYearlyStats($year);

        return view('reports.monthly-report', compact('monthlyStats', 'yearlyStats', 'year', 'month'));
    }

    /**
     * Display receipts report
     */
    public function receiptsReport(Request $request)
    {
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Receipt::with(['contract', 'customer', 'issuedBy', 'collectedBy']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('issue_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('issue_date', '<=', $dateTo);
        }

        $receipts = $query->orderBy('issue_date', 'desc')->paginate(50);

        $receiptStats = [
            'total_issued' => Receipt::where('status', 'issued')->count(),
            'total_collected' => Receipt::where('status', 'collected')->count(),
            'total_overdue' => Receipt::where('status', 'overdue')->count(),
            'total_amount_issued' => Receipt::where('status', 'issued')->sum('amount'),
            'total_amount_collected' => Receipt::where('status', 'collected')->sum('amount'),
            'total_amount_overdue' => Receipt::where('status', 'overdue')->sum('amount'),
        ];

        return view('reports.receipts-report', compact('receipts', 'receiptStats', 'status', 'dateFrom', 'dateTo'));
    }

    /**
     * Print container status report
     */
    public function printContainerStatus()
    {
        $containers = Container::with(['type'])
            ->orderBy('code')
            ->get();

        return view('reports.print.container-status', compact('containers'));
    }

    /**
     * Print daily report
     */
    public function printDailyReport(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $dailyStats = [
            'deliveries' => Delivery::whereDate('delivery_date', $selectedDate)->count(),
            'discharges' => Discharge::whereDate('discharge_date', $selectedDate)->count(),
            'bookings' => DailyBooking::whereDate('booking_date', $selectedDate)->count(),
            'receipts_issued' => Receipt::whereDate('issue_date', $selectedDate)->count(),
            'receipts_collected' => Receipt::whereDate('collection_date', $selectedDate)->count(),
        ];

        return view('reports.print.daily-report', compact('dailyStats', 'selectedDate'));
    }

    /**
     * Get monthly statistics
     */
    private function getMonthlyStats($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        return [
            'year' => $year,
            'month' => $month,
            'days_in_month' => $startDate->daysInMonth,
            'deliveries' => Delivery::whereBetween('delivery_date', [$startDate, $endDate])->count(),
            'discharges' => Discharge::whereBetween('discharge_date', [$startDate, $endDate])->count(),
            'total_trips' => Delivery::whereBetween('delivery_date', [$startDate, $endDate])->count() + 
                           Discharge::whereBetween('discharge_date', [$startDate, $endDate])->count(),
            'discharge_requests' => Discharge::whereBetween('discharge_date', [$startDate, $endDate])->count(),
            'avg_daily_deliveries' => round(Delivery::whereBetween('delivery_date', [$startDate, $endDate])->count() / $startDate->daysInMonth, 2),
            'avg_daily_discharges' => round(Discharge::whereBetween('discharge_date', [$startDate, $endDate])->count() / $startDate->daysInMonth, 2),
            'avg_days_at_customer' => $this->calculateAvgDaysAtCustomer($startDate, $endDate),
            'avg_delay_days' => $this->calculateAvgDelayDays($startDate, $endDate),
            'free_containers' => Container::where('status', 'available')->count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
            'receipts_issued' => Receipt::whereBetween('issue_date', [$startDate, $endDate])->count(),
            'receipts_amount_issued' => Receipt::whereBetween('issue_date', [$startDate, $endDate])->sum('amount'),
            'receipts_collected' => Receipt::whereBetween('collection_date', [$startDate, $endDate])->count(),
            'receipts_amount_collected' => Receipt::whereBetween('collection_date', [$startDate, $endDate])->sum('amount'),
            'receipts_uncollected' => Receipt::where('status', 'issued')->where('due_date', '<', now())->count(),
            'receipts_amount_uncollected' => Receipt::where('status', 'issued')->where('due_date', '<', now())->sum('amount'),
        ];
    }

    /**
     * Get yearly statistics
     */
    private function getYearlyStats($year)
    {
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);

        return [
            'year' => $year,
            'total_deliveries' => Delivery::whereBetween('delivery_date', [$startDate, $endDate])->count(),
            'total_discharges' => Discharge::whereBetween('discharge_date', [$startDate, $endDate])->count(),
            'total_income' => Receipt::whereBetween('collection_date', [$startDate, $endDate])->sum('amount'),
        ];
    }

    /**
     * Calculate average days at customer
     */
    private function calculateAvgDaysAtCustomer($startDate, $endDate)
    {
        // This would need to be calculated based on your business logic
        // For now, returning a placeholder
        return 7.5;
    }

    /**
     * Calculate average delay days
     */
    private function calculateAvgDelayDays($startDate, $endDate)
    {
        // This would need to be calculated based on your business logic
        // For now, returning a placeholder
        return 2.3;
    }
}