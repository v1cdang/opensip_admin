@extends('layout')

@section('content')
<div>
    Add Credit for
    <select name="prefixSelect" id="prefixSelect">
        <option value="">Choose prefix</option>
        @foreach ($prefixes as $prefix):
        <option value="addCredit/{{ $prefix->prefix }}">{{ $prefix->prefix }} </option>
        @endforeach
    </select>

</div>
@endsection
