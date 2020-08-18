
 @extends('layout')

 @section('content')
    <div>
        View Current Summary
            <table>
                <tr><td>PREFIX</td><td>TOTAL</td><td>DURATION</td><td>Customer Total Cost</td><td>Average Customer Rate</td><td>Total Vendor Call Cost</td><td>Average Carrier Rate</td><td>carrierid</td></tr>
            @foreach ($okCalls as $okCall):
            <tr><td>{{ $okCall->PREFIX }}</td><td>TOTAL</td><td>DURATION</td><td>Customer Total Cost</td><td>Average Customer Rate</td><td>Total Vendor Call Cost</td><td>Average Carrier Rate</td><td>carrierid</td></tr>
            @endforeach
            </table>
    </div>
@endsection
