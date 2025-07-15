@extends('layouts.front')

<script>
    const userId = "{{ auth()->user()->id }}";

    const csrf_token = "{{ csrf_token() }}";

    console.log(csrf_token)
</script>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/chat_styles.css') }}" />
@endpush

@section('content')
    <div class="container-chat">
        <messenger />
    </div>
@endsection

@push('scripts')
    @vite('resources/js/messages.js')
@endpush
