@extends('layout')

@section('content')
<div>
    Allow Country
    <select name="prefixSelect" id="prefixSelect">
        <option value="">Choose prefix</option>
        @foreach ($prefixes as $prefix):
        <option value="setAllowedCountries/{{ $prefix->prefix }}">{{ $prefix->prefix }} </option>
        @endforeach
    </select>

</div>

@isset($selectedPrefix)
<form action="/setAllowedCountriesSubmit" method="POST">
    @csrf

    <div class="form_description">
        <input type="hidden" name="selectedPrefix" value="{{ $selectedPrefix }}" />
    </div>

        <label class="description" for="element_1">Select Allowed Countries</label>
            <div>
                <select name="countries[]" id="countries" multiple>
                    @foreach ($countries as $country)
                        <option value="{{ $country->alpha2}}">{{$country->country_name}}</option>
                    @endforeach
                </select>
            </div>
            <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit">
</form>
@endisset

@if(session('success'))
<h3>{{session('success')}}</h3>
@endif
@endsection
