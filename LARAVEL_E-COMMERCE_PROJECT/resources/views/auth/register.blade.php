<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>
            
            <div class="mt-4">
                <x-label for="role" value="{{ __('I want to register as:') }}" />

                <div class="flex items-center mt-2">
                    <label for="role_customer" class="inline-flex items-center mr-6">
                        <x-input id="role_customer" type="radio" name="role" value="customer" :checked="old('role', 'customer') == 'customer'" required />
                        <span class="ml-2 text-sm text-gray-600">{{ __('Customer') }}</span>
                    </label>

                    <label for="role_seller" class="inline-flex items-center">
                        <x-input id="role_seller" type="radio" name="role" value="seller" :checked="old('role') == 'seller'" required />
                        <span class="ml-2 text-sm text-gray-600">{{ __('Seller') }}</span>
                    </label>
                </div>
                <x-input-error for="role" class="mt-2" />
            </div>

            {{-- Dapat mo itong i-hide gamit ang JS at ipakita lang kung pinili ang 'Seller' --}}
            <div class="mt-4" id="shop_name_container">
                <x-label for="shopname" value="{{ __('Shop Name (Required for Sellers)') }}" />
                {{-- Ang name ay 'shopname' --}}
                <x-input id="shopname" class="block mt-1 w-full" type="text" name="shopname" :value="old('shopname')" placeholder="Enter your shop name" />
                <x-input-error for="shopname" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<script>
    // Magdagdag ng simpleng JavaScript para i-hide/ipakita ang shopname field
    document.addEventListener('DOMContentLoaded', function () {
        const sellerRadio = document.getElementById('role_seller');
        const customerRadio = document.getElementById('role_customer');
        const shopNameContainer = document.getElementById('shop_name_container');
        const shopNameInput = document.getElementById('shopname');

        function toggleShopName() {
            if (sellerRadio.checked) {
                shopNameContainer.style.display = 'block';
                shopNameInput.setAttribute('required', 'required');
            } else {
                shopNameContainer.style.display = 'none';
                shopNameInput.removeAttribute('required');
            }
        }

        sellerRadio.addEventListener('change', toggleShopName);
        customerRadio.addEventListener('change', toggleShopName);

        // Initial check on page load (in case of validation error and old('role') is 'seller')
        toggleShopName();
    });
</script>