<div class="modal fade" id="dischargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="dischargeForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Discharge Container') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="discharge_date" class="form-label">{{ __('Discharge Date') }} *</label>
                        <input type="date" class="form-control" id="discharge_date" name="discharge_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="discharge_id" class="form-label">{{ __('Discharged By') }} *</label>
                        <select class="form-select" id="discharge_id" name="discharge_id" required>
                            <option value="">{{ __('Choose user...') }}</option>
                            @foreach(App\Models\User::all(['id', 'name']) as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="discharge_car_id" class="form-label">{{ __('Discharge Car') }}</label>
                        <select class="form-select" id="discharge_car_id" name="discharge_car_id">
                            <option value="">{{ __('Choose car...') }}</option>
                            @foreach(App\Models\Car::all(['id', 'number']) as $car)
                                <option value="{{ $car->id }}">{{ $car->number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('Discharge Container') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function () {
    // Ensure the script runs only once even if pushed multiple times
    if (window.__dischargeModalInitialized) return;
    window.__dischargeModalInitialized = true;

    // When any button with data-bs-target="#dischargeModal" is clicked
    $(document).on('click', '[data-bs-target="#dischargeModal"]', function () {
        const url = $(this).data('url');
        const containerNo = $(this).data('no') || '';
        const userRole = $(this).data('user-role') || 'driver';
        const modal = $('#dischargeModal');

        // Set form action
        modal.find('form').attr('action', url);

        // Update user role filter
        modal.find('#discharge_id').data('role', userRole);

        // Update modal title (optional)
        modal.find('.modal-title').text(`{{ __('Discharge Container') }} ${containerNo ? '#' + containerNo : ''}`);
    });
});
</script>
@endpush
