@extends('web.master')

@section('body')
<div class="p-4">
    <h4>Selected Date: {{ session('selected_date', 'Not set') }}</h4>
    <h4>Selected Section: {{ ucfirst(session('selected_section', 'Not set')) }}</h4>
</div>
@endsection
