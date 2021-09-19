<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-gray-800">
            {{ __('Dashboard') }} | Global Data
        </h2>
    </x-slot>

    <div x-data="dashboardHandle()" x-init="initialDashboard()" class="py-12 px-3 md:px-0">
        <div class="w-full mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            {{-- CARD --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5 overflow-x-auto">

                @can('manage-company')
                <x-card title="Total Company">
                    <x-slot name="icon">
                        <x-icon.frame class="bg-gradient-to-br from-green-300 to-green-500 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" stroke="none" fill="currentColor" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/>
                                <path d="M3 19V5.7a1 1 0 0 1 .658-.94l9.671-3.516a.5.5 0 0 1 .671.47v4.953l6.316 2.105a1 1 0 0 1 .684.949V19h2v2H1v-2h2zm2 0h7V3.855L5 6.401V19zm14 0v-8.558l-5-1.667V19h5z"/>
                            </svg>
                        </x-icon.frame>
                    </x-slot>
                    <span x-text="state.totalCompany"></span>
                </x-card>
                @endcan

                @hasanyrole('Admin|Company')
                <x-card title="Total Employee">
                    <x-slot name="icon">
                        <x-icon.frame class="bg-gradient-to-br from-yellow-200 to-yellow-500 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </x-icon.frame>
                    </x-slot>
                    <span x-text="state.totalEmployee"></span>
                </x-card>
                @endhasanyrole

                @hasanyrole('Admin|Company|Employee')
                <x-card title="Total Customer">
                    <x-slot name="icon">
                        <x-icon.frame class="bg-gradient-to-br from-yellow-500 to-blue-500 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </x-icon.frame>
                    </x-slot>
                    <span x-text="state.totalCustomer"></span>
                </x-card>
                <x-card title="Total Category">
                    <x-slot name="icon">
                        <x-icon.frame class="bg-gradient-to-br from-green-500 to-blue-500 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </x-icon.frame>
                    </x-slot>
                    <span x-text="state.totalCategory"></span>
                </x-card>
                <x-card title="Total Product">
                    <x-slot name="icon">
                        <x-icon.frame class="bg-gradient-to-br from-purple-500 to-blue-500 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </x-icon.frame>
                    </x-slot>
                    <span x-text="state.totalProduct"></span>
                </x-card>
                <x-card title="Total Transaction">
                    <x-slot name="icon">
                        <x-icon.frame class="bg-gradient-to-br from-red-400 to-blue-500 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </x-icon.frame>
                    </x-slot>
                    <span x-text="state.totalTransaction"></span>
                </x-card>
                {{-- <x-card title="Total Invoice">
                    <x-slot name="icon">
                        <x-icon.frame class="bg-gradient-to-br from-blue-200 to-blue-500 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24" stroke="none" fill="currentColor" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm1 2v14h16V5H4zm4.5 9H14a.5.5 0 1 0 0-1h-4a2.5 2.5 0 1 1 0-5h1V6h2v2h2.5v2H10a.5.5 0 1 0 0 1h4a2.5 2.5 0 1 1 0 5h-1v2h-2v-2H8.5v-2z"/></svg>
                        </x-icon.frame>
                    </x-slot>
                    <span x-text="money(state.totalInvoice)"></span>
                </x-card> --}}
                @endhasanyrole
            </div>
        </div>
    </div>

    <x-slot name="style">
        <style>
            input[type=number]:valid {
                background: rgb(24, 231, 162);
            }
        </style>
    </x-slot>

    <x-slot name="script">
        <script>
            function dashboardHandle() {
                return {
                    state: {
                        // email: {{ auth()->user()->email }},
                        // for card data
                        totalCompany: 0,
                        totalEmployee: 0,
                        totalCustomer: 0,
                        totalCategory: 0,
                        totalProduct: 0,
                        totalTransaction: 0,
                    },
                    initialDashboard() {
                        this.getTotalCustomer();
                        this.getTotalCategory();

                        this.getTotalCompany();
                        this.getTotalEmployee();
                        this.getTotalProduct();
                        this.getTotalTransaction();
                    },
                    getTotalCompany() {
                        axios.get('/company/count')
                        .then(({data}) => {
                            this.state.totalCompany = data;
                        }).catch(err => console.error(err))
                    },
                    getTotalEmployee() {
                        axios.get('/employee/count')
                        .then(({data}) => {
                            this.state.totalEmployee = data;
                        }).catch(err => console.error(err))
                    },
                    getTotalCustomer() {
                        axios.get('/customer/count')
                        .then(({data}) => {
                            this.state.totalCustomer = data;
                        }).catch(err => console.error(err))
                    },
                    getTotalCategory() {
                        axios.get('/category/count')
                        .then(({data}) => {
                            this.state.totalCategory = data;
                        }).catch(err => console.error(err))
                    },
                    getTotalTransaction() {
                        axios.get('/transaction/count')
                        .then(({data}) => {
                            this.state.totalTransaction = data;
                        }).catch(err => console.error(err))
                    },
                    getTotalProduct() {
                        axios.get('/product/count', {
                            params: {
                                email: this.state.email
                            }
                        })
                        .then(({data}) => {
                            this.state.totalProduct = data;
                        }).catch(err => console.error(err))
                    },
                    money(number) {
                        return new Intl.NumberFormat('id-ID', {style: 'currency',currency: 'IDR', minimumFractionDigits: 2}).format(number)
                    }
                }
            }
        </script>
    </x-slot>
</x-app-layout>
