<x-guest-layout>
    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-4 items-center w-screen bg-gray-100 bg-texture bg-cover bg-center bg-blend-multiply">
        <div class="text-center hidden md:block">
            {{-- <x-icon.secure class="h-60 w-60 mx-auto hidden md:block" /> --}}
            <h2 class="uppercase filter transform -rotate-6 text-gray-400 rounded-lg drop-shadow-2xl font-serif font-black text-7xl mix-blend-multiply">JOSEPH'S POINT OF SALE</h2>
        </div>
        <x-auth-card>
            <x-slot name="logo">
                <a href="/" class="mx-auto">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    {{-- <img src="{{ asset('/images/tabung kebaikan.webp') }}" alt="Logo tabung kebaikan" class="mb-5" loading="lazy"> --}}
                </a>
            </x-slot>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <!-- Validation Errors -->
            <x-auth-validation-errors class="flex mb-4" :errors="$errors" />
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div>
                    <x-label for="email" :value="__('Email')" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autofocus />
                </div>
                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" :value="__('Password')" />
                    <x-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    autocomplete="current-password" />
                </div>
                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>
                <div x-data="{loading: false}" class="flex items-center justify-between mt-4">
                    <x-button x-on:click="loading = true" x-bind:disabled="loading" class="flex items-center text-white hover:bg-blue-900" x-bind:class="loading === true ? 'bg-blue-400 cursor-wait' : 'bg-blue-800'">
                        <x-icon.loading class="h-5 w-5 mr-2" x-show="loading" style="display: none;" />
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </form>
        </x-auth-card>
    </div>

    <x-slot name="script">
        <script>
            // var token = localStorage.getItem("posToken");
            // window.addEventListener('load', async function () {
            //     console.log(this.token);

            //     try {
            //         await getUser()
            //         // window.location.replace('/dashboard')
            //     } catch (error) {

            //     }
            // })

            // function login() {
            //     event.preventDefault();
            //     var loginBtn = document.getElementById('login-btn');
            //     loginBtn.disabled = true;

            //     var email = document.getElementById('email').value;
            //     var password = document.getElementById('password').value;

            //     axios.post('api/login', {
            //         email,
            //         password
            //     })
            //     .then(({data}) => {
            //         var newToken = data.data.token;
            //         localStorage.setItem("posToken", newToken);
            //         location.replace("/dashboard");
            //     }).catch(err => {
            //         loginBtn.disabled = false;
            //         console.error(err)
            //     })
            // }

            // async function getUser() {
            //     await axios.get('api/user', {
            //         headers: {
            //             'Authorization': `Bearer ${this.token}`
            //         }
            //     })
            //     .then(({data}) => {
            //         var newToken = data.data.token;
            //         var user = data.user;
            //         console.log(newToken);
            //         localStorage.setItem("posToken", newToken);
            //         localStorage.setItem("user", JSON.stringify(user));
            //         location.replace("/dashboard");
            //     }).catch(err => console.error(err))
            // }

        </script>
    </x-slot>
</x-guest-layout>
