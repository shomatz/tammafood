@extends('main')  
@section('content')
            <!--BEGIN PAGE WRAPPER-->
            <div id="page-wrapper">
                <!--BEGIN TITLE & BREADCRUMB PAGE-->
                <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                    <div class="page-header pull-left" style="font-family: 'Raleway', sans-serif;">
                        <div class="page-title">Monitoring Order & Stock</div>
                    </div>
                    <ol class="breadcrumb page-breadcrumb pull-right" style="font-family: 'Raleway', sans-serif;">
                        <li><i class="fa fa-home"></i>&nbsp;<a href="{{ url('/home') }}">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                        <li><i></i>&nbsp;Produksi&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                        <li class="active">Monitoring Order & Stock</li>
                    </ol>
                    <div class="clearfix">
                    </div>
                </div>
                <div class="page-content fadeInRight">
                    <div id="tab-general">
                        <div class="row mbl">
                            <div class="col-lg-12">
                                
                              <div class="col-md-12">
                                  <div id="area-chart-spline" style="width: 100%; height: 300px; display: none;">
                                  </div>
                              </div>
                  
                                
                              <ul id="generalTab" class="nav nav-tabs">
                                <li class="active"><a href="#alert-tab" data-toggle="tab">Monitoring Order & Stock</a></li>
                                <!-- <li><a href="#note-tab" data-toggle="tab">2</a></li>
                                <li><a href="#label-badge-tab" data-toggle="tab">3</a></li> -->
                              </ul>
                              <div id="generalTabContent" class="tab-content responsive">
                                
                                <div id="alert-tab" class="tab-pane fade in active">
                                 
                                  <div class="row" style="margin-top:-15px;">

                                    <!-- Modal Nota-->

                                    <div class="modal fade" id="nota" role="dialog">
                                      <div class="modal-dialog" >
                                          <!-- Modal content-->
                                            <div class="modal-content">
                                              <div class="modal-header" style="background-color: #e77c38;">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title" style="color: white;">Jumlah Nota</h4>
                                              </div>

                                              <div class="modal-body">

                                                

                                                <div class="table-responsive">
                                                  <table class="table tabelan table-hover table-bordered">
                                                    <thead>
                                                      <tr>
                                                        <th>No Nota</th>
                                                        <th>Tanggal</th>
                                                        <th>Jumlah Order</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody id="table-nota">
                                                      
                                                    </tbody>
                                                  </table>
                                                </div>
                                                
                                              </div>
                                          
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-warning " data-dismiss="modal">Close</button>
                                                
                                              </div>
                                            </div>
                                             
                                        </div>
                                    </div><!-- End Modal -->
                                   
                                      <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" style="margin-top:10px;">

                              <!--
                              <div class="col-md-8 col-sm-12 col-xs-12" style="padding-bottom: 10px;">
                                <div style="margin-left:-30px;">
                                  <div class="col-md-3 col-sm-2 col-xs-12">
                                    <label class="tebal">Tanggal</label>
                                  </div>

                                  <div class="col-md-6 col-sm-7 col-xs-12">
                                    <div class="form-group" style="display: ">
                                      <div class="input-daterange input-group">
                                        <input id="tanggal1" class="form-control input-sm datepicker2 " name="tanggal" type="text" value="{{ date('d-m-Y') }}">
                                        <span class="input-group-addon">-</span>
                                        <input id="tanggal2" class="input-sm form-control datepicker2" name="tanggal" type="text" value="{{ date('d-m-Y') }}">
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-3 col-xs-12" align="center">
                                  <button class="btn btn-primary btn-sm btn-flat" type="button" onclick="cariTanggal()">
                                    <strong>
                                      <i class="fa fa-search" aria-hidden="true"></i>
                                    </strong>
                                  </button>
                                  <button class="btn btn-info btn-sm btn-flat" type="button" onclick="refresh()">
                                    <strong>
                                      <i class="fa fa-undo" aria-hidden="true"></i>
                                    </strong>
                                  </button>
                                </div>
                                
                              </div>

                            -->


                                          <table class="table tabelan table-hover table-bordered" width="100%" cellspacing="0" id="data">
                                           <thead>
                                              <tr>
                                               <th>Kode Item</th>
                                               <th width="25%">Nama Item</th>
                                               <th>No Nota</th>
                                               <th>Jumlah Order</th>
                                               <th>Jumlah Stok</th>
                                               <th>Jumlah Kebutuhan</th>
                                               <th>Jumlah Rencana Produksi</th>
                                               <th>Jumlah Kekuarangan</th>
                                               <th>Aksi</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              
                  
                                            </tbody>
                                          </table> 
                                        </div>                                       
                                      </div>
                                  </div>

                                  <!-- detail order-->
                                  <div class="modal fade" id="modal" role="dialog">
                                    <div class="modal-dialog">
                                    
                                      
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                          <div class="modal-header" style="background-color: #e77c38;">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title" style="color: white;">Plan</h4>
                                          </div>
                                          <div class="modal-body">
                                                    
                                            

                                            <div class="table-responsive">
                                              <table class="table tabelan table-bordered table-hover">
                                                <thead>
                                                  <th>Tanggal</th>
                                                  <th>Jumlah Rencana Produksi</th>
                                                  <th>Status SPK</th>
                                                </thead>
                                                <tbody id="table-plan">
                                                  

                                                </tbody>
                                              </table>
                                            </div>
                                            
                                          </div>
                                              
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" onclick="plan()">Simpan Data</button>
                                          </div>

                                        </div>
                                      
                                    </div>
                                  </div>
                                  <!-- end detail order-->

                                  
                                </div>
                                <!-- /div alert-tab -->
                 <!-- div note-tab -->
                  <div id="note-tab" class="tab-pane fade">
                    <div class="row">
                      <div class="panel-body">
                        <!-- Isi Content -->
                      </div>
                    </div>
                  </div><!--/div note-tab -->
                  <!-- div label-badge-tab -->
                  <div id="label-badge-tab" class="tab-pane fade">
                    <div class="row">
                      <div class="panel-body">
                        <!-- Isi content -->we
                      </div>
                    </div>
                  </div><!-- /div label-badge-tab -->
                            </div>
                    
            </div>
          </div>

        </div>
      </div>
    </div>

