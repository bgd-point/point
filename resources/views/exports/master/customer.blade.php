<table>
  <tr>
    <th></th>
    <th></th>
  </tr>
  <tr>
    <th>Date Export</th>
    <th>{{ $exportedDate }}</th>
  </tr>
</table>
<table>
  <thead>
    <tr>    
      <th></th>
      <th></th>
      <th colspan="9">{{ $tenant }}</th>
    </tr>
    <tr>
      <th></th>
      <th></th>
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
    @foreach($data as $row)
    <tr>
      <th></th>
      <th></th>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $row->code }}</td>
      <td>{{ $row->name }}</td>
      <td>{{ $row->email }}</td>
      <td>{{ $row->phone }}</td>
      <td>{{ $row->address }}</td>
      <td>{{ $row->credit_limit }}</td>
      <td>{{ optional($row->pricingGroup)->label }}</td>
      <td>{{ count($row->groups) > 0 ? $row->groups->first()->name : '' }}</td>
    </tr>
    @endforeach
  </tbody>
</table>