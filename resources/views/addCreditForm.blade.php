@extends('layout')

@section('content')

<form action="/addCreditforPrefix" method="POST">
    @csrf
    <div class="form_description">
        <h2>Add Credit for {{ $selectedPrefix }}</h2>

        <input type="hidden" name="selectedPrefix" value="{{ $selectedPrefix }}"
    </div>
    <ul>
        <li id="li_1">
            <label class="description" for="element_1">Credit Amount </label>
            <div>
                <input id="element_1" name="credit_amount" class="element text medium" type="text" maxlength="5" value="">
            </div>
        </li>

        <li class="buttons">
            <input type="hidden" name="form_id" value="117415">
            <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit">
        </li>
    </ul>
</form>
@if(session('success'))
<h3>{{session('success')}}</h3>
@endif
@endsection
