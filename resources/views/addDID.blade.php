@extends('layout')

@section('content')

<div width="70%">
    <form action="/addDIDtoExt" method="POST">
        @csrf
        <label class="description" for="customerPrefix">Prefix</label>
         <select name="customerPrefix" id="customerPrefix">
            <option value="">Choose prefix</option>
            @foreach ($prefixes as $prefix):
            <option value="{{ $prefix->prefix }}">{{ $prefix->prefix }} </option>
            @endforeach
        </select><br>
        <label class="description" for="destinationExt">Extension</label>
        <select name="destinationExt" id="destinationExt">
            <option value="">Choose extension</option>

        </select><br>
        <label class="description" for="DID">Enter DID</label>
        <select name="DID" id="DID">
            <option value="">Choose Inbound DID</option>

        </select>


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
