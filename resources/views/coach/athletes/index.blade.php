@extends('layouts.app')
@section('title', 'Manage Athletes')

@section('content')
<div class="overflow-x-auto bg-white rounded-lg shadow-lg p-6">

    {{-- Search Form --}}
    <form method="GET" action="{{ route('coach.athletes.index') }}" class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium mb-1">Search Athletes</label>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search by name, year level, or sport..." 
                class="w-full border rounded p-2"
            />
        </div>
        
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium mb-1">Filter by Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="injured" {{ request('status') === 'injured' ? 'selected' : '' }}>Injured</option>
                <option value="graduate" {{ request('status') === 'graduate' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium mb-1">Filter by Year Level</label>
            <select name="year_level" class="w-full border rounded p-2">
                <option value="">All Year Levels</option>
                @foreach(['1st year', '2nd year', '3rd year', '4th year'] as $level)
                    <option value="{{ $level }}" {{ request('year_level') === $level ? 'selected' : '' }}>
                        {{ $level }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Search
            </button>
            <a href="{{ route('coach.athletes.index') }}" 
               class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Clear
            </a>
        </div>
    </form>

    @if($athletes->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg">No athletes found matching your criteria.</p>
            <a href="{{ route('coach.athletes.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                Clear filters
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border">Name</th>
                        <th class="p-3 border">Sport</th>
                        <th class="p-3 border">Year Level</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($athletes as $athlete)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
                                    <img src="{{ $athlete->profile_url }}" 
                                         alt="{{ $athlete->full_name }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <span>{{ $athlete->full_name }}</span>
                            </div>
                        </td>
                        <td class="p-3 border">{{ $athlete->sport->sport_name ?? 'N/A' }}</td>
                        <td class="p-3 border">
                            @if($athlete->year_level)
                                {{ $athlete->year_level }}
                            @else
                                <span class="text-gray-500">Not set</span>
                            @endif
                        </td>
                        <td class="p-3 border">
                            @if($athlete->conditions)
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'injured' => 'bg-red-100 text-red-800',
                                        'graduate' => 'bg-blue-100 text-blue-800'
                                    ];
                                    $statusText = [
                                        'active' => 'Active',
                                        'injured' => 'Injured',
                                        'graduate' => 'Archived'
                                    ];
                                    $color = $statusColors[$athlete->conditions] ?? 'bg-gray-100 text-gray-800';
                                    $text = $statusText[$athlete->conditions] ?? ucfirst($athlete->status);
                                @endphp
                                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $color }}">
                                    {{ $text }}
                                </span>
                            @else
                                <span class="text-gray-500">Not set</span>
                            @endif
                        </td>
                        <td class="p-3 border">
                            <div class="flex gap-2 items-center">
                                <a href="{{ route('coach.athletes.show', $athlete->athlete_id) }}" 
                                   class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                    View
                                </a>
                                
                                {{-- Status Change Dropdown --}}
                                <form action="{{ route('coach.athletes.update-status', $athlete->athlete_id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Change athlete status to ' + this.status.value + '?')">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" 
                                            onchange="this.form.submit()" 
                                            class="border rounded p-1.5 text-sm bg-white min-w-32">
                                        <option value="active" {{ $athlete->conditions === 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="injured" {{ $athlete->conditions === 'injured' ? 'selected' : '' }}>
                                            Injured
                                        </option>
                                        <option value="graduate" {{ $athlete->conditions === 'graduate' ? 'selected' : '' }}>
                                            Archive
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="text-gray-600">
                Showing {{ $athletes->firstItem() }} to {{ $athletes->lastItem() }} of {{ $athletes->total() }} athletes
            </div>
            <div class="mt-4">
                {{ $athletes->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading indicator when changing status
    const statusForms = document.querySelectorAll('form[action*="update-status"]');
    statusForms.forEach(form => {
        const select = form.querySelector('select[name="status"]');
        select.addEventListener('change', function() {
            // Show loading on the select
            this.disabled = true;
            this.style.opacity = '0.5';
            
            // Submit the form
            form.submit();
        });
    });
});
</script>
@endsection