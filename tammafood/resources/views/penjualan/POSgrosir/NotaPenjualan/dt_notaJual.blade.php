        <table class="table tabelan table-bordered table-hover dt-responsive" id="data2">
          <thead>
              <th  width="2%">Tanggal Nota</th>
              <th width="15%">No Nota</th>
              <th width="2%">Customer</th>
              <th width="2%">Item</th>
              <th width="25%">Nominal</th>
              <th width="2%">Status</th>
              <th width="10%">Aksi</th>
            </thead>
            <tbody>
              @foreach ($detalis as $index => $detail)                       
              <tr>
                <td class="text-center">{{ date('d M Y', strtotime($detail->s_date)) }}</td>
                <td class="text-center">{{ $detail->s_note }}</td>
                <td class="text-center">{{ $detail->c_name }}</td>
                <td class="text-center"><button type="button" class="btn btn-info btn-sm" data-toggle="modal"  onclick="lihatDetail('{{ $detail->s_id }}')" data-target="#myItem">Buka Item</button></td>
                <td><input style="text-align:right" type="text" readonly="true" class="nominal form-control" value="Rp.{{ number_format( $detail->s_gross ,2,',','.')}}"></td>
                <td class="text-center">{{ $detail->s_status }}</td>
              <td class="text-center">
                <div class="">
                 <a href="{{ url('/penjualan/POSgrosir/grosir/edit_sales',$detail->s_id) }}" class="btn btn-warning btn-sm" title="Edit" @if($detail->s_status=='FN') disabled  @endif id="FormDeleteTime"><i class="fa fa-pencil"></i></a>
                 <a onclick="return confirm('Apakah anda yakin?')"; href="{{ url('/penjualan/POSgrosir/grosir/distroy',$detail->s_id) }}" class="btn btn-danger btn-sm" title="Hapus"  @if($detail->s_status=='FN' || $detail->s_status=='PR') disabled  @endif ><i class="fa fa-trash-o"></i></a>
                </div>                            
            </td>
            </tr>
            @endforeach
          </tbody>
        </table>
<script>
  $('#data2').DataTable();

</script>