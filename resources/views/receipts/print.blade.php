<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Receipt') }} - {{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .receipt-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .receipt-info .left, .receipt-info .right {
            width: 48%;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        .amount-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px solid #000;
            background: #f9f9f9;
        }
        .amount-label {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .amount-value {
            font-size: 32px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .status-section {
            text-align: center;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
        }
        .status-issued {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }
        .status-collected {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        .status-overdue {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }
        .status-cancelled {
            background: #e2e3e5;
            color: #383d41;
            border: 2px solid #d6d8db;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
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
            .receipt-container {
                border: none;
                max-width: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> {{ __('Print') }}
    </button>

    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ __('RTM Arabia Est') }}</div>
            <div class="receipt-title">{{ __('Receipt') }}</div>
            <div class="receipt-number">{{ $receipt->receipt_number }}</div>
        </div>

        <!-- Receipt Information -->
        <div class="receipt-info">
            <div class="left">
                <div class="info-row">
                    <span class="info-label">{{ __('Issue Date') }}:</span>
                    <span class="info-value">{{ $receipt->issue_date->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Due Date') }}:</span>
                    <span class="info-value">{{ $receipt->due_date->format('d/m/Y') }}</span>
                </div>
                @if($receipt->collection_date)
                <div class="info-row">
                    <span class="info-label">{{ __('Collection Date') }}:</span>
                    <span class="info-value">{{ $receipt->collection_date->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>
            <div class="right">
                <div class="info-row">
                    <span class="info-label">{{ __('Contract') }}:</span>
                    <span class="info-value">#{{ $receipt->contract_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Issued By') }}:</span>
                    <span class="info-value">{{ $receipt->issuedBy->name ?? '-' }}</span>
                </div>
                @if($receipt->collectedBy)
                <div class="info-row">
                    <span class="info-label">{{ __('Collected By') }}:</span>
                    <span class="info-value">{{ $receipt->collectedBy->name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        <div class="info-section">
            <h3>{{ __('Customer Information') }}</h3>
            <div class="info-row">
                <span class="info-label">{{ __('Name') }}:</span>
                <span class="info-value">{{ $receipt->customer_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Phone') }}:</span>
                <span class="info-value">{{ $receipt->customer_phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Address') }}:</span>
                <span class="info-value">{{ $receipt->customer_address }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('City') }}:</span>
                <span class="info-value">{{ $receipt->city }}</span>
            </div>
        </div>

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-label">{{ __('Amount Due') }}</div>
            <div class="amount-value">{{ number_format($receipt->amount, 2) }} {{ __('SAR') }}</div>
        </div>

        <!-- Status Section -->
        <div class="status-section">
            <span class="status-badge status-{{ $receipt->status }}">
                @switch($receipt->status)
                    @case('issued')
                        {{ __('Issued') }}
                        @break
                    @case('collected')
                        {{ __('Collected') }}
                        @break
                    @case('overdue')
                        {{ __('Overdue') }}
                        @break
                    @case('cancelled')
                        {{ __('Cancelled') }}
                        @break
                @endswitch
            </span>
        </div>

        <!-- Notes -->
        @if($receipt->notes)
        <div class="info-section">
            <h3>{{ __('Notes') }}</h3>
            <div class="info-value">{{ $receipt->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('Thank you for your business!') }}</p>
            <p>{{ __('For any inquiries, please contact us.') }}</p>
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


