<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-gray-800">
            <a href="{{ route('dashboard') }}" class="text-blue-800">Home</a>
            {{ __('/ Employee') }}
        </h2>
    </x-slot>

    {{-- HEADER DATATABLE --}}
    @php
        $headers = ['No','Name', 'Email', 'Phone', 'Username', 'Address', 'Action'];

        $parts = parse_url($_SERVER['REQUEST_URI']);
        isset($parts['query']) ? parse_str($parts['query'], $query) : null;
    @endphp
    {{-- @dd($query['from']) --}}
    <div class="py-12">
        <div id="modal-edit-employee" class="hidden fixed inset-0 z-40 w-auto mx-auto h-screen sm:px-6 lg:px-8 overflow-x-auto bg-blue-700 bg-opacity-70">
            <div class="flex items-center justify-center w-full h-screen">
                <div class="bg-white w-full rounded p-5 mx-auto flex flex-col">
                    <h2 class="text-center font-semibold text-2xl mb-5">Change Employee Profile</h2>
                    <input id="id-employee" class="w-full rounded mb-3 text-center font-semibold" hidden disabled>
                    <input id="id-employee-profile" class="w-full rounded mb-3 text-center font-semibold" hidden disabled>
                    <label for="change-firstname">First Name:</label>
                    <input type="text" id="change-firstname" class="w-full rounded mb-3">
                    <label for="change-lastname">Last Name:</label>
                    <input type="text" id="change-lastname" class="w-full rounded mb-3">
                    {{-- <label for="change-username">Username:</label>
                    <input type="text" id="change-username" class="w-full rounded mb-3">
                    <label for="change-email">Email:</label>
                    <input type="email" id="change-email" class="w-full rounded mb-3"> --}}
                    <label for="change-phone">Phone:</label>
                    <input type="tel" id="change-phone" class="w-full rounded mb-3">
                    <label for="change-address">Address:</label>
                    <textarea id="change-address" class="w-full rounded mb-3"></textarea>
                    <button type="submit" onclick="updateEmployee()" class="w-full bg-blue-600 text-white p-2 rounded mb-3">Simpan</button>
                    <button onclick="closeModalEdit()" class="w-full bg-red-600 text-white p-2 rounded">Tutup</button>
                </div>
            </div>
        </div>
        <div class="w-auto mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Data Employee
                </div>
                <details class="bg-white shadow-sm rounded-lg my-5">
                    <summary class="cursor-pointer p-6 hover:opacity-25">Add Employee</summary>
                    <div class="pb-2">
                        <form method="post" class="border rounded-lg p-4 mx-6 mb-6">
                            @csrf
                            <label for="add-firstname">First Name:</label>
                            <input type="text" name="first_name" id="add-firstname" placeholder="First name"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                                <label for="add-lastname">Last Name:</label>
                            <input type="text" name="last_name" id="add-lastname" placeholder="Last name"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <label for="add-username">Username:</label>
                            <input type="text" name="username" id="add-username" placeholder="Username"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <label for="add-phone">Phone:</label>
                            <input type="tel" name="phone" id="add-phone" placeholder="Phone number"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <label for="add-email">Email:</label>
                            <input type="email" name="email" id="add-email" placeholder="Email address"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <label for="add-password">Password:</label>
                            <input type="password" name="password" id="add-password" placeholder="Password"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <label for="add-password-confirmation">Password Confirmation:</label>
                            <input type="password" name="password-confirmation" id="add-password-confirmation" placeholder="Password confirmation"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3">
                            <label for="add-address">Address:</label>
                            <textarea name="address" id="add-address" placeholder="Address"
                                    class="transition duration-300 ease-in-out rounded shadow border-1 border-none bg-gray-100 p-2 w-full focus:outline-none focus:bg-white focus:ring-2 ring-blue-800 mb-3"></textarea>
                            <button type="submit" onclick="addEmployee()" id="add-new-employee" class="transition duration-300 ease-in-out rounded text-white bg-blue-800 hover:bg-blue-600 px-3 py-2">
                                Submit
                            </button>
                        </form>
                    </div>
                </details>
                <div class="p-5 w-full">
                    {{-- passing data :headers berupa array
                        passing data :datas berupa array collection --}}
                    {{-- <x-datatable :headers="$accounts" :datas="$datas"> --}}
                    <x-datatable orderIndex="0" orderType="desc" id="employee">
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
                                    var table = $('#employee').DataTable({
                                        // responsive: true,
                                        processing: true,
                                        serverSide: true,
                                        ajax: "{{ url('/employee/datatable') }}?from={{ isset($query['from']) ? $query['from'] : null }}&to={{ isset($query['to']) ? $query['to'] : null }}",
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
                                            {data: 'address', name: 'address'},
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
            function addEmployee() {
                event.preventDefault();
                let first_name = document.getElementById('add-firstname').value
                let last_name = document.getElementById('add-lastname').value
                let email = document.getElementById('add-email').value
                let username = document.getElementById('add-username').value
                let phone_number = document.getElementById('add-phone').value
                let address = document.getElementById('add-address').value
                let password = document.getElementById('add-password').value
                let password_confirmation = document.getElementById('add-password-confirmation').value

                axios.post(`/employee`, {
                    "first_name" : first_name,
                    "last_name" : last_name,
                    "email" : email,
                    "username" : username,
                    "phone_number" : phone_number,
                    "address" : address,
                    "password" : password,
                    "password_confirmation" : password_confirmation,
                })
                .then(({data}) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil menambah data employee!'
                    });
                    $('#employee').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Gagal menambahkan data employee!',
                        icon: 'error'
                    });
                })
            }

            function editEmployee(id) {
                let first_name = document.getElementById('change-firstname')
                let last_name = document.getElementById('change-lastname')
                // let email = document.getElementById('change-email')
                // let username = document.getElementById('change-username')
                let phone_number = document.getElementById('change-phone')
                let address = document.getElementById('change-address')

                let employee_id = document.getElementById('id-employee')
                let employee_profile_id = document.getElementById('id-employee-profile')
                let canvas = document.querySelector('#canvas')
                let modal = document.getElementById('modal-edit-employee')

                axios.get(`/employee/${id}`)
                    .then(({data}) => {
                        first_name.value = data.profile.first_name
                        last_name.value = data.profile.last_name
                        // email.value = data.email
                        // username.value = data.profile.username
                        phone_number.value = data.profile.phone_number
                        address.value = data.profile.address
                        employee_id.value = data.id
                        employee_profile_id.value = data.profile.id

                        canvas.scrollTop = 0;
                    })
                    .then(() => {
                        modal.classList.remove('hidden')
                        canvas.classList.remove('overflow-auto')
                        canvas.classList.add('overflow-hidden')
                    })
                    .catch(err => {
                        Swal.fire({title: 'Tidak dapat menemukan data employee!', icon: 'error'});
                        console.log(err);
                        canvas.classList.add('overflow-auto')
                        canvas.classList.remove('overflow-hidden')
                    })
            }

            function updateEmployee() {
                let first_name = document.getElementById('change-firstname').value
                let last_name = document.getElementById('change-lastname').value
                // let email = document.getElementById('change-email').value
                // let username = document.getElementById('change-username').value
                let phone_number = document.getElementById('change-phone').value
                let address = document.getElementById('change-address').value

                let employee_id = document.getElementById('id-employee').value
                let employee_profile_id = document.getElementById('id-employee-profile').value
                let modal = document.getElementById('modal-edit-employee')

                axios.post(`/employee/update/${employee_profile_id}`, {
                    "first_name" : first_name,
                    "last_name" : last_name,
                    // "email" : email,
                    // "username" : username,
                    "phone_number" : phone_number,
                    "address" : address,
                })
                .then(({data}) => {
                    Swal.fire({title: 'Berhasil mengubah data employee!', icon: 'success'});
                    $('#employee').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    modal.classList.add('hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({title: 'Tidak dapat memperbaharui data employee!', icon: 'error'});
                    modal.classList.add('hidden')
                    console.log(err);
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                })
            }

            function closeModalEdit() {
                let first_name = document.getElementById('change-firstname')
                let last_name = document.getElementById('change-lastname')
                // let email = document.getElementById('change-email')
                // let username = document.getElementById('change-username')
                let phone_number = document.getElementById('change-phone')
                let address = document.getElementById('change-address')

                let employee_id = document.getElementById('id-employee')
                let modal = document.getElementById('modal-edit-employee')

                employee_id.value = null
                first_name.value = null
                last_name.value = null
                // email.value = null
                // username.value = null
                phone_number.value = null
                address.value = null

                modal.classList.add('hidden')
                document.querySelector('#canvas').classList.add('overflow-auto')
                document.querySelector('#canvas').classList.remove('overflow-hidden')
            }

            function deleteEmployee(id) {
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
                        axios.delete(`/employee/${id}/delete`)
                        .then( ({data}) => {
                            Swal.fire({title: 'Berhasil menghapus Employee!', icon: 'success'});
                            $('#employee').DataTable().ajax.reload();
                            // window.location.reload();
                        })
                        .catch( err => {
                            Swal.fire({title: 'Employee gagal dihapus!', icon: 'error'});
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
