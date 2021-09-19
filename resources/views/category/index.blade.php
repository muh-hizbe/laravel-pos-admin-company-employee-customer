<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-gray-800">
            <a href="{{ route('dashboard') }}" class="text-blue-800">Home</a>
            {{ __('/ Category') }}
        </h2>
    </x-slot>

    {{-- HEADER DATATABLE --}}
    @php
        $headers = ['No','Name', 'Products', 'Action'];
    @endphp
    <div class="py-12">
        <div id="modal-edit-category" class="hidden fixed inset-0 z-40 w-auto mx-auto h-screen sm:px-6 lg:px-8 overflow-x-auto bg-blue-700 bg-opacity-70">
            <div class="flex items-center justify-center w-full h-screen">
                <div class="bg-white w-full rounded p-5 mx-auto flex flex-col">
                    <h2 class="text-center font-semibold text-2xl mb-5">Change Category</h2>
                    <input id="id-category" class="w-full rounded mb-3 text-center font-semibold" hidden disabled>
                    <label for="change-name">Name:</label>
                    <input type="text" id="change-name" class="w-full rounded mb-3">
                    <button type="submit" onclick="updateCategory()" class="w-full bg-blue-600 text-white p-2 rounded mb-3">Simpan</button>
                    <button onclick="closeModalEdit()" class="w-full bg-red-600 text-white p-2 rounded">Tutup</button>
                </div>
            </div>
        </div>
        <div class="w-auto mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Data Category
                </div>
                <details class="bg-white shadow-sm rounded-lg my-5">
                    <summary class="cursor-pointer p-6 hover:opacity-25">Add Category</summary>
                    <div class="pb-2">
                        <form method="post" class="border rounded-lg p-4 mx-6 mb-6">
                            @csrf
                            <label for="add-name">Name:</label>
                            <input type="text" name="first_name" id="add-name" placeholder="Name"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <button type="submit" onclick="addCategory()" id="add-new-category" class="transition duration-300 ease-in-out rounded text-white bg-blue-800 hover:bg-blue-600 px-3 py-2">
                                Submit
                            </button>
                        </form>
                    </div>
                </details>
                <div class="p-5 w-full">
                    <x-datatable orderIndex="0" orderType="desc" id="category">
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
                                    var table = $('#category').DataTable({
                                        // responsive: true,
                                        processing: true,
                                        serverSide: true,
                                        ajax: "{{ url('/category/datatable') }}?from={{ isset($query['from']) ? $query['from'] : null }}&to={{ isset($query['to']) ? $query['to'] : null }}",
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
                                            {data: 'products_count', name: 'products_count'},
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
            function addCategory() {
                event.preventDefault();
                let name = document.getElementById('add-name').value

                axios.post(`/category`, {
                    "name" : name,
                })
                .then(({data}) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil menambah data category!'
                    });
                    $('#category').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Gagal menambahkan data category!',
                        icon: 'error'
                    });
                })
            }

            function editCategory(id) {
                let name = document.getElementById('change-name')

                let category_id = document.getElementById('id-category')
                let canvas = document.querySelector('#canvas')
                let modal = document.getElementById('modal-edit-category')

                axios.get(`/category/${id}`)
                    .then(({data}) => {
                        name.value = data.name
                        category_id.value = data.id

                        canvas.scrollTop = 0;
                    })
                    .then(() => {
                        modal.classList.remove('hidden')
                        canvas.classList.remove('overflow-auto')
                        canvas.classList.add('overflow-hidden')
                    })
                    .catch(err => {
                        Swal.fire({title: 'Tidak dapat menemukan data category!', icon: 'error'});
                        console.log(err);
                        canvas.classList.add('overflow-auto')
                        canvas.classList.remove('overflow-hidden')
                    })
            }

            function updateCategory() {
                let name = document.getElementById('change-name').value

                let category_id = document.getElementById('id-category').value
                let modal = document.getElementById('modal-edit-category')

                axios.post(`/category/update/${category_id}`, {
                    "name" : name,
                })
                .then(({data}) => {
                    Swal.fire({title: 'Berhasil mengubah data category!', icon: 'success'});
                    $('#category').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    modal.classList.add('hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({title: 'Tidak dapat memperbaharui data category!', icon: 'error'});
                    modal.classList.add('hidden')
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                })
            }

            function closeModalEdit() {
                let name = document.getElementById('change-name')
                let category_id = document.getElementById('id-category')
                let modal = document.getElementById('modal-edit-category')

                category_id.value = null
                name.value = null

                modal.classList.add('hidden')
                document.querySelector('#canvas').classList.add('overflow-auto')
                document.querySelector('#canvas').classList.remove('overflow-hidden')
            }

            function deleteCategory(id) {
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
                        axios.delete(`/category/${id}/delete`)
                        .then( ({data}) => {
                            Swal.fire({title: 'Berhasil menghapus Category!', icon: 'success'});
                            $('#category').DataTable().ajax.reload();
                            // window.location.reload();
                        })
                        .catch( err => {
                            Swal.fire({title: 'Category gagal dihapus!', icon: 'error'});
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
