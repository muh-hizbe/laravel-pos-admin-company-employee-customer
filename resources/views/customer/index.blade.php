<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-gray-800">
            <a href="{{ route('dashboard') }}" class="text-blue-800">Home</a>
            {{ __('/ Customer') }}
        </h2>
    </x-slot>

    {{-- HEADER DATATABLE --}}
    @php
        $headers = ['No','Name', 'Email', 'Phone', 'Username', 'Address'];
    @endphp

    <div class="py-12">
        <div class="w-auto mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Data Customer
                </div>
                <div class="p-5 w-full">
                    <x-datatable orderIndex="0" orderType="desc" id="customer">
                        <x-slot name="headers">
                            <tr>
                                @forelse ($headers as $key => $header)
                                    <th data-priority="{{ $key +1 }}">{{ $header }}</th>
                                @empty
                                    <th data-priority="1" class="text-center">No Header</th>
                                @endforelse
                            </tr>
                        </x-slot>

                        <x-slot name="datatableScript">
                            <script>
                                $(document).ready(function () {
                                    var table = $('#customer').DataTable({
                                        // responsive: true,
                                        processing: true,
                                        serverSide: true,
                                        ajax: "{{ url('/customer/datatable') }}?from={{ isset($query['from']) ? $query['from'] : null }}&to={{ isset($query['to']) ? $query['to'] : null }}",
                                        dom: 'Blfrtip',
                                        buttons: [
                                            {
                                                extend: 'excel',
                                                action: newExportAction
                                            }
                                        ],
                                        order: [[ 1, 'desc']],
                                        columns: [
                                            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                            {data: 'fullname', name: 'fullname'},
                                            {data: 'email', name: 'email'},
                                            {data: 'phone_number', name: 'phone_number'},
                                            {data: 'username', name: 'username'},
                                            {data: 'address', name: 'address'}
                                        ]
                                    })
                                });

                                $('table').ready(function () {
                                    $('table').wrap('<div class="overflow-x-auto w-full"></div>');
                                });
                            </script>

                            @include('scripts.function-dt')
                        </x-slot>
                    </x-datatable>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
