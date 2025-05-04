@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl mb-4 font-medium">User Create</h2>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div>
            <div class=" flex">
                <x-label for="name" value="{{ __('Name') }}" /><span class=" text-red-500 font-semibold text-sm">*</span>
            </div>
            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" autocomplete="email" />
            <p class=" mt-1 text-sm text-slate-600">Email or Phone is mandatory<span class=" text-red-500 font-semibold text-sm">*</span></p>
            @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4">
            <x-label for="phone" value="{{ __('Phone') }}" />
            <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" autocomplete="phone" pattern="[6-9]{1}[0-9]{9}" minlength="10" maxlength="10" />
            <p class=" mt-1 text-sm text-slate-600">Phone or Email is mandatory<span class=" text-red-500 font-semibold text-sm">*</span></p>
        </div>

        <div class="mt-4">
            <div class=" flex">
                <x-label for="password" value="{{ __('Password') }}" /><span class=" text-red-500 font-semibold text-sm">*</span>
            </div>
            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
        </div>

        <div class="mt-4">
            <div class=" flex">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" /><span class=" text-red-500 font-semibold text-sm">*</span>
            </div>
            <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
            <x-label for="terms">
                <div class="flex items-center">
                    <x-checkbox name="terms" id="terms" required />

                    <div class="ms-2">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </div>
                </div>
            </x-label>
        </div>
    @endif

        <div class="flex items-center justify-end mt-4">
            <x-button class="ms-4">
                {{ __('Create') }}
            </x-button>
        </div>
    </form>
</div>
@endsection