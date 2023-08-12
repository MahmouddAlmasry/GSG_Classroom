{{-- @php
    $class = $name == 'error' ? 'danger' : 'success';
@endphp --}}

@props([
    'name'
])

<div>
    @if (session()->has($name))
        <div {{$attributes->class(['alert'])}}>
            From Component: {{ session($name) }}
        </div>
    @endif
</div>
