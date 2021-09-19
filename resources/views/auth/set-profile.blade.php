<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('set.profile') }}">
            @csrf

            <!-- Username -->
            <div class="mt-4">
                <x-label for="email" :value="__('Username')" />

                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required />
            </div>

            <!-- First Name -->
            <div class="mt-4">
                <x-label for="first_name" :value="__('First Name')" />

                <x-input id="first_name" class="block mt-1 w-full"
                                type="text"
                                name="first_name"
                                :value="old('first_name')"
                                required autocomplete="first_name" />
            </div>

            <!-- Last Name -->
            <div class="mt-4">
                <x-label for="last_name" :value="__('Last Name')" />

                <x-input id="last_name" class="block mt-1 w-full"
                                type="text"
                                name="last_name"
                                :value="old('last_name')"
                                required autocomplete="last_name" />
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <x-label for="phone_number" :value="__('Phone number')" />

                <x-input id="phone_number" class="block mt-1 w-full"
                                type="tel"
                                :value="old('phone_number')"
                                name="phone_number" required />
            </div>

            <!-- Address -->
            <div class="mt-4">
                <x-label for="address" :value="__('Address')" />

                <textarea id="address" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                name="address" required>{{ old('address') }}</textarea>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Submit') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
