<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="manifest" href="/manifest.json">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="msapplication-starturl" content="/">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="theme-color" content="#e5e5e5">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />

        @stack('style')
        @if (isset($style))
            {{ $style }}
        @endif
    </head>
    <body x-data="{ open: true }" class="font-sans antialiased flex h-screen">
        @hasanyrole('Admin|Company|Employee')
        <div class="sticky flex bg-transparent left-0 top-0">
            <div :class="{'hidden md:block': open, 'block md:hidden': !open }">
                <x-sidebar />
            </div>

            <!-- Switch sidebar to hidden -->
            <span x-on:click="open = ! open" class="flex z-30 text-center items-center justify-center cursor-pointer bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="transition duration-200 ease-in-out transform h-5 w-5 text-blue-800" :class="{'rotate-0 md:rotate-180': !open, 'rotate-180 md:rotate-0': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        </div>
        @endhasanyrole
        {{-- <div x-data="notifHandle()"><span x-on:click="notif()">notif</span></div> --}}

        {{-- @stack('modal') --}}

        <div id="canvas" class="main-page transform max-h-screen bg-gray-100 w-full overflow-auto">
            @include('layouts.navigation')
            <!-- Page Heading -->
            <header class="bg-gradient-to-r from-gray-100 via-white to-gray-100">
                <div class="w-full mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    @if (isset($header))
                        {{ $header }}
                    @endif
                </div>
            </header>

            <!-- Page Content -->
            <main id="app" class="overflow-hidden min-h-screen">
                {{ $slot }}
            </main>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"></script>

        <script>
            window.addEventListener('load', function () {
                window.toastr.options = {
                    progressBar: true,
                    showEasing: 'swing',
                    iconClasses: {
                        success: 'toast-warning !bg-blue-800'
                    }
                }
            }, false);

            function notifHandle() {
                return {
                    notif() {
                        toastr.success('Hamba Allah baru saja berdonasi sebesar Rp. 10.000', 'Donasi baru!')
                        // Swal.fire({
                        //     title: 'Data FAQS berhasil dihapus!',
                        //     icon: 'info',
                        //     toast: true,
                        //     position: 'top-end',
                        //     showConfirmButton: false,
                        //     grow: 'column',
                        //     timer: 3000,
                        //     timerProgressBar: true,
                        // });
                    }
                }
            }

            var token = document.head.querySelector('meta[name="csrf-token"]');
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            var elem = document.documentElement;
            function openFullscreen() {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) { /* Safari */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { /* IE11 */
                    elem.msRequestFullscreen();
                }
            }

            function closeFullscreen() {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) { /* Safari */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { /* IE11 */
                    document.msExitFullscreen();
                }
            }
        </script>

        @stack('script')
        @isset($script)
            {{ $script }}
        @endisset

        @include('sweetalert::alert')
    </body>
</html>
