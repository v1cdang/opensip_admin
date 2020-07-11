
 @extends('layout')

 @section('content')
    <div>
        View Credit History for
        <select name="prefixSelect" id="prefixSelect">
            <option value="">Choose prefix</option>
            @foreach ($prefixes as $prefix):
            <option value="creditHistory/{{ $prefix->prefix }}">{{ $prefix->prefix }} </option>
            @endforeach
        </select>

    </div>
@endsection
