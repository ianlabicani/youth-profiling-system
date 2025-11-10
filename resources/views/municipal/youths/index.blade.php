@extends('municipal.shell')

@section('municipal-content')
<div class="space-y-6">

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

    <!-- Youths Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($youths->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Purok</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">SK Member</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($youths as $youth)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $youth->first_name }}
                                            @if($youth->middle_name)
                                                {{ substr($youth->middle_name, 0, 1) }}.
                                            @endif
                                            {{ $youth->last_name }}
                                            @if($youth->suffix)
                                                {{ $youth->suffix }}
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">ID: {{ $youth->id }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">
                                        @if($youth->contact_number)
                                            <p>{{ $youth->contact_number }}</p>
                                        @endif
                                        @if($youth->email)
                                            <p class="text-xs text-gray-500">{{ $youth->email }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $youth->purok ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($youth->status === 'active') bg-green-100 text-green-800
                                        @elseif($youth->status === 'inactive') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($youth->status ?? 'unknown') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($youth->is_sk_member)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-check-circle mr-1"></i>Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('municipal.dashboard', $youth) }}"
                                           class="text-blue-600 hover:text-blue-900 transition">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($youths->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $youths->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">No youths found</p>
                <p class="text-gray-400 mt-2">There are no youth records yet</p>
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
</script>
@endsection
