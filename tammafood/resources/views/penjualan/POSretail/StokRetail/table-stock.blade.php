<div class="table-responsive no-padding">       
 <table class="table tabelan table-bordered no-padding" id="data4">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Item</th>
              <th>Tipe Item</th>
              <th>Group Item</th>
              <th>Harga Jual</th>
              <th>Stock</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($stock as $index=> $data)
            <tr>
              <td>{{   ($stock->currentpage()-1) * $stock->perpage() + $index + 1  }}</td>
              <td>{{ $data->i_name }}</td>
              <td>
                    @if($data->i_type=='BJ')
                    Barang Jual
                    @elseif($data->i_type=='BP')
                    Barang Produksi
                    @endif
              </td>
              <td>{{ $data->i_group }}</td>
              <td>Rp.
                  <span class="pull-right">
                    {{ number_format($data->i_price,2,',','.')}}</td>
                  </span>
              <td>
                  <span class="pull-right">
                    @if($data->s_qty=='')
                      0
                    @else
                      {{$data->s_qty}}
                    @endif
                  </span>
              </td>
              <td>
                <a href="" class="btn btn-warning btn-sm" data-toggle="modal"  onclick="lihatDetail('{{ $data->s_id }}')" data-target="#EditItem" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
              </td>
            </tr> 
            @endforeach
          </tbody>
</table>
  <div id="c" class="pull-right">
    {{$stock->links()}}
  </div>
</div>

<div class="modal fade" id="EditItem" role="dialog">
  <div class="modal-dialog" >
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: #e77c38;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"  style="color: white;">Nama Item</h4>
        
      </div>
      <div class="modal-body">
        <table class="table tabelan table-bordered no-padding">
          <thead>
            <tr>
              <th>Nama Item</th>
              <th>Tipe Item</th>
              <th>Group Item</th>
              <th>Harga Jual</th>
              <th>Stock</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>