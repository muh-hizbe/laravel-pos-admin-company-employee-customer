<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-10">
    <!-- Primary Navigation Menu -->
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-6">
        <div class="flex justify-between h-16">
            <div class="flex overflow-x-auto">
                <div class="flex-shrink-0 flex items-center">
                    {{-- Showing date and time locally --}}
                    <span class="date text-blue-800 px-2 rounded border-2 border-blue-800"></span>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 cursor-pointer" onclick="openFullscreen()" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z"/><path d="M20 3h2v6h-2V5h-4V3h4zM4 3h4v2H4v4H2V3h2zm16 16v-4h2v6h-6v-2h4zM4 19h4v2H2v-6h2v4z"/>
                </svg> --}}

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->profile->username }}</div>

                            <div class="ml-1">
                                <svg class="transition duration-200 ease-in-out transform fill-current h-4 w-4" :class="{'rotate-180': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- SHOW PROFILE --}}
                        {{-- <x-dropdown-link href="{{ route('profile') }}"> --}}
                        {{-- <x-dropdown-link href="/">
                            <div class="w-full cursor-pointer">Profile</div>
                        </x-dropdown-link> --}}
                        <x-dropdown-link class="cursor-default">
                            <div class="w-full cursor-default whitespace-nowrap">{{ Auth::user()->profile->fullName }}</div>
                        </x-dropdown-link>

                        @hasanyrole('Employee')
                        <x-dropdown-link class="cursor-default">
                            <details class="w-full">
                                <summary class="w-full cursor-pointer">Company</summary>
                                <div class="w-full cursor-default whitespace-nowrap">🏬 {{ Auth::user()->profile->company->profile->fullName }}</div>
                            </details>
                        </x-dropdown-link>
                        @endhasanyrole

                        {{-- SHOW ROLE --}}
                        <x-dropdown-link>
                            <details class="w-full">
                                <summary class="w-full cursor-pointer">Roles</summary>
                                <ul>
                                    @foreach (Auth::user()->getRoleNames() as $role)
                                        <li class="py-2 w-full whitespace-nowrap">
                                            🔐 {{ $role }}
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        </x-dropdown-link>

                        {{-- SHOW EMAIL --}}
                        <x-dropdown-link>
                            <details class="w-full">
                                <summary class="w-full cursor-pointer">Email</summary>
                                <ul>
                                    <li class="py-2 w-full whitespace-nowrap">
                                        📧 {{ Auth::user()->email }}
                                    </li>
                                </ul>
                            </details>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" class="rounded-md">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    class="whitespace-nowrap"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <x-icon.frame class="bg-gradient-to-br from-red-400 to-red-700 mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24" stroke="none" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M5 11h8v2H5v3l-5-4 5-4v3zm-1 7h2.708a8 8 0 1 0 0-12H4A9.985 9.985 0 0 1 12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10a9.985 9.985 0 0 1-8-4z"/></svg>
                                </x-icon.frame>
                                {{ __('Log out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button x-on:click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md bg-white text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden">

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->profile->username }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- SHOW PROFILE --}}
                {{-- <x-dropdown-link href="{{ route('profile') }}"> --}}
                <x-dropdown-link class="cursor-default">
                    <div class="w-full cursor-default whitespace-nowrap">{{ Auth::user()->profile->fullName }}</div>
                </x-dropdown-link>

                @hasanyrole('Employee')
                <x-dropdown-link class="cursor-default">
                    <details class="w-full">
                        <summary class="w-full cursor-pointer">Company</summary>
                        <div class="w-full cursor-default whitespace-nowrap">🏬 {{ Auth::user()->profile->company->profile->fullName }}</div>
                    </details>
                </x-dropdown-link>
                @endhasanyrole

                <x-dropdown-link>
                    <details class="w-full">
                        <summary class="w-full cursor-pointer">Roles</summary>
                        <ul>
                            @foreach (Auth::user()->getRoleNames() as $role)
                                <li class="py-2">
                                    🔐 {{ $role }}
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </x-dropdown-link>
            </div>
            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            class="whitespace-nowrap flex items-center"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <x-icon.frame class="bg-gradient-to-br from-red-400 to-red-700 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24" stroke="none" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M5 11h8v2H5v3l-5-4 5-4v3zm-1 7h2.708a8 8 0 1 0 0-12H4A9.985 9.985 0 0 1 12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10a9.985 9.985 0 0 1-8-4z"/></svg>
                        </x-icon.frame>
                        {{ __('Log out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

@push('script')
    <script>
        var time = document.querySelector('.date');
        window.setInterval(function() {
            time.textContent = new Date().toLocaleDateString() + ' | ' + new Date().toLocaleTimeString();
        })
    </script>
@endpush
