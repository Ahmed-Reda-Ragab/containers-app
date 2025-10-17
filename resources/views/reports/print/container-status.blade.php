<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Container Status Report') }}</title>
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
            font-size: 16px;
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
        .status-available {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-in-use {
            background-color: #cce5ff;
            color: #004085;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-filled {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-maintenance {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-out-of-service {
            background-color: #e2e3e5;
            color: #383d41;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .location-warehouse {
            color: #28a745;
            font-weight: bold;
        }
        .location-customer {
            color: #007bff;
            font-weight: bold;
        }
        .location-ready {
            color: #ffc107;
            font-weight: bold;
        }
        .location-service {
            color: #6c757d;
            font-weight: bold;
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
            <div class="report-title">{{ __('Container Status Report') }}</div>
            <div class="report-date">{{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <!-- Statistics -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">{{ $containers->count() }}</div>
                <div class="stat-label">{{ __('Total') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $containers->where('status', 'available')->count() }}</div>
                <div class="stat-label">{{ __('Available') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $containers->where('status', 'in_use')->count() }}</div>
                <div class="stat-label">{{ __('In Use') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $containers->where('status', 'filled')->count() }}</div>
                <div class="stat-label">{{ __('Filled') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $containers->where('status', 'maintenance')->count() }}</div>
                <div class="stat-label">{{ __('Maintenance') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $containers->where('status', 'out_of_service')->count() }}</div>
                <div class="stat-label">{{ __('Out of Service') }}</div>
            </div>
        </div>

        <!-- Container Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('SN') }}</th>
                        <th>{{ __('Container Code') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Size') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($containers as $index => $container)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $container->code }}</strong></td>
                        <td>{{ $container->type->name ?? '-' }}</td>
                        <td>{{ $container->size ?? '-' }}</td>
                        <td>
                            <span class="status-{{ $container->status->value }}">
                                {{ $container->status->label() }}
                            </span>
                        </td>
                        <td>
                            @if($container->status->value === 'available')
                                <span class="location-warehouse">{{ __('Warehouse') }}</span>
                            @elseif($container->status->value === 'in_use')
                                <span class="location-customer">{{ __('At Customer') }}</span>
                            @elseif($container->status->value === 'filled')
                                <span class="location-ready">{{ __('Ready for Pickup') }}</span>
                            @else
                                <span class="location-service">{{ __('Service Center') }}</span>
                            @endif
                        </td>
                        <td>{{ $container->description ?? '-' }}</td>
                        <td>
                            @if($container->status->value === 'available')
                                <span class="location-warehouse">{{ __('Ready') }}</span>
                            @else
                                <span class="location-customer">{{ __('Active') }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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


