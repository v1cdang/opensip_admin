
 @extends('layout')

 @section('content')
    <div>
        View Current Summary
            @foreach ($prefixes as $prefix):
            <option value="creditHistory/{{ $prefix->prefix }}">{{ $prefix->prefix }} </option>
            @endforeach

    </div>
@endsection
