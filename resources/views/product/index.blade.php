<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-gray-800">
            <a href="{{ route('dashboard') }}" class="text-blue-800">Home</a>
            {{ __('/ Product') }}
        </h2>
    </x-slot>

    {{-- HEADER DATATABLE --}}
    @php
        $headers = ['No','Name', 'Category', 'Price', 'Discount Price', 'Stock', 'Action'];
    @endphp
    <div class="py-12">
        <div id="modal-edit-product" class="hidden fixed inset-0 z-40 w-auto mx-auto h-screen sm:px-6 lg:px-8 overflow-x-auto bg-blue-700 bg-opacity-70">
            <div class="flex items-center justify-center w-full h-screen">
                <div class="bg-white w-full rounded p-5 mx-auto flex flex-col">
                    <h2 class="text-center font-semibold text-2xl mb-5">Change Product</h2>
                    <input id="id-product" class="w-full rounded mb-3 text-center font-semibold" hidden disabled>
                    <label for="change-name">Name:</label>
                    <input type="text" id="change-name" class="w-full rounded mb-3">
                    <label for="change-category">Category:</label>
                    <select name="category_id" id="change-category_id" class="">
                        <option value='0'>-- Select Category --</option>
                    </select>
                    <label for="change-price">Price:</label>
                    <input type="number" id="change-price" class="w-full rounded mb-3">
                    <label for="change-discount_price">Discount Price:</label>
                    <input type="number" id="change-discount_price" class="w-full rounded mb-3">
                    <label for="change-stock">Stock:</label>
                    <input type="number" id="change-stock" class="w-full rounded mb-3">
                    <button type="submit" onclick="updateProduct()" class="w-full bg-blue-600 text-white p-2 rounded mb-3">Simpan</button>
                    <button onclick="closeModalEdit()" class="w-full bg-red-600 text-white p-2 rounded">Tutup</button>
                </div>
            </div>
        </div>
        <div class="w-auto mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Data Product
                </div>
                <details class="bg-white shadow-sm rounded-lg my-5">
                    <summary class="cursor-pointer p-6 hover:opacity-25">Add Product</summary>
                    <div class="pb-2">
                        <form method="post" class="border rounded-lg p-4 mx-6 mb-6">
                            @csrf
                            <label for="add-name">Name:</label>
                            <input type="text" name="name" id="add-name" placeholder="Name"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <select name="category_id" id="add-category_id" class="">
                                <option value='0'>-- Select Category --</option>
                            </select>
                            <input id="add-company_id" class="w-full rounded mb-3 text-center font-semibold" hidden disabled>
                            <label for="add-price">Price:</label>
                            <input type="number" name="price" id="add-price" placeholder="0"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <label for="add-discount_price">Discount Price:</label>
                            <input type="number" name="discount_price" id="add-discount_price" placeholder="0"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <label for="add-stock">Stock:</label>
                            <input type="number" name="stock" id="add-stock" placeholder="0"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <button type="submit" onclick="addProduct()" id="add-new-product" class="transition duration-300 ease-in-out rounded text-white bg-blue-800 hover:bg-blue-600 px-3 py-2">
                                Submit
                            </button>
                        </form>
                    </div>
                </details>
                <div class="p-5 w-full">
                    <x-datatable orderIndex="0" orderType="desc" id="product">
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
                                    var table = $('#product').DataTable({
                                        // responsive: true,
                                        processing: true,
                                        serverSide: true,
                                        ajax: "{{ url('/product/datatable') }}?from={{ isset($query['from']) ? $query['from'] : null }}&to={{ isset($query['to']) ? $query['to'] : null }}",
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
                                            {data: 'name', name: 'name'},
                                            {data: 'category', name: 'category'},
                                            {data: 'price', name: 'price'},
                                            {data: 'discount_price', name: 'discount_price'},
                                            {data: 'stock', name: 'stock'},
                                            {data: 'action', name: 'action'}
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

            function addProduct() {
                event.preventDefault();
                let name = document.getElementById('add-name').value
                let category_id = document.getElementById('add-category_id').value
                let company_id = {{ auth()->user()->id }}
                let price = document.getElementById('add-price').value
                let discount_price = document.getElementById('add-discount_price').value
                let stock = document.getElementById('add-stock').value

                axios.post(`/product`, {
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
                        title: 'Berhasil menambah data product!'
                    });
                    $('#product').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Gagal menambahkan data product!',
                        icon: 'error'
                    });
                })
            }

            function editProduct(id) {
                let name = document.getElementById('change-name')
                let category_id = document.getElementById('change-category_id')
                let price = document.getElementById('change-price')
                let discount_price = document.getElementById('change-discount_price')
                let stock = document.getElementById('change-stock')

                let product_id = document.getElementById('id-product')
                let canvas = document.querySelector('#canvas')
                let modal = document.getElementById('modal-edit-product')

                axios.get(`/product/${id}`)
                    .then(({data}) => {
                        name.value = data.name
                        category_id.value = data.category_id
                        price.value = data.price
                        discount_price.value = data.discount_price
                        stock.value = data.stock
                        product_id.value = data.id

                        canvas.scrollTop = 0;
                    })
                    .then(() => {
                        modal.classList.remove('hidden')
                        canvas.classList.remove('overflow-auto')
                        canvas.classList.add('overflow-hidden')
                    })
                    .catch(err => {
                        Swal.fire({title: 'Tidak dapat menemukan data product!', icon: 'error'});
                        console.log(err);
                        canvas.classList.add('overflow-auto')
                        canvas.classList.remove('overflow-hidden')
                    })
            }

            function updateProduct() {
                let name = document.getElementById('change-name').value
                let category_id = document.getElementById('change-category_id').value
                let price = document.getElementById('change-price').value
                let discount_price = document.getElementById('change-discount_price').value
                let stock = document.getElementById('change-stock').value

                let product_id = document.getElementById('id-product').value
                let modal = document.getElementById('modal-edit-product')

                axios.post(`/product/update/${product_id}`, {
                    "name" : name,
                    "category_id" : category_id,
                    "price" : price,
                    "discount_price" : discount_price,
                    "stock" : stock,
                })
                .then(({data}) => {
                    Swal.fire({title: 'Berhasil mengubah data product!', icon: 'success'});
                    $('#product').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    modal.classList.add('hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({title: 'Tidak dapat memperbaharui data product!', icon: 'error'});
                    modal.classList.add('hidden')
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                })
            }

            function closeModalEdit() {
                let name = document.getElementById('change-name')
                let product_id = document.getElementById('id-product')
                let modal = document.getElementById('modal-edit-product')

                product_id.value = null
                name.value = null

                modal.classList.add('hidden')
                document.querySelector('#canvas').classList.add('overflow-auto')
                document.querySelector('#canvas').classList.remove('overflow-hidden')
            }

            function deleteProduct(id) {
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
                        axios.delete(`/product/${id}/delete`)
                        .then( ({data}) => {
                            Swal.fire({title: 'Berhasil menghapus Product!', icon: 'success'});
                            $('#product').DataTable().ajax.reload();
                            // window.location.reload();
                        })
                        .catch( err => {
                            Swal.fire({title: 'Product gagal dihapus!', icon: 'error'});
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
