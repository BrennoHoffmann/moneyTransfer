<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (!empty($user->wallet->money))
                        Você tem: 
                        @if ($user->wallet->money >= 50)
                            <strong> R$ {{ $user->wallet->money }} </strong>
                        @endif
                    @else
                        Você ainda não possui saldo. Deseja depositar dinheiro?
                    @endif
                    <br>
                    @foreach($users as $user)
                        @if ($user->user_type == 1)
                            {{$user->name}}
                        @else
                            <strong>{{$user->user_type}}</strong> 
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
