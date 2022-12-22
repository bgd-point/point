<table>
  <thead>
    <tr>
      <th></th>
    </tr>
    <tr>
      <th>Date :</th>
      <th>{{ $today }}</th>
    </tr>
    <tr>
      <th></th>
    </tr>
    <tr>
      <th></th>
      <th></th>
      <th colspan="9" style="text-align: center">Nama Tenant</th>
    </tr>
    <tr>
      <th></th>
      <th></th>
      <th style="border: 1px solid #000000">No</th>
      <th style="border: 1px solid #000000">Customer Code</th>
      <th style="border: 1px solid #000000">Customer Name</th>
      <th style="border: 1px solid #000000">Email</th>
      <th style="border: 1px solid #000000">Phone</th>
      <th style="border: 1px solid #000000">Address</th>
      <th style="border: 1px solid #000000">Credit Limit</th>
      <th style="border: 1px solid #000000">Pricing Group</th>
      <th style="border: 1px solid #000000">Customer Group</th>
    </tr>
  </thead>
  <tbody>
    {{ $i=1 }}
    @foreach ($customer as $dt)
    <tr>
      <td></td>
      <td></td>
      <td style="border: 1px solid #000000">{{ $i++ }}</td>
      <td style="border: 1px solid #000000">{{ $dt->code }}</td>
      <td style="border: 1px solid #000000">{{ $dt->name }}</td>
      <td style="border: 1px solid #000000">{{ $dt->email }}</td>
      <td style="border: 1px solid #000000">{{ $dt->phone }}</td>
      <td style="border: 1px solid #000000">{{ $dt->address }}</td>
      <td style="border: 1px solid #000000">{{ $dt->credit_limit }}</td>
      <td style="border: 1px solid #000000">{{ $dt->pricing_group_id }}</td>
      <td style="border: 1px solid #000000"></td>
    </tr>
    @endforeach
  </tbody>
</table>