<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Daily Report') }} - {{ $selectedDate->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .report-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-date {
            font-size: 18px;
            color: #666;
        }
        .stats-section {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .stat-card {
            text-align: center;
            padding: 15px;
            margin: 10px;
            border: 2px solid #000;
            min-width: 120px;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            font-weight: bold;
        }
        .table-container {
            margin-bottom: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }
        .table th {
            background-color: #376092;
            color: white;
            font-weight: bold;
            padding: 12px 8px;
            text-align: center;
            border: 1px solid #000;
        }
        .table td {
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .metric-item {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .metric-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .metric-value {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-button:hover {
            background: #0056b3;
        }
        @media print {
            .print-button {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .report-container {
                max-width: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> {{ __('Print') }}
    </button>

    <div class="report-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ __('RTM Arabia Est') }}</div>
            <div class="report-title">{{ __('Daily Report') }}</div>
            <div class="report-date">{{ $selectedDate->format('d/m/Y') }}</div>
        </div>

        <!-- Daily Statistics -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">{{ $dailyStats['deliveries'] }}</div>
                <div class="stat-label">{{ __('Deliveries') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $dailyStats['discharges'] }}</div>
                <div class="stat-label">{{ __('Discharges') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $dailyStats['bookings'] }}</div>
                <div class="stat-label">{{ __('Bookings') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $dailyStats['receipts_issued'] }}</div>
                <div class="stat-label">{{ __('Receipts Issued') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $dailyStats['receipts_collected'] }}</div>
                <div class="stat-label">{{ __('Receipts Collected') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $dailyStats['deliveries'] + $dailyStats['discharges'] }}</div>
                <div class="stat-label">{{ __('Total Trips') }}</div>
            </div>
        </div>

        <!-- Monthly Statistics -->
        <div class="section-title">{{ __('Monthly Statistics') }} - {{ $selectedDate->format('F Y') }}</div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Year') }}</th>
                        <th>{{ __('Month') }}</th>
                        <th>{{ __('Deliveries') }}</th>
                        <th>{{ __('Discharges') }}</th>
                        <th>{{ __('Total Trips') }}</th>
                        <th>{{ __('Total Income') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>{{ $monthlyStats['year'] ?? $selectedDate->year }}</strong></td>
                        <td><strong>{{ $monthlyStats['month'] ?? $selectedDate->month }}</strong></td>
                        <td><strong>{{ $monthlyStats['deliveries'] ?? 0 }}</strong></td>
                        <td><strong>{{ $monthlyStats['discharges'] ?? 0 }}</strong></td>
                        <td><strong>{{ $monthlyStats['total_trips'] ?? 0 }}</strong></td>
                        <td><strong>{{ number_format($monthlyStats['total_income'] ?? 0, 2) }} {{ __('SAR') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Performance Metrics -->
        <div class="section-title">{{ __('Performance Metrics') }}</div>
        <div class="metrics-grid">
            <div class="metric-item">
                <div class="metric-label">{{ __('Avg Daily Deliveries') }}</div>
                <div class="metric-value">{{ $monthlyStats['avg_daily_deliveries'] ?? 0 }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Avg Daily Discharges') }}</div>
                <div class="metric-value">{{ $monthlyStats['avg_daily_discharges'] ?? 0 }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Avg Days at Customer') }}</div>
                <div class="metric-value">{{ $monthlyStats['avg_days_at_customer'] ?? 0 }} {{ __('days') }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Avg Delay Days') }}</div>
                <div class="metric-value">{{ $monthlyStats['avg_delay_days'] ?? 0 }} {{ __('days') }}</div>
            </div>
        </div>

        <!-- Container & Contract Status -->
        <div class="section-title">{{ __('Container & Contract Status') }}</div>
        <div class="metrics-grid">
            <div class="metric-item">
                <div class="metric-label">{{ __('Free Containers') }}</div>
                <div class="metric-value">{{ $monthlyStats['free_containers'] ?? 0 }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Active Contracts') }}</div>
                <div class="metric-value">{{ $monthlyStats['active_contracts'] ?? 0 }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Receipts Issued') }}</div>
                <div class="metric-value">{{ $monthlyStats['receipts_issued'] ?? 0 }}</div>
            </div>
            <div class="metric-item">
                <div class="metric-label">{{ __('Receipts Collected') }}</div>
                <div class="metric-value">{{ $monthlyStats['receipts_collected'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 30px; font-size: 12px; color: #666;">
            <p>{{ __('Report generated on') }} {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>


