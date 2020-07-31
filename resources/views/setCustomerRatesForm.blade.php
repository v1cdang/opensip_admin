@extends('layout')

@section('content')

<form action="/setCustomerRates" method="POST">
    @csrf
    <div class="form_description">
        <select name="prefix" id="prefix">
            <option value="">Choose prefix</option>
            @foreach ($prefixes as $prefix):
            <option value="{{ $prefix->prefix }}">{{ $prefix->prefix }} </option>
            @endforeach
        </select>
    </div>
    <label class="description" for="element_1">Code </label>
    <div>
        <input id="code" name="code" class="element text medium" type="text" value="">
    </div>
    <label class="description" for="element_1">Rate </label>
    <div>
        <input id="rate" name="rate" class="element text medium" type="text" value="">
    </div>

    <input type="hidden" name="form_id" value="117415">
    <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit">
</form>
@if(session('success'))
<h3>{{session('success')}}</h3>
@endif
@endsection
