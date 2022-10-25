<table>
    <thead>
    <tr>
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
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td><?=$loop->iteration?></td>
            <td>{{ $customer->code }}</td>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ $customer->address }}</td>
            <td>{{ $customer->credit_limit }}</td>
            <td>{{ $customer->pricing_group_id }}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>