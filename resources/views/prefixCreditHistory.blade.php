

 @extends('layout')

 @section('content')
 <div>
    View Credit History for
    <select name="prefixSelect" id="prefixSelect">
        <option value="">Choose prefix</option>
        @foreach ($prefixes as $prefix):
        <option value="{{ $prefix->prefix }}">{{ $prefix->prefix }} </option>
        @endforeach
    </select>

</div>
    <table width='90%' cellpadding='0' cellspacing='0'>
        <tr><td>Credit Date</td><td>Credit Balance</td><td>Previous Balance</td><td>Balance after credit</td></tr>
        @foreach ($credit_history as $history)
            <tr><td>{{$history->credit_date}}</td><td>{{$history->credit_amount}}</td><td>{{$history->previous_balance}}</td><td>{{$history->current_balance}}</td></tr>
        @endforeach
    </table>
@endsection


