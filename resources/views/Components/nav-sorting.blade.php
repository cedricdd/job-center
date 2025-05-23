@props(['type'])

@php
    if ($type == 'jobs') {
        $elements = Constants::JOB_SORTING;
        $route = route('sorting', 'job');
        $session = Session::get('job-sorting', Constants::JOB_SORTING_DEFAULT);
    } elseif ($type == 'employers') {
        $elements = Constants::EMPLOYER_SORTING;
        $route = route('sorting', 'employer');
        $session = Session::get('employer-sorting', Constants::EMPLOYER_SORTING_DEFAULT);
    } else {
    }
@endphp

<div class="mt-4 p-6 bg-white/10 rounded">
    <form action="{{ $route }}" method="POST" class="flex justify-center items-center flex-wrap gap-2">
        @csrf
        @foreach ($elements as $name => ['label' => $sorting])
            <x-forms.button name="sorting" value="{{ $name }}">
                @if ($session == $name)
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 11 12 14 22 4" />
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                    </svg>
                @else
                    <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <rect x="4" y="4" width="16" height="16" rx="2" />
                    </svg>
                @endif
                <span>{{ $sorting }}</span>
            </x-forms.button>
        @endforeach
    </form>
</div>
