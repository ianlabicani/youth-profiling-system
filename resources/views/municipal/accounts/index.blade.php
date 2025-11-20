@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Barangay Accounts</h1>
            <p class="text-gray-600 mt-1">Manage barangay admin accounts for Camalaniugan</p>
        </div>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('municipal.accounts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Create New Account
            </a>
            <div class="flex gap-2">
                <button type="button" onclick="exportData('accounts', 'pdf')" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
                <button type="button" onclick="exportData('accounts', 'excel')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($message = session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-start">
                <i class="fas fa-check-circle mr-3 mt-0.5"></i>
                <div>
                    <p class="font-medium">Success</p>
                    <p class="text-sm">{{ $message }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mr-3 mt-0.5"></i>
                <div>
                    <p class="font-medium">Error</p>
                    <ul class="text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Accounts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if ($accounts->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Barangay</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($accounts as $account)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-700 font-semibold">{{ substr($account->name, 0, 1) }}</span>
                                        </div>
                                        <span class="ml-3 text-gray-800 font-medium">{{ $account->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $account->email }}</td>
                                <td class="px-6 py-4">
                                    @if ($account->barangays->first())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-map-pin mr-1"></i>{{ $account->barangays->first()->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $account->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('municipal.accounts.edit', $account) }}" class="inline-flex items-center px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form action="{{ route('municipal.accounts.destroy', $account) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" onclick="return confirm('Are you sure you want to delete this account?')">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white border-t px-6 py-4">
                {{ $accounts->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">No barangay accounts yet</p>
                <p class="text-gray-400 mt-2">Create your first barangay admin account to get started</p>
                <a href="{{ route('municipal.accounts.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Create First Account
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success messages after 5 seconds
        const successAlert = document.querySelector('[role="alert"]');
        if (successAlert && successAlert.classList.contains('bg-green-100')) {
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 5000);
        }
    });

    function exportData(dataType, format) {
        const queryParams = new URLSearchParams(window.location.search);
        const params = new URLSearchParams();

        // Add all filter parameters
        for (let [key, value] of queryParams) {
            params.append(key, value);
        }

        params.append('format', format);

        const routeMap = {
            'accounts': '{{ route('municipal.accounts.export', [], false) }}',
            'barangays': '{{ route('municipal.barangays.export', [], false) }}',
            'organizations': '{{ route('municipal.organizations.export', [], false) }}',
            'youths': '{{ route('municipal.youths.export', [], false) }}'
        };

        const exportUrl = `${routeMap[dataType]}?${params.toString()}`;
        window.location.href = exportUrl;
    }
</script>
@endsection
