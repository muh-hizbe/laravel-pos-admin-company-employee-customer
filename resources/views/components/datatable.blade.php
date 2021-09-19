@props([
    'id' => 'example',
    'button' => ['copy', 'excel', 'pdf'],
    'orderIndex' => 0,
    'orderType' => 'asc'
])

@push('style')
    <!--Regular Datatables CSS-->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <!--Responsive Extension Datatables CSS-->
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <!--Button Extension Datatables CSS-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">
    <style>
        /* Overrides to match the Tailwind CSS */

        .dataTables_wrapper {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem
        }

        table {
            width: 100% !important;
            overflow-x: auto !important;
            /* padding: 1rem; */
        }

        table.dataTable.no-footer {
            border-bottom-width: 1px;
            border-color: #d2d6dc;
            width: 100vw;
            overflow-x: auto;
        }

        table.dataTable tbody td, table.dataTable tbody th {
            padding: 0.75rem 1rem;
            border-bottom-width: 1px;
            border-color: #d2d6dc;
        }

        div.dt-buttons {
            padding: 1rem 1rem 1rem 1rem;
            display: flex;
            align-items: center
        }

        .dataTables_filter, .dataTables_info {
            padding: 1rem
        }

        .dataTables_wrapper .dataTables_paginate {
            padding: 1rem
        }

        .dataTables_filter label input {
            padding: 0.25rem .5rem;
            border-width: 1px;
            border-radius: 0.25rem
        }

        .dataTables_filter label input:focus {
            box-shadow: 0 0 0 3px rgba(118, 169, 250, 0.45);
            outline: none;
        }

        table.dataTable thead, table.dataTable tbody {
            white-space: nowrap;
        }

        table.dataTable thead tr {
            border-radius: 0.5rem
        }

        table.dataTable thead tr th:not(.text-center) {
            text-align: left
        }

        table.dataTable thead tr th {
            background-color: #edf2f7;
            border-bottom-width: 2px;
            border-top-width: 1px;
            border-color: #d2d6dc
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current:not(.disabled), .dataTables_wrapper .dataTables_paginate .paginate_button.next:not(.disabled), .dataTables_wrapper .dataTables_paginate .paginate_button.previous:not(.disabled), .dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled), button.dt-button {
            transition-duration: 150ms;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #374151 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            font-size: 0.75rem;
            font-weight: 600;
            align-items: center;
            display: inline-flex;
            border-width: 1px !important;
            border-color: #d2d6dc !important;
            border-radius: 0.375rem;
            background: #ffffff;
            overflow: visible;
            margin-bottom: 0
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.next:focus:not(.disabled), .dataTables_wrapper .dataTables_paginate .paginate_button.next:hover:not(.disabled), .dataTables_wrapper .dataTables_paginate .paginate_button.previous:focus:not(.disabled), .dataTables_wrapper .dataTables_paginate .paginate_button.previous:hover:not(.disabled), .dataTables_wrapper .dataTables_paginate .paginate_button:focus:not(.disabled), .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled), button.dt-button:focus, button.dt-button:focus:not(.disabled), button.dt-button:hover, button.dt-button:hover:not(.disabled) {
            background-color: #edf2f7 !important;
            border-width: 1px !important;
            border-color: #d2d6dc !important;
            color: #374151 !important
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current:not(.disabled) {
            background: #6875f5 !important;
            color: #ffffff !important;
            border-color: #8da2fb !important
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #8da2fb !important;
            color: #ffffff !important;
            border-color: #8da2fb !important
        }

        .dataTables_length select {
            padding-top: .25rem;
            padding-bottom: .25rem;
            border-radius: .25rem;
        }

        .dataTables_length {
            padding-top: 1rem;
        }

        .dt-buttons, .dataTables_filter {

        }

        table tbody p {
            position: relative;
            color: rgba(16, 185, 129, 1);
            background-color: rgba(17, 24, 39, 1);
            max-width: 300px;
            max-height: 300px;
            overflow-x: auto !important;
            z-index: 1;
        }

        table tbody tr td input[type=checkbox] {
            opacity: 1;
            cursor: pointer;
            z-index: 1;
            touch-action: manipulation;
            padding: 8px;
        }

        table tbody tr td input[type=checkbox]:checked ~ p {
            margin: 0;
            /* max-height: 0; */
            transform: .3s;
            opacity: 0;
        }
    </style>
@endpush

<div id='recipients' class="mt-6 lg:mt-0 rounded shadow bg-white">
    <table id="{{ $id }}">
        <thead>
            {{ $headers }}
        </thead>
        <tbody>
            @if (isset($data))
                {{ $data }}
            @endif
        </tbody>
    </table>
</div>

@push('script')
    {{-- <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> --}}

    <!--Datatables -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/datatables.min.js"></script>
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.25/sorting/formatted-numbers.js"></script> --}}

    @if (isset($datatableScript))
        {{ $datatableScript }}
    @else
        <script>
            $(document).ready(function () {
                let table = $('#{{ $id }}').DataTable({
                    responsive: true,
                    dom: 'Blfrtip',
                    buttons: [
                        'excel', 'print'
                    ],
                    order: [[ {!! json_encode($orderIndex) !!}, {!! json_encode($orderType) !!}]]
                }).columns.adjust().responsive.recalc();
            });
            $('table').ready(function () {
                $('table').wrap('<div class="overflow-x-auto w-full"></div>');
            });
        </script>
    @endif
@endpush
