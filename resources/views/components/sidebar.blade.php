<section class="px-5 bg-white toggle-sidebar h-screen">
    <div class="flex items-center text-center justify-center h-16 border-b">
        <a href="{{ route('welcome') }}">
            {{-- <img src="{{ asset('/images/tabung kebaikan.webp') }}" alt="Logo tabung kebaikan" class="my-5 w-auto h-8" loading="lazy"> --}}
            POS-JOSEPH
        </a>
    </div>

    <ul class="py-5">

        @hasanyrole('Admin|Company|Employee') {{-- Dashboard menampilkan seluruh data donasi berdasarkan affiliate Id --}}
        <li class="px-0 py-2">
            {{-- <x-nav-link :href="route('welcome')" :active="request()->routeIs('dashboard.fundraiser')"> --}}
            <x-nav-link :href="route('welcome')" :active="request()->routeIs('dashboard')">
                <x-icon.frame class="bg-gradient-to-br from-blue-400 to-red-500 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </x-icon.frame>
                {{ __('Dashboard') }}
            </x-nav-link>
        </li>
        @endhasanyrole

        @can('manage-company') {{-- Menampilkan data donasi berdasarkan affiliate Id dengan filter by periodik --}}
        <li class="px-0 py-2">
            <x-nav-link :href="route('company')" :active="request()->routeIs('company')">
                <x-icon.frame class="bg-gradient-to-br from-green-400 to-green-600 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" stroke="none" fill="currentColor" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/>
                        <path d="M3 19V5.7a1 1 0 0 1 .658-.94l9.671-3.516a.5.5 0 0 1 .671.47v4.953l6.316 2.105a1 1 0 0 1 .684.949V19h2v2H1v-2h2zm2 0h7V3.855L5 6.401V19zm14 0v-8.558l-5-1.667V19h5z"/>
                    </svg>
                </x-icon.frame>
                {{ __('Company') }}
            </x-nav-link>
        </li>
        @endcan

        @can('manage-employee') {{-- Dashboard menampilkan seluruh data donasi --}}
        <li class="px-0 py-2">
            <x-nav-link :href="route('employee')" :active="request()->routeIs('employee')">
                <x-icon.frame class="bg-gradient-to-br from-red-200 to-red-500 mr-3">
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </x-icon.frame>
                {{ __('Employee') }}
            </x-nav-link>
        </li>
        @endcan

        @can('manage-category')
        <li class="px-0 py-2">
            <x-nav-link :href="route('category')" :active="request()->routeIs('category')">
                <x-icon.frame class="bg-gradient-to-br from-purple-400 to-purple-800 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </x-icon.frame>
                {{ __('Category') }}
            </x-nav-link>
        </li>
        @endcan

        @can('manage-product')
        <li class="px-0 py-2">
            <x-nav-link :href="route('product')" :active="request()->routeIs('product')">
                <x-icon.frame class="bg-gradient-to-br from-red-400 to-purple-800 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </x-icon.frame>
                {{ __('Produk') }}
            </x-nav-link>
        </li>
        @endcan

        @can('manage-customer')
        <li class="px-0 py-2">
            <x-nav-link :href="route('customer')" :active="request()->routeIs('customer')">
                <x-icon.frame class="bg-gradient-to-br from-green-400 to-purple-800 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-icon.frame>
                {{ __('Customer') }}
            </x-nav-link>
        </li>
        @endcan

        @hasanyrole('Company|Employee')
        <li class="px-0 py-2">
            <x-nav-link :href="route('transaction')" :active="request()->routeIs('transaction')">
                <x-icon.frame class="bg-gradient-to-br from-green-400 to-green-600 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </x-icon.frame>
                {{ __('Transaction') }}
            </x-nav-link>
        </li>
        @endhasanyrole




        {{-- @hasanyrole('Customer|Employee')
        <li class="px-0 py-2">
            <x-nav-link :href="route('welcome')" :active="request()->routeIs('dashboard')">
                <x-icon.frame class="bg-gradient-to-br from-yellow-400 to-red-600 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </x-icon.frame>
                {{ __('Invoice') }}
            </x-nav-link>
        </li>
        @endhasanyrole --}}

        {{-- @can('manage-company')
        <li class="px-0 py-2">
            <x-nav-link :href="route('welcome')" :active="request()->routeIs('notification')">
                <x-icon.frame class="bg-gradient-to-br from-red-400 to-purple-800 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </x-icon.frame>
                {{ __('Notifikasi') }}
            </x-nav-link>
        </li>
        @endcan

        @can('manage-company')
        <li class="px-0 py-2">
            <x-nav-link :href="route('welcome')" :active="request()->routeIs('faqs')">
                <x-icon.frame class="bg-gradient-to-br from-yellow-400 to-yellow-600 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-icon.frame>
                {{ __('FAQS') }}
            </x-nav-link>
        </li>
        @endcan

        @can('manage-company')
        <li class="px-0 py-2">
            <x-nav-link :href="route('welcome')" :active="request()->routeIs('role-permission')">
                <x-icon.frame class="bg-gradient-to-br from-green-600 to-purple-800 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </x-icon.frame>
                {{ __('Role permission') }}
            </x-nav-link>
        </li>
        @endcan

        @can('manage-company')
        <li class="px-0 py-2">
            <x-nav-link :href="route('welcome')" :active="request()->routeIs('akun-divisi')">
                <x-icon.frame class="bg-gradient-to-br from-blue-600 to-purple-800 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </x-icon.frame>
                {{ __('Akun Divisi') }}
            </x-nav-link>
        </li>
        @endcan --}}

    </ul>
</section>
