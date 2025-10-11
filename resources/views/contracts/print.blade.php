<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Contract') }} #{{ $contract->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .contract-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
        }
        .header {
            background: #D9D9D9;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #000;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .contract-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #595959;
            color: white;
        }
        .contract-number {
            font-size: 20px;
            color: #FF0000;
            font-weight: bold;
        }
        .date-section {
            text-align: right;
        }
        .section-header {
            background: #595959;
            color: white;
            padding: 10px 20px;
            font-weight: bold;
            font-size: 16px;
        }
        .customer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            background: #D9D9D9;
            padding: 8px 12px;
            font-weight: bold;
            min-width: 150px;
            border: 1px solid #000;
        }
        .info-value {
            background: white;
            padding: 8px 12px;
            border: 1px solid #000;
            flex: 1;
        }
        .service-details {
            padding: 20px;
        }
        .service-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }
        .service-label {
            background: #D9D9D9;
            padding: 8px 12px;
            font-weight: bold;
            min-width: 200px;
            border: 1px solid #000;
        }
        .service-value {
            background: white;
            padding: 8px 12px;
            border: 1px solid #000;
            flex: 1;
            text-align: center;
            font-weight: bold;
        }
        .total-section {
            background: #D9D9D9;
            padding: 15px;
            border: 2px solid #000;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .terms-section {
            padding: 20px;
        }
        .terms-content {
            background: #D9D9D9;
            padding: 15px;
            border: 1px solid #000;
            margin-bottom: 15px;
        }
        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
            margin-top: 30px;
        }
        .signature-box {
            border: 1px solid #000;
            padding: 20px;
            text-align: center;
            min-height: 100px;
        }
        .signature-label {
            font-weight: bold;
            margin-bottom: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="contract-container">
        <!-- Header -->
        <div class="header">
            <h1>{{ __('Dumpster Rental Contract') }}</h1>
            <h1>عقد تأجير حاويات</h1>
        </div>

        <!-- Contract Information -->
        <div class="contract-info">
            <div>
                <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">{{ __('Contract No') }}</div>
                <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">رقم العقد</div>
                <div class="contract-number">#{{ $contract->id }}</div>
            </div>
            <div class="date-section">
                <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">{{ __('Date') }}</div>
                <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">التاريخ</div>
                <div style="font-size: 18px; color: #FF0000; font-weight: bold;">{{ $contract->created_at->format('Y-m-d') }}</div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section-header">
            {{ __('Customer Information') }} - معلومات العميل
        </div>
        <div class="customer-info">
            <div>
                <div class="info-row">
                    <div class="info-label">{{ __('Customer Name') }}</div>
                    <div class="info-value">{{ $contract->customer['name'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('Contact Person') }}</div>
                    <div class="info-value">{{ $contract->customer['contact_person'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('Telephone') }}</div>
                    <div class="info-value">{{ $contract->customer['telephone'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('Ext') }}</div>
                    <div class="info-value">{{ $contract->customer['ext'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('Fax') }}</div>
                    <div class="info-value">{{ $contract->customer['fax'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('Mobile') }}</div>
                    <div class="info-value">{{ $contract->customer['mobile'] ?? 'N/A' }}</div>
                </div>
            </div>
            <div>
                <div class="info-row">
                    <div class="info-label">{{ __('City') }}</div>
                    <div class="info-value">{{ $contract->customer['city'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('Address') }}</div>
                    <div class="info-value">{{ $contract->customer['address'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Service Details -->
        <div class="section-header">
            {{ __('Service Details') }} - تفاصيل الخدمة
        </div>
        <div class="service-details">
            <div class="service-row">
                <div class="service-label">{{ __('Container Size') }}</div>
                <div class="service-value">{{ $contract->type->name ?? 'N/A' }}</div>
                <div class="service-value">{{ __('Yards') }}</div>
                <div class="service-value">{{ __('Container Size') }}</div>
            </div>
            <div class="service-row">
                <div class="service-label">{{ __('Price/Container') }}</div>
                <div class="service-value">{{ number_format($contract->container_price, 2) }} {{ __('SAR') }}</div>
                <div class="service-value">{{ __('SAR') }}</div>
                <div class="service-value">{{ __('Price/Container') }}</div>
            </div>
            <div class="service-row">
                <div class="service-label">{{ __('No. of Containers') }}</div>
                <div class="service-value">{{ $contract->no_containers }}</div>
                <div class="service-value">{{ __('Container') }}</div>
                <div class="service-value">{{ __('No. of Containers') }}</div>
            </div>
            <div class="service-row">
                <div class="service-label">{{ __('Monthly Dumping/Cont.') }}</div>
                <div class="service-value">{{ number_format($contract->monthly_dumping_cont, 2) }}</div>
                <div class="service-value">{{ __('Container') }}</div>
                <div class="service-value">{{ __('Monthly Dumping/Cont.') }}</div>
            </div>
            <div class="service-row">
                <div class="service-label">{{ __('Total Dumping') }}</div>
                <div class="service-value">{{ number_format($contract->monthly_total_dumping_cost, 2) }}</div>
                <div class="service-value">{{ __('Container') }}</div>
                <div class="service-value">{{ __('Total Dumping') }}</div>
            </div>
            <div class="service-row">
                <div class="service-label">{{ __('Total Monthly Price') }}</div>
                <div class="service-value">{{ number_format($contract->monthly_total_dumping_cost, 2) }} {{ __('SAR') }}</div>
                <div class="service-value">{{ __('SAR') }}</div>
                <div class="service-value">{{ __('Total Monthly Price') }}</div>
            </div>
            <div class="service-row">
                <div class="service-label">{{ __('Additional Trips') }}</div>
                <div class="service-value">{{ number_format($contract->additional_trip_cost, 2) }} {{ __('SAR') }}</div>
                <div class="service-value">{{ __('SAR') }}</div>
                <div class="service-value">{{ __('Additional Trips') }}</div>
            </div>
            <div class="service-row">
                <div class="service-label">{{ __('Contract Period') }}</div>
                <div class="service-value">{{ $contract->contract_period }} {{ __('Days') }}</div>
                <div class="service-value">{{ __('Days') }}</div>
                <div class="service-value">{{ __('Contract Period') }}</div>
            </div>
        </div>

        <!-- Total Price -->
        <div class="total-section">
            <div>{{ __('Total Price') }} - إجمالي الاتفاقية الشهرية</div>
            <div style="font-size: 24px; color: #FF0000;">{{ number_format($contract->total_price, 2) }} {{ __('SAR') }}</div>
        </div>

        <!-- Terms and Conditions -->
        <div class="section-header">
            {{ __('Agreement') }} - الالتزام
        </div>
        <div class="terms-section">
            <div class="terms-content">
                <strong>{{ __('Agreement Terms') }}:</strong><br>
                {{ $contract->agreement_terms ?? __('Rtm Arabia EST. agree to provide the disposal services and/or equipments specified herein and customer agrees to make the payment as provided for herein and abide by the terms and conditions of this agreement.') }}
            </div>
        </div>

        <div class="section-header">
            {{ __('Material') }} - مواد المخلفات
        </div>
        <div class="terms-section">
            <div class="terms-content">
                <strong>{{ __('Material Restrictions') }}:</strong><br>
                {{ $contract->material_restrictions ?? __('Material that will be collected and disposed by Rtm Arabia EST. is solid material generated by customer excluding radioactive, volatile, highly flammable, explosive, toxic or hazardous material.') }}
            </div>
        </div>

        <div class="section-header">
            {{ __('Receiving Containers') }} - استلام الحاويات
        </div>
        <div class="terms-section">
            <div class="terms-content">
                <strong>{{ __('Delivery Terms') }}:</strong><br>
                {{ $contract->delivery_terms ?? __('Any delivered container shall come with service delivery note/receipt even additional trips. Container will be delivered when requested by customer.') }}
            </div>
        </div>

        <div class="section-header">
            {{ __('Payments Policy') }} - سياسة سداد المستحقات
        </div>
        <div class="terms-section">
            <div class="terms-content">
                <strong>{{ __('Payment Policy') }}:</strong><br>
                {{ $contract->payment_policy ?? __('The amount due will be paid immediately from the date of issue of the invoice according to the agreement between the customer and RTM') }}
            </div>
        </div>

        <!-- Contract End Date -->
        <div style="padding: 20px; text-align: center; background: #D9D9D9; border: 1px solid #000;">
            <strong>{{ __('Contract ends on') }} - ينتهي العقد في تاريخ:</strong>
            <div style="font-size: 18px; color: #FF0000; font-weight: bold;">{{ $contract->end_date->format('Y-m-d') }}</div>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">{{ __('Customer Information') }} - معلومات العميل</div>
                <div style="margin: 20px 0; border-bottom: 1px solid #000;"></div>
                <div style="margin: 20px 0; border-bottom: 1px solid #000;"></div>
                <div style="margin: 20px 0; border-bottom: 1px solid #000;"></div>
                <div style="margin-top: 20px;">
                    <strong>{{ __('Name') }} - الاسم:</strong> {{ $contract->customer['name'] ?? 'N/A' }}<br>
                    <strong>{{ __('Signature') }} - التوقيع:</strong> _________________<br>
                    <strong>{{ __('Date') }} - التاريخ:</strong> _________________
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-label">{{ __('RTM Arabia Est') }} - مؤسسة رتم العربية للتأجير</div>
                <div style="margin: 20px 0; border-bottom: 1px solid #000;"></div>
                <div style="margin: 20px 0; border-bottom: 1px solid #000;"></div>
                <div style="margin: 20px 0; border-bottom: 1px solid #000;"></div>
                <div style="margin-top: 20px;">
                    <strong>{{ __('Manager Name') }} - اسم المسئول:</strong> Rakan M Al-Marzuqi<br>
                    <strong>{{ __('Signature') }} - التوقيع:</strong> _________________<br>
                    <strong>{{ __('Date') }} - التاريخ:</strong> _________________
                </div>
            </div>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary">{{ __('Print Contract') }}</button>
        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-secondary">{{ __('Back to Contract') }}</a>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

