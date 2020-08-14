@extends('layout')

@section('content')

<form action="/addCustomer" method="POST">
    @csrf
    <label class="description" for="element_1">First Name </label>
    <div>
        <input id="name" name="first_name" class="element text medium" type="text" value="">
    </div>
    <label class="description" for="element_1">Last Name </label>
    <div>
        <input id="name" name="last_name" class="element text medium" type="text" value="">
    </div>
    <label class="description" for="element_1">Prefix</label>
    <div>
    <input id="prefix" name="prefix" class="element text medium" type="text" value="{{ $newPrefix }}" readonly="readonly" size=4>
    </div>
    <label class="description" for="element_1">Rate increment</label>
    <div>
        <input id="rate_increment" name="rate_increment" class="element text medium" type="text" value="" size=4>
    </div>
    <label class="description" for="element_1">Rate minimum</label>
    <div>
        <input id="rate_minimum" name="rate_minimum" class="element text medium" type="text" value="" size=4>
    </div>
    <label class="description" for="element_1">LRN Enabled</label>
    <div>
        <select disabled>
            <option value="1">Yes</option>
            <option value="0" selected>No</option>
        </select>
    </div>
    <label class="description" for="element_1">Currency</label>
    <div>
        <select name="currency">
            <option value="USD" selected>USD</option>
            <option value="GBP" >GBP</option>
        </select>
    </div>


    <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit">
</form>
@if(session('success'))
<h3>{{session('success')}}</h3>
@endif
@endsection
