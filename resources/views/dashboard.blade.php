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
                    <h3 class="text-lg font-semibold mb-4">Link Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h4 class="font-bold text-xl">{{ $totalLinks }}</h4>
                            <p class="text-sm">Total Links</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h4 class="font-bold text-xl">{{ $linksWithRedirect }}</h4>
                            <p class="text-sm">Links with Redirect URL</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <h4 class="font-bold text-xl">{{ $linksWithoutRedirect }}</h4>
                            <p class="text-sm">Links without Redirect URL</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <h4 class="font-bold text-xl">{{ $recentlyCreated }}</h4>
                            <p class="text-sm">Links Created (Last 7 Days)</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Recent Links</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Redirect URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($recentLinks as $link)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $link->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $link->redirect_url ?? 'Not set' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $link->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>