@extends('layout')

@section('content')

<div width="70%">
    <form action="/addExtension" method="POST">
        @csrf
        Prefix <select name="customerPrefix" id="customerPrefix">
            <option value="">Choose prefix</option>
            @foreach ($prefixes as $prefix):
            <option value="{{ $prefix->prefix }}">{{ $prefix->prefix }} </option>
            @endforeach
        </select><br>
        Extension name <input type="text" name="extension" class="form-control" onkeypress="return onlyNumberKey(event)" id="extension" size="35" tabindex="" value="">
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
<script>
    function onlyNumberKey(evt) {

        // Only ASCII charactar in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
</script>
