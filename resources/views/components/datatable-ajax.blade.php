@props([
    'id' => 'dataTable',
    'url' => null,
    'columns' => [],
    'dataColumns' => [],
    'searchable' => true,
    'filterable' => false,
    'filters' => [],
    'pageLength' => 25,
    'responsive' => true,
    'order' => [[0, 'desc']],
    'language' => 'en'
])

<div class="datatable-container">
    @if($searchable || $filterable)
        <div class="datatable-controls mb-3">
            <div class="row justify-content-end">
               
                
                @if($filterable && !empty($filters))
                    <div class="col-md-6">
                        <div class="row justify-content-end">
                            @foreach($filters as $filter)
                                <div class="col-md-6">
                                    <select class="form-select filter-datatable" name="{{ $filter['name'] }}" id="{{ $id }}_filter_{{ $filter['name'] }}">
                                        <option value="">{{ $filter['placeholder'] ?? __('customers.all_types') }}</option>
                                        @foreach($filter['options'] as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="table-responsive">
        <table id="{{ $id }}" class="table table-striped table-hover" style="width:100%">
            <thead class="table-dark">
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column['title'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded via AJAX -->
            </tbody>
        </table>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
.datatable-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
}

.datatable-controls {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
    margin: 10px 0;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 8px 12px;
    margin: 0 2px;
    border-radius: 4px;
    border: 1px solid #dee2e6;
    background: white;
    color: #495057;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    background: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
}

.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    border: none;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin: 0 2px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@if($responsive)
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
@endif
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    const tableId = '{{ $id }}';
    const language = '{{ $language }}';
    
    // Language configuration
    const languageConfig = {
        'en': {
            "lengthMenu": "Show _MENU_ entries",
            "zeroRecords": "{{ __('customers.no_data') }}",
            "info": "{{ __('customers.showing') }} _START_ {{ __('customers.to') }} _END_ {{ __('customers.of') }} _TOTAL_ {{ __('customers.entries') }}",
            "infoEmpty": "{{ __('customers.showing') }} 0 {{ __('customers.to') }} 0 {{ __('customers.of') }} 0 {{ __('customers.entries') }}",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "search": "{{ __('customers.search') }}:",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "{{ __('customers.next') }}",
                "previous": "{{ __('customers.previous') }}"
            }
        },
        'ar': {
            "lengthMenu": "عرض _MENU_ إدخال",
            "zeroRecords": "{{ __('customers.no_data') }}",
            "info": "{{ __('customers.showing') }} _START_ {{ __('customers.to') }} _END_ {{ __('customers.of') }} _TOTAL_ {{ __('customers.entries') }}",
            "infoEmpty": "{{ __('customers.showing') }} 0 {{ __('customers.to') }} 0 {{ __('customers.of') }} 0 {{ __('customers.entries') }}",
            "infoFiltered": "(مفلتر من _MAX_ إدخال إجمالي)",
            "search": "{{ __('customers.search') }}:",
            "paginate": {
                "first": "الأول",
                "last": "الأخير",
                "next": "{{ __('customers.next') }}",
                "previous": "{{ __('customers.previous') }}"
            }
        }
    };

    // DataTable configuration
    const config = {
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ $url }}',
            type: 'GET',
            data: function(d) {
                $('.filter-datatable').each(function() {
                    if($(this).attr('name'))
                        d[$(this).attr('name')] = $(this).val();
                });
                console.log(d);
            }
        },
        pageLength: {{ $pageLength }},
        columns: {!! json_encode($dataColumns) !!},
        order: {!! json_encode($order) !!},
        language: languageConfig[language] || languageConfig['en'],
        responsive: {{ $responsive ? 'true' : 'false' }},
        columnDefs: [
            { orderable: false, targets: -1 } // Make last column (actions) non-orderable
        ]
    };

    // Initialize DataTable
    const table = $('#' + tableId).DataTable(config);

    // Search functionality
    @if($searchable)
    $('#{{ $id }}_search').on('keyup', function() {
        table.search(this.value).draw();
    });
    @endif

    // Filter functionality
    @if($filterable && !empty($filters))
        @foreach($filters as $filter)
        $('#{{ $id }}_filter_{{ $filter['name'] }}').on('change', function() {
            const columnIndex = {{ $filter['column'] ?? 0 }};
            const value = this.value;
            
            if (value === '') {
                table.column(columnIndex).search('').draw();
            } else {
                table.column(columnIndex).search(value, true, false).draw();
            }
        });
        @endforeach
    @endif

    // Delete confirmation with SweetAlert2
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        
        const deleteUrl = $(this).attr('href');
        const customerId = $(this).data('id');
        
        Swal.fire({
            title: '{{ __("customers.confirm_delete_title") }}',
            text: '{{ __("customers.confirm_delete_text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("customers.delete") }}',
            cancelButtonText: '{{ __("customers.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form and submit
                const form = $('<form>', {
                    'method': 'POST',
                    'action': deleteUrl
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': $('meta[name="csrf-token"]').attr('content')
                }));
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush
