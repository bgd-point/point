<table>
    <thead>
        <tr>
            <th colspan="9" style="text-align: left">{{ \Carbon\Carbon::now() }}</th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center">{{$tenant}}</th>
        </tr>
        <tr style="border: 1px solid">
            <th>No</th>
            <th>Customer Code</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Credit Limit</th>
            <th>Pricing Group</th>
            <th>Customer Group</th>
        </tr>
    </thead>
    <tbody style="border: 1px solid">
        @foreach ($customers as $item)
        <tr>
            <td>{{$loop->iteration ?? ''}}</td>
            <td>{{$item->code}}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->email}}</td>
            <td>{{$item->address}}</td>
            <td>{{$item->phone}}</td>
            <td>{{$item->credit_limit}}</td>
            <td>{{$item->pricing_group->label ?? ''}}</td>
            <td>
                @foreach ($item->customer_group ?? [] as $cg)
                    {{$cg->name}},
                @endforeach    
            </td>
        </tr>
        @endforeach
        
    </tbody>
</table>