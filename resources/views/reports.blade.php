
 @extends('layout')

 @section('content')
    <div>
        View Current Summary
            <table>
                <tr><td>PREFIX</td><td>TOTAL</td><td>DURATION</td><td>Customer Total Cost</td><td>Average Customer Rate</td><td>Total Vendor Call Cost</td><td>Average Carrier Rate</td><td>carrierid</td></tr>
                @foreach ($okCalls as $okCall)
                <tr><td>{{ $okCall->PREFIX }}</td><td>{{ $okCall->TOTAL}}</td><td>{{ $okCall->DURATION }}</td><td>{{ $okCall->customerCallCost }}</td>
                <td>{{ $okCall->avgcustomerCost }}</td><td>{{ $okCall->vendorCallCost }}</td><td>{{ $okCall->avgcarrierCost }}</td><td>{{ $okCall->carrierid }}</td></tr>
                @endforeach
            </table>
    </div>
@endsection
