<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Offer Information') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">{{ __('Offer #') }}:</th>
                        <td><strong>#{{ $offer->id }}</strong></td>
                    </tr>
                    <tr>
                        <th>{{ __('Customer') }}:</th>
                        <td>{{ $offer->customer['name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Contact Person') }}:</th>
                        <td>{{ $offer->customer['name'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('phone') }}:</th>
                        <td>{{ $offer->customer['phone'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Mobile') }}:</th>
                        <td>{{ $offer->customer['mobile'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('City') }}:</th>
                        <td>{{ $offer->customer['city'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Address') }}:</th>
                        <td>{{ $offer->customer['address'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">{{ __('Container Type') }}:</th>
                        <td>{{ $offer->type->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Container Price') }}:</th>
                        <td><strong>{{ number_format($offer->container_price, 2) }} {{ __('SAR') }}</strong></td>
                    </tr>
                    <tr>
                        <th>{{ __('Number of Containers') }}:</th>
                        <td>{{ $offer->no_containers }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Period (Months)') }}:</th>
                        <td>{{ $offer->contract_period }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Start Date') }}:</th>
                        <td>{{ optional($offer->start_date)->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('End Date') }}:</th>
                        <td>{{ optional($offer->end_date)->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Status') }}:</th>
                        <td>{{ ucfirst($offer->status ?? 'draft') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


