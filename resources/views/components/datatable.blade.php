@props([
    'id' => 'dataTable',
    'columns' => [],
    'searchable' => true,
    'filterable' => false,
    'filters' => [],
    'ajax' => false,
    'url' => null,
    'pageLength' => 25,
    'responsive' => true,
    'order' => [[0, 'desc']],
    'language' => 'en'
])

<div class="datatable-container">
    @if($searchable || $filterable)
        <div class="datatable-controls mb-3">
            <div class="row">
                @if($searchable)
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="{{ $id }}_search" 
                                   placeholder="{{ __('containers.search_placeholder') }}">
                        </div>
                    </div>
                @endif
                
                @if($filterable && !empty($filters))
                    <div class="col-md-6">
                        <div class="row">
                            @foreach($filters as $filter)
                                <div class="col-md-6">
                                    <select class="form-select" id="{{ $id }}_filter_{{ $filter['name'] }}">
                                        <option value="">{{ $filter['placeholder'] ?? __('containers.all_sizes') }}</option>
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
                {{ $slot }}
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

<script>
$(document).ready(function() {
    const tableId = '{{ $id }}';
    const language = '{{ $language }}';
    
    // Language configuration
    const languageConfig = {
        'en': {
            "lengthMenu": "Show _MENU_ entries",
            "zeroRecords": "{{ __('containers.no_data') }}",
            "info": "{{ __('containers.showing') }} _START_ {{ __('containers.to') }} _END_ {{ __('containers.of') }} _TOTAL_ {{ __('containers.entries') }}",
            "infoEmpty": "{{ __('containers.showing') }} 0 {{ __('containers.to') }} 0 {{ __('containers.of') }} 0 {{ __('containers.entries') }}",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "search": "{{ __('containers.search') }}:",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "{{ __('containers.next') }}",
                "previous": "{{ __('containers.previous') }}"
            }
        },
        'ar': {
            "lengthMenu": "عرض _MENU_ إدخال",
            "zeroRecords": "{{ __('containers.no_data') }}",
            "info": "{{ __('containers.showing') }} _START_ {{ __('containers.to') }} _END_ {{ __('containers.of') }} _TOTAL_ {{ __('containers.entries') }}",
            "infoEmpty": "{{ __('containers.showing') }} 0 {{ __('containers.to') }} 0 {{ __('containers.of') }} 0 {{ __('containers.entries') }}",
            "infoFiltered": "(مفلتر من _MAX_ إدخال إجمالي)",
            "search": "{{ __('containers.search') }}:",
            "paginate": {
                "first": "الأول",
                "last": "الأخير",
                "next": "{{ __('containers.next') }}",
                "previous": "{{ __('containers.previous') }}"
            }
        }
    };

    // DataTable configuration
    const config = {
        pageLength: {{ $pageLength }},
        order: {!! json_encode($order) !!},
        language: languageConfig[language] || languageConfig['en'],
        responsive: {{ $responsive ? 'true' : 'false' }},
        @if($ajax && $url)
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ $url }}',
            type: 'GET'
        },
        @endif
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
});
</script>
@endpush
