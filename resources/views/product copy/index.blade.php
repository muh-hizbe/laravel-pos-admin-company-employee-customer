<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-gray-800">
            <a href="{{ route('dashboard') }}" class="text-blue-800">Home</a>
            {{ __('/ Transaction') }}
        </h2>
    </x-slot>

    {{-- HEADER DATATABLE --}}
    @php
        $headers = ['No','Customer', 'Phone', 'Product', 'Price', 'Discount Price', 'Quantity', 'Total', 'Waktu'];
    @endphp
    <div class="py-12">
        <div class="w-auto mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Data Transaction
                </div>
                <div class="p-5 w-full">
                    <x-datatable orderIndex="0" orderType="desc" id="transaction">
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
                                    var table = $('#transaction').DataTable({
                                        // responsive: true,
                                        processing: true,
                                        serverSide: true,
                                        ajax: "{{ route('transaction.dataTable') }}",
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
                                            {data: 'customer_name', name: 'customer_name'},
                                            {data: 'customer_phone', name: 'customer_phone'},
                                            {data: 'product_name', name: 'product_name'},
                                            {data: 'price', name: 'price'},
                                            {data: 'discount_price', name: 'discount_price'},
                                            {data: 'quantity', name: 'quantity'},
                                            {data: 'total', name: 'total'},
                                            {data: 'created_at', name: 'created_at'}
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

    <x-slot name="script">
        <script>
            $('#add-category_id').select2({
                placeholder: "Select a category",
                ajax: {
                    url: "{{ route('category.dataJson') }}",
                    type: "get",
                    delay: 50,
                    dataType: 'json',
                    processResults: function(response) {
                        return {
                            results: $.map(response, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $('#change-category_id').select2({
                placeholder: "Select a category",
                ajax: {
                    url: "{{ route('category.dataJson') }}",
                    type: "get",
                    delay: 50,
                    dataType: 'json',
                    processResults: function(response) {
                        return {
                            results: $.map(response, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            function addTransaction() {
                event.preventDefault();
                let name = document.getElementById('add-name').value
                let category_id = document.getElementById('add-category_id').value
                let company_id = {{ auth()->user()->id }}
                let price = document.getElementById('add-price').value
                let discount_price = document.getElementById('add-discount_price').value
                let stock = document.getElementById('add-stock').value

                axios.post(`/transaction`, {
                    "name" : name,
                    "category_id" : category_id,
                    "company_id" : company_id,
                    "price" : price,
                    "discount_price" : discount_price,
                    "stock" : stock,
                })
                .then(({data}) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil menambah data transaction!'
                    });
                    $('#transaction').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Gagal menambahkan data transaction!',
                        icon: 'error'
                    });
                })
            }

            function editTransaction(id) {
                let name = document.getElementById('change-name')
                let category_id = document.getElementById('change-category_id')
                let price = document.getElementById('change-price')
                let discount_price = document.getElementById('change-discount_price')
                let stock = document.getElementById('change-stock')

                let transaction_id = document.getElementById('id-transaction')
                let canvas = document.querySelector('#canvas')
                let modal = document.getElementById('modal-edit-transaction')

                axios.get(`/transaction/${id}`)
                    .then(({data}) => {
                        name.value = data.name
                        category_id.value = data.category_id
                        price.value = data.price
                        discount_price.value = data.discount_price
                        stock.value = data.stock
                        transaction_id.value = data.id

                        canvas.scrollTop = 0;
                    })
                    .then(() => {
                        modal.classList.remove('hidden')
                        canvas.classList.remove('overflow-auto')
                        canvas.classList.add('overflow-hidden')
                    })
                    .catch(err => {
                        Swal.fire({title: 'Tidak dapat menemukan data transaction!', icon: 'error'});
                        console.log(err);
                        canvas.classList.add('overflow-auto')
                        canvas.classList.remove('overflow-hidden')
                    })
            }

            function updateTransaction() {
                let name = document.getElementById('change-name').value
                let category_id = document.getElementById('change-category_id').value
                let price = document.getElementById('change-price').value
                let discount_price = document.getElementById('change-discount_price').value
                let stock = document.getElementById('change-stock').value

                let transaction_id = document.getElementById('id-transaction').value
                let modal = document.getElementById('modal-edit-transaction')

                axios.post(`/transaction/update/${transaction_id}`, {
                    "name" : name,
                    "category_id" : category_id,
                    "price" : price,
                    "discount_price" : discount_price,
                    "stock" : stock,
                })
                .then(({data}) => {
                    Swal.fire({title: 'Berhasil mengubah data transaction!', icon: 'success'});
                    $('#transaction').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    modal.classList.add('hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({title: 'Tidak dapat memperbaharui data transaction!', icon: 'error'});
                    modal.classList.add('hidden')
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                })
            }

            function closeModalEdit() {
                let name = document.getElementById('change-name')
                let transaction_id = document.getElementById('id-transaction')
                let modal = document.getElementById('modal-edit-transaction')

                transaction_id.value = null
                name.value = null

                modal.classList.add('hidden')
                document.querySelector('#canvas').classList.add('overflow-auto')
                document.querySelector('#canvas').classList.remove('overflow-hidden')
            }

            function deleteTransaction(id) {
                Swal.fire({
                    title: 'Konfirmasi hapus?',
                    text: 'Yakin dihapus!',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Hapus!'
                }).then((result) => {
                    //if user clicks on delete button
                    if (result.value) {
                        // calling destroy method to delete data
                        axios.delete(`/transaction/${id}/delete`)
                        .then( ({data}) => {
                            Swal.fire({title: 'Berhasil menghapus Transaction!', icon: 'success'});
                            $('#transaction').DataTable().ajax.reload();
                            // window.location.reload();
                        })
                        .catch( err => {
                            Swal.fire({title: 'Transaction gagal dihapus!', icon: 'error'});
                        })
                    } else {
                        Swal.fire({
                            title: 'Dibatalkan!',
                            icon: 'success'
                        });
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>
