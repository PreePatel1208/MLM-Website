<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                 <!-- display MLM tree -->
                <div class="tree">
                    <ul>
                        <li>
                            <a href="#">
                                Me
                            </a>
                            <ul>
                                @foreach($mlm_users as $key=>$user)
                                <li>
                                    <a href="#">{{$user['name']}}</a>  
                                    <ul>
                                        <ul>
                                        @foreach($user['tree_user'] as $key=>$user)
                                            <li>
                                                <a href="#">{{$user['name']}}</a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- display Earnings -->
                @include('main_dashbord')
                <!-- <x-jet-welcome /> -->
            </div>
        </div>
    </div>
</x-app-layout>