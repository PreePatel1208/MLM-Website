<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @include('sidebar')
                @include('main_dashbord')
                <div class="tree">
                    <ul>
                        <li>
                            <a href="#">1</a>
                            <ul>
                                <li>
                                    <a href="#">2</a>
                                    <ul>
                                        <li>
                                            <a href="#">2.1</a>

                                        </li>
                                        <li>
                                            <a href="#">2.2</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">3</a>
                                    <ul>
                                        <ul>
                                            <li>
                                                <a href="#">3.1</a>
                                                <ul>
                                                    <li>
                                                        <a href="#">3.1.1</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">3.1.2</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">3.2</a>
                                            </li>
                                        </ul>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- <x-jet-welcome /> -->
            </div>
        </div>
    </div>
</x-app-layout>