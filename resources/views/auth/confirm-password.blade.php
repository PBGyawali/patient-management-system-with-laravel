<x-guest-layout>
    <x-auth-card>
        {{-- <x-slot name="logo">
            <a href="/">

            </a>
        </x-slot>--}}

        <div class="mb-4 text-sm text-gray-800">
            {{ __('This is a secure area of the application. Please confirm your password
            before continuing forward.') }}
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="text"
                                name="password"
                                required autocomplete="current-password" />
            </div>
            <div>
                <x-label for="secret_password" class="mt-5 " :value="__('Secret Password')" />

                <x-input id="secret_password" class="block mt-1 w-full"
                                type="text"
                                name="secret_password"
                                required autocomplete="current-password" />

                <span class="text-red-600 pb-4">{{$errors->first('secret_password')}}</span>
            </div>

            <div class="flex justify-end mt-4">
                <x-button>
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
