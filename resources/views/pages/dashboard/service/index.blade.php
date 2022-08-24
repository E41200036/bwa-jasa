@extends('layouts.app')

@section('title', 'Service')

@section('content')

    @if ($services->count())
        <main class="h-full overflow-y-auto">

            <div class="container mx-auto">
                <div class="grid w-full gap-5 px-10 mx-auto md:grid-cols-12">
                    <div class="col-span-8">
                        <h2 class="mt-8 mb-1 text-2xl font-semibold text-gray-700">
                            My Services
                        </h2>
                        <p class="text-sm text-gray-400">
                            {{ auth()->user()->service->count() }} Total Services
                        </p>
                    </div>
                    <div class="col-span-4 lg:text-right">
                        <div class="relative mt-0 md:mt-6">
                            <a href="{{ route('member.service.create') }}"
                                class="inline-block px-4 py-2 mt-2 text-left text-white rounded-xl bg-serv-button">
                                + Add Service
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="container px-6 mx-auto mt-5">
                <div class="grid gap-5 md:grid-cols-12">
                    <main class="col-span-12 p-4 md:pt-0">
                        <div class="px-6 py-2 mt-2 bg-white rounded-xl">
                            <table class="w-full" aria-label="Table">
                                <thead>
                                    <tr class="text-sm font-normal text-left text-gray-900 border-b border-b-gray-600">
                                        <th class="py-4" scope="">Service Details</th>
                                        <th class="py-4" scope="">Role</th>
                                        <th class="py-4" scope="">Price</th>
                                        <th class="py-4" scope="">Status</th>
                                        <th class="py-4" scope="">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($services as $service)
                                        <tr class="text-gray-700 border-b">
                                            <td class="w-2/6 px-1 py-5">
                                                <div class="flex items-center text-sm">
                                                    <div class="relative w-10 h-10 mr-3 rounded-full md:block">

                                                        @if ($service->thumbnailService->count())
                                                            <img class="object-cover w-full h-full rounded"
                                                                src="{{ asset($service->thumbnailService[0]->thumbnail) }}"
                                                                alt="" loading="lazy" />
                                                        @else
                                                            <img class="object-cover w-full h-full rounded"
                                                                src="{{ url('https://pertaniansehat.com/v01/wp-content/uploads/2015/08/default-placeholder.png') }}"
                                                                alt="" loading="lazy" />
                                                        @endif

                                                        <div class="absolute inset-0 rounded-full shadow-inner"
                                                            aria-hidden="true">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('member.service.show', 1) }}"
                                                            class="font-medium text-black">
                                                            {{ $service->title }} <br>{{ $service->description }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-1 py-5 text-sm">
                                                {{ Auth::user()->detailUser->role }}
                                            </td>
                                            <td class="px-1 py-5 text-sm">
                                                Rp. {{ number_format($service->price) }}
                                            </td>
                                            <td class="px-1 py-5 text-sm text-green-500 text-md">
                                                Active
                                            </td>
                                            <td class="px-1 py-5 text-sm">
                                                <a href="{{ route('member.service.edit', $service) }}"
                                                    class="px-4 py-2 mt-2 text-left text-white rounded-xl bg-serv-email">
                                                    Edit Service
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </main>
                </div>
            </section>

        </main>
    @else
        <div class="flex h-screen">
            <div class="m-auto text-center">
                <img src="{{ asset('assets/images/empty-illustration.svg') }}" alt="" class="w-48 mx-auto">
                <h2 class="mt-8 mb-1 text-2xl font-semibold text-gray-700">
                    There is No Requests Yet
                </h2>
                <p class="text-sm text-gray-400">
                    It seems that you haven’t provided any service. <br>
                    Let’s create your first service!
                </p>

                <div class="relative mt-0 md:mt-6">
                    <a href="{{ route('member.service.create') }}"
                        class="px-4 py-2 mt-2 text-left text-white rounded-xl bg-serv-button">
                        + Add Services
                    </a>
                </div>
            </div>
        </div>
    @endif


@endsection
