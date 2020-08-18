
 @extends('layout')

 @section('content')
    <div>
        View Current Summary
            <table>
                <tr><td>PREFIX</td><td>TOTAL</td><td>carrierid</td><td>SIP Code</td><td>Sip Reason</td></tr>
                @foreach ($okCalls as $okCall)
                <tr><td>{{ $okCall->PREFIX }}</td><td>{{ $okCall->TOTAL}}</td><td>{{ $okCall->carrierid }}</td><td>{{ $okCall->sip_code }}</td><td>{{ $okCall->sip_reason }}</td></tr>
                @endforeach
            </table>
    </div>
@endsection
