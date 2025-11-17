<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Offer') }} #{{ $offer->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { padding: 2rem; font-size: 14px; }
        .section-title { border-bottom: 2px solid #0d6efd; margin-top: 1.5rem; padding-bottom: .5rem; }
    </style>
</head>
<body onload="window.print()">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">{{ config('app.name') }}</h2>
            <small>{{ __('Quotation Summary') }}</small>
        </div>
        <div class="text-end">
            <strong>{{ __('Offer #') }}:</strong> {{ $offer->id }}<br>
            <strong>{{ __('Date') }}:</strong> {{ optional($offer->created_at)->format('Y-m-d') }}
        </div>
    </div>

    <div class="section-title">{{ __('Customer') }}</div>
    <p class="mb-1"><strong>{{ $offer->customer['name'] ?? '' }}</strong></p>
    <p class="mb-1">{{ $offer->customer['city'] ?? '' }}</p>
    <p class="mb-1">{{ $offer->customer['address'] ?? '' }}</p>
    <p class="mb-0">{{ $offer->customer['mobile'] ?? '' }}</p>

    <div class="section-title">{{ __('Offer Details') }}</div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>{{ __('Container Size') }}</th>
                <th>{{ __('Containers') }}</th>
                <th>{{ __('Monthly Unloads') }}</th>
                <th>{{ __('Unit Price') }}</th>
                <th>{{ __('Monthly Total') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $offer->size?->name ?? __('N/A') }}</td>
                <td>{{ $offer->no_containers }}</td>
                <td>{{ $offer->monthly_dumping_cont }}</td>
                <td>{{ number_format($offer->container_price, 2) }} {{ __('SAR') }}</td>
                <td>{{ number_format($offer->monthly_total_dumping_cost, 2) }} {{ __('SAR') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6">
            <p class="mb-1">{{ __('Contract Period') }}: {{ $offer->contract_period }} {{ __('months') }}</p>
            <p class="mb-1">{{ __('Start Date') }}: {{ optional($offer->start_date)->format('Y-m-d') }}</p>
            <p class="mb-1">{{ __('End Date') }}: {{ optional($offer->end_date)->format('Y-m-d') }}</p>
            <p class="mb-0">{{ __('Status') }}: {{ ucfirst($offer->status) }}</p>
        </div>
        <div class="col-md-6">
            <table class="table">
                <tr>
                    <td>{{ __('Subtotal') }}</td>
                    <td class="text-end">{{ number_format($offer->monthly_total_dumping_cost, 2) }} {{ __('SAR') }}</td>
                </tr>
                <tr>
                    <td>{{ __('VAT ') }}(15%)</td>
                    <td class="text-end">{{ number_format($offer->monthly_total_dumping_cost * 0.15, 2) }} {{ __('SAR') }}</td>
                </tr>
                <tr>
                    <th>{{ __('Total With VAT') }}</th>
                    <th class="text-end">{{ number_format($offer->monthly_total_dumping_cost * 1.15, 2) }} {{ __('SAR') }}</th>
                </tr>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <p class="fw-bold mb-1">{{ __('Notes') }}</p>
        <p>{{ $offer->notes ?? __('N/A') }}</p>
    </div>
</body>
</html>

