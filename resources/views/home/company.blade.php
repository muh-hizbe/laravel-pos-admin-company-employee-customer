<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-gray-800">
            <a href="{{ route('home') }}" class="text-blue-800">/ 🏬 Companies</a>
            {{ __('/ '). $company->profile->fullName }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div id="modal-detail-product" class="hidden fixed inset-0 z-40 w-auto mx-auto h-screen sm:px-6 lg:px-8 overflow-x-auto bg-blue-700 bg-opacity-70">
            <div class="flex items-center justify-center w-full h-screen">
                <div class="bg-white w-full rounded p-5 mx-auto flex flex-col">
                    <h2 class="text-center font-semibold text-2xl mb-5">Detail Product</h2>
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
                    <button type="submit" onclick="updateEmployee()" class="bg-blue-600 text-white p-2 rounded mb-3">Pay</button>
                    <button onclick="closeModalEdit()" class="bg-red-600 text-white p-2 rounded">Close</button>
                </div>
            </div>
        </div>

        <div class="w-auto mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="overflow-hidden rounded-lg">
                <div class="p-0">
                    {{-- CARD --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-5 overflow-x-auto">

                        @hasanyrole('Customer')
                        @forelse ($company->products as $product)
                            <x-card title="{{ $product->name }}" class="font-mono">
                                @if(!empty($product->discount_price))
                                    <div class="flex items-center w-full">
                                        <p class="line-through text-xs text-red-500 mr-2">@money($product->price)</p>
                                        <p class="">@money($product->discount_price)</p>
                                    </div>
                                @else
                                    <p class="">@money($product->price)</p>
                                @endif
                                <p class="text-sm">Stock : {{ $product->stock > 0 ? $product->stock : 'Empty' }}</p>
                                <p class="font-bold mb-3">🏬 {{ $product->company->profile->fullName }}</p>
                                @if($product->stock === 0)
                                    <a href="" class="">
                                        <x-button class="bg-gray-500 text-white w-full text-center justify-center cursor-not-allowed" disabled>Empty</x-button>
                                    </a>
                                @else
                                    <span onclick="buyProduct({{ $product->id }})" class="hover:opacity-75">
                                        <x-button class="bg-blue-500 text-white w-full text-center justify-center">Buy</x-button>
                                    </span>
                                @endif
                            </x-card>
                        @empty
                            <div class="w-full">Tidak ada data</div>
                        @endforelse
                        @endhasanyrole

                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            function addCompany() {
                event.preventDefault();
                let first_name = document.getElementById('add-firstname').value
                let last_name = document.getElementById('add-lastname').value
                let email = document.getElementById('add-email').value
                let username = document.getElementById('add-username').value
                let phone_number = document.getElementById('add-phone').value
                let address = document.getElementById('add-address').value
                let password = document.getElementById('add-password').value
                let password_confirmation = document.getElementById('add-password-confirmation').value

                axios.post(`/company`, {
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
                        title: 'Berhasil menambah data company!'
                    });
                    $('#company').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Gagal menambahkan data company!',
                        icon: 'error'
                    });
                })
            }

            function buyProduct(id) {
                Swal.fire({
                    title: 'Konfirmasi pembelian',
                    text: 'Pastikan pilihan anda tepat!',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    //if user clicks on delete button
                    if (result.value) {
                        // calling destroy method to delete data
                        axios.get(`/home/product/${id}/buy`)
                        .then( ({data}) => {
                            Swal.fire({title: 'Produk telah dibeli!', text: 'Invoice dikirim ke email anda.', icon: 'success'});
                            window.location.reload();
                        })
                        .catch( err => {
                            Swal.fire({title: 'Pembelian product gagal!', icon: 'error'});
                        })
                    } else {
                        Swal.fire({
                            title: 'Dibatalkan!',
                            icon: 'success'
                        });
                    }
                });
            }

            function updateCompany() {
                let first_name = document.getElementById('change-firstname').value
                let last_name = document.getElementById('change-lastname').value
                // let email = document.getElementById('change-email').value
                // let username = document.getElementById('change-username').value
                let phone_number = document.getElementById('change-phone').value
                let address = document.getElementById('change-address').value

                let company_id = document.getElementById('id-company').value
                let company_profile_id = document.getElementById('id-company-profile').value
                let modal = document.getElementById('modal-edit-company')

                axios.post(`/company/update/${company_profile_id}`, {
                    "first_name" : first_name,
                    "last_name" : last_name,
                    // "email" : email,
                    // "username" : username,
                    "phone_number" : phone_number,
                    "address" : address,
                })
                .then(({data}) => {
                    Swal.fire({title: 'Berhasil mengubah data company!', icon: 'success'});
                    $('#company').DataTable().ajax.reload();
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                    modal.classList.add('hidden')
                    // window.location.reload();
                })
                .catch(err => {
                    Swal.fire({title: 'Tidak dapat memperbaharui data company!', icon: 'error'});
                    modal.classList.add('hidden')
                    console.log(err);
                    document.querySelector('#canvas').classList.add('overflow-auto')
                    document.querySelector('#canvas').classList.remove('overflow-hidden')
                })
            }

            function closeModalEdit() {
                // let first_name = document.getElementById('change-firstname')
                // let last_name = document.getElementById('change-lastname')
                // // let email = document.getElementById('change-email')
                // // let username = document.getElementById('change-username')
                // let phone_number = document.getElementById('change-phone')
                // let address = document.getElementById('change-address')

                // let company_id = document.getElementById('id-company')
                let modal = document.getElementById('modal-detail-product')

                // company_id.value = null
                // first_name.value = null
                // last_name.value = null
                // // email.value = null
                // // username.value = null
                // phone_number.value = null
                // address.value = null

                modal.classList.add('hidden')
                document.querySelector('#canvas').classList.add('overflow-auto')
                document.querySelector('#canvas').classList.remove('overflow-hidden')
            }

            function deleteCompany(id) {
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
                        axios.delete(`/company/${id}/delete`)
                        .then( ({data}) => {
                            Swal.fire({title: 'Berhasil menghapus Company!', icon: 'success'});
                            $('#company').DataTable().ajax.reload();
                            // window.location.reload();
                        })
                        .catch( err => {
                            Swal.fire({title: 'Company gagal dihapus!', icon: 'error'});
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
