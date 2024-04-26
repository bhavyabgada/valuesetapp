@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Value Sets</h1>
    <form method="GET" action="{{ route('valuesets.index') }}">
        <input type="text" name="search" placeholder="Search value sets" value="{{ request('search') }}">
        <button type="submit">Search</button>
    </form>
    <ul>
        @foreach ($valueSets as $valueSet)
            <li>
                <a href="{{ route('valueSets.show', $valueSet) }}">{{ $valueSet->name }}</a>
                - Medications: {{ $valueSet->medications->count() }}
            </li>
        @endforeach
    </ul>
    {{ $valueSets->links() }}  <!-- Pagination links -->
</div>
@endsection
