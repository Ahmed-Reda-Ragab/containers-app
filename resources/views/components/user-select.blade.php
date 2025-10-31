@props([
    'name' => 'user_id',
    'id' => 'user_id',
    'label' => 'User',
    'required' => false,
    'userType' => null,
    'placeholder' => 'Choose user...',
    'class' => 'form-select'
])

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if($required) * @endif</label>
    <select 
        class="{{ $class }}" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        @if($required) required @endif
        data-role="{{ $userType }}"
        data-search-url="{{ route('users.search') }}"
    >
        <option value="">{{ $placeholder }}</option>
    </select>
    <div class="form-text">{{ __('Start typing to search users...') }}</div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    // Ensure the script runs only once even if pushed multiple times
    if (window.__userSelectInitialized) return;
    window.__userSelectInitialized = true;

    // AJAX User Search Function
    function searchUsers(searchTerm, userType, selectElement) {
        const searchUrl = selectElement.data('search-url');
        
        $.ajax({
            url: searchUrl,
            method: 'GET',
            data: {
                search: searchTerm,
                user_type: userType
            },
            success: function(response) {
                const currentValue = selectElement.val();
                selectElement.empty();
                selectElement.append(`<option value="">{{ $placeholder }}</option>`);
                
                response.users.forEach(function(user) {
                    const option = `<option value="${user.id}">${user.name} (${user.phone}) - ${user.user_type_label}</option>`;
                    selectElement.append(option);
                });
                
                // Restore selected value if it exists
                if (currentValue) {
                    selectElement.val(currentValue);
                }
            },
            error: function() {
                console.error('Error searching users');
            }
        });
    }

    // Handle user search on focus
    $(document).on('focus', 'select[data-search-url]', function() {
        const selectElement = $(this);
        const userType = selectElement.data('role');
        
        // If no options loaded yet, load them
        if (selectElement.find('option').length <= 1) {
            searchUsers('', userType, selectElement);
        }
    });

    // Handle user search on change (for real-time search)
    $(document).on('change', 'select[data-search-url]', function() {
        const selectElement = $(this);
        const userType = selectElement.data('role');
        const searchTerm = selectElement.find('option:selected').text();
        
        // If user typed something, search
        if (searchTerm && searchTerm !== '{{ $placeholder }}') {
            searchUsers(searchTerm, userType, selectElement);
        }
    });
});
</script>
@endpush
