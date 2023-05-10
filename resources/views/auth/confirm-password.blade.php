@include('config')
<x-guest-layout>
    <x-auth-card>
        @isset($logo)
        <x-slot name="logo">
            <img src="{{IMAGES_URL.$logo}}" class="rounded-full  cursor-pointer w-40"alt="" >
        </x-slot>
        @endisset      
     

        <div class="mb-4 text-sm text-red-600">
            {{ __('message.secret') }}
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>
            <div>
                <x-label for="secret_password" class="mt-5 " :value="__('Secret Password')" />

                <x-input id="secret_password" class="block mt-1 w-full"
                                type="password"
                                name="secret_password"
                                required autocomplete="current-password" />

                <span class="text-red-600 pb-4">{{$errors->first('secret_password')}}</span>
            </div>

            <div class="flex justify-between mt-6">
                <a href="{{url()->previous()}}" class="btn btn-link inline-flex items-center px-4 py-2
                     bg-blue-700 rounded-md text-white uppercase hover:bg-purple-800  ">
                    {{ __('Return') }}
                </a>

                <x-button>
                    {{ __('Forward') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
