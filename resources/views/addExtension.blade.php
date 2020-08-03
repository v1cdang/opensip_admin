@extends('layout')

@section('content')

<div width="70%">
    <form action="/addExtension" method="POST">
        @csrf
        Extension name <input type="text" name="extension" class="form-control " id="extension" size="35" tabindex="" value="">
        Display name <input type="text" name="name" class="form-control " id="name" size="35" tabindex="" value="">
        Caller ID <input type="text" name="outboundcid" class="form-control " id="outboundcid" size="35" tabindex="" value="">
        Secret <input type="text" name="secret" class="form-control password-meter confidential" id="secret" size="35" tabindex="" autocomplete="off" value="{{ $newpassword }}">
        <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit">

    </form>
</div>
@if(session('success'))
<h3>{{session('success')}}</h3>
@endif
@endsection