@endsection
@section("extra_scripts")
<script type="text/javascript">
  $(document).ready(function() {
    var extensions = {
         "sFilterInput": "form-control input-sm",
        "sLengthSelect": "form-control input-sm"
    }
    // Used when bJQueryUI is false
    $.extend($.fn.dataTableExt.oStdClasses, extensions);
    // Used when bJQueryUI is true
    $.extend($.fn.dataTableExt.oJUIClasses, extensions);
    $('#data').dataTable({
        "responsive":true,
        "pageLength": 10,
        "lengthMenu": [[10, 20, 50, - 1], [10, 20, 50, "All"]],
        "language": {
            "searchPlaceholder": "Cari Data",
            "emptyTable": "Tidak ada data",
            "sInfo": "Menampilkan _START_ - _END_ Dari _TOTAL_ Data",
            "infoFiltered" : "",
            "sSearch": '<i class="fa fa-search"></i>',
            "sLengthMenu": "Menampilkan &nbsp; _MENU_ &nbsp; Data",
            "infoEmpty": "",
            "zeroRecords": "Data tidak ditemukan",
            "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Selanjutnya",
                }
          },

        "ajax":{
              "url" : baseUrl + "/produksi/monitoringprogress/tabel",
              "type": "GET",
              
        },
        "columns": [
            { "data": "sd_item" },
            { "data": "i_name" },
            { "data": "jumlah" },
            { "data": "nota" },
            { "data": "s_qty" },
            { "data": "j_butuh" },
            { "data": "pp_qty" },
            { "data": "j_kurang" },
            { "data": "plan" },
        ]

    });
    $('#data2').dataTable({
          "responsive":true,

          "pageLength": 10,
        "lengthMenu": [[10, 20, 50, - 1], [10, 20, 50, "All"]],
        "language": {
            "searchPlaceholder": "Cari Data",
            "emptyTable": "Tidak ada data",
            "sInfo": "Menampilkan _START_ - _END_ Dari _TOTAL_ Data",
            "sSearch": '<i class="fa fa-search"></i>',
            "sLengthMenu": "Menampilkan &nbsp; _MENU_ &nbsp; Data",
            "infoEmpty": "",
            "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Selanjutnya",
                 }
          }

        });
    $('#data3').dataTable({
          "responsive":true,

          "pageLength": 10,
        "lengthMenu": [[10, 20, 50, - 1], [10, 20, 50, "All"]],
        "language": {
            "searchPlaceholder": "Cari Data",
            "emptyTable": "Tidak ada data",
            "sInfo": "Menampilkan _START_ - _END_ Dari _TOTAL_ Data",
            "sSearch": '<i class="fa fa-search"></i>',
            "sLengthMenu": "Menampilkan &nbsp; _MENU_ &nbsp; Data",
            "infoEmpty": "",
            "paginate": {
                    "previous": "Sebelumnya",
                    "next": "Selanjutnya",
                 }
          }

    });

    $(document).on('click','.plan',function(){
        var id = $(this).data('id');
        console.log('plan '+id);
        $.ajax({
        url : baseUrl + "/produksi/monitoringprogress/plan/"+id,
        type: 'get',   
        
        success:function(response)
        {

         $('#table-plan').html(response);
        }
      });
    });

    $(document).on('click','.nota',function(){
        var id = $(this).data('id');
        console.log('nota '+id);
        $.ajax({
        url : baseUrl + "/produksi/monitoringprogress/"+id,
        type: 'get',   
        
        success:function(response)
        {

         $('#table-nota').html(response);
        }
      });
    });

});

    $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        altFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true
      });

    $('.datepicker2').datepicker({
        format:"dd-mm-yyyy"
      });    

    function cariTanggal()
    {
      var tgl1=$('#tanggal1').val();
      var tgl2=$('#tanggal2').val();
      console.log(tgl1);
      $.ajax({
        url : baseUrl + "/produksi/monitoringprogress/"+tgl1+'/'+tgl2,
        type: 'get',   
        
        success:function(response)
        {

         $('#data-search').html(response);
        }
      });
    }

    function refresh()
    {
      $.ajax({
        url : baseUrl + "/produksi/monitoringprogress/refresh",
        type: 'get',   
        
        success:function(response)
        {

         $('#data-search').html(response);
        }
      });
    }
      </script>
@endsection()