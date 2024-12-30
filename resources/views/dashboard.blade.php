<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            @can('view content')
            <div class="p-6 text-gray-900">
                    {{ __("You are Staff") }}
            </div>
            @endcan
            @can('customize user data')
            <div class="p-6 text-gray-900">
                    {{ __("You are an admin!") }}
            </div>
            @endcan
            @can('super-admins')
            <div class="p-6 text-gray-900">
                    {{ __("You are a super-admin!") }}
            </div>
            @endcan
        </div>
    </div>
</x-app-layout>
