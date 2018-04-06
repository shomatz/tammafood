<?php
  
namespace App\Http\Controllers\Produksi;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Response;
use App\Http\Requests;
use Illuminate\Http\Request;

class MonitoringProgressController extends Controller
{
  public function monitoring(){
    $monitoring = DB::Table('d_sales_dt')
            ->select('sd_item','i_name','s_qty','pp_qty',DB::raw("max(s_date) as date"), DB::raw("sum(sd_qty) as jumlah"), DB::raw("count(sd_sales) as nota"))
            ->join('m_item', 'm_item.i_id', '=' , 'd_sales_dt.sd_item')
            ->join('d_sales', 'd_sales.s_id', '=' , 'd_sales_dt.sd_sales')        
            ->join('d_stock','d_stock.s_item','=','d_sales_dt.sd_item')
            ->join('d_productplan','d_productplan.pp_item','=','d_sales_dt.sd_item')
            ->where('s_comp','2')->where('s_position','2')
            ->where('s_status','PR')
            ->groupBy('sd_item','i_name','s_qty','pp_qty')
            ->get();
            
    return view('produksi.monitoringprogress.monitoring', compact('monitoring'));
  }

  public function tabel()
  {
    $mon = DB::Table('d_sales_dt')
            ->select('sd_item','i_name','s_qty','pp_qty',DB::raw("max(s_date) as date"), DB::raw("sum(sd_qty) as jumlah"), DB::raw("count(sd_sales) as nota"))
            ->join('m_item', 'm_item.i_id', '=' , 'd_sales_dt.sd_item')
            ->join('d_sales', 'd_sales.s_id', '=' , 'd_sales_dt.sd_sales')        
            ->join('d_stock','d_stock.s_item','=','d_sales_dt.sd_item')
            ->join('d_productplan','d_productplan.pp_item','=','d_sales_dt.sd_item')
            ->where('s_comp','2')->where('s_position','2')
            ->where('s_status','PR')
            ->groupBy('sd_item','i_name','s_qty','pp_qty')
            ->get();

    $data = array();
    foreach ($mon as $r) {
      $data[] = (array) $r;
    }
    $i=0;
    foreach ($data as $key) {
            // add new button
      $data[$i]['nota'] = '<button id="nota" class="btn btn-info btn-sm nota" 
                                                        data-toggle="modal" 
                                                        data-target="#nota"
                                                        data-id="'.$data[$i]['sd_item'].'">
                                                        '.$data[$i]['nota'].'</button>';
      $data[$i]['plan'] = '<a href="#" data-toggle="modal" data-target="#modal" 
                                              class="btn btn-info btn-sm plan" 
                                              data-id="'.$data[$i]['sd_item'].'">Plan</a>';
      $data[$i]['j_butuh'] = $j_kebutuhan = $data[$i]['jumlah'] - $data[$i]['s_qty'] < 0 ? 
                                              0 :  $data[$i]['jumlah'] - $data[$i]['s_qty'];
      $data[$i]['j_kurang'] = $data[$i]['j_butuh'] - $data[$i]['pp_qty'] < 0 ? 0 : $data[$i]['j_butuh'] - $data[$i]['pp_qty'];


      
      $i++;
    }
    $datax = array('data' => $data);
    echo json_encode($datax);
  }
  public function nota($id)
  {
    $nota = DB::Table('d_sales_dt')
            ->where('sd_item',$id)
            ->join('d_sales','d_sales.s_id','=','d_sales_dt.sd_sales')
            ->orderBy('s_date','asc')
            ->get();
    return view('produksi.monitoringprogress.nota', compact('nota'));
  }

  public function plan($id)
  {
    $plan = DB::Table('d_sales_dt')
            ->where('sd_item',$id)
            ->join('d_sales','d_sales_dt.sd_sales','=','d_sales.s_id')
            ->orderBy('s_date','asc')
            ->get();
    return view('produksi.monitoringprogress.plan', compact('plan'));  
  }

  public function search($tgl1, $tgl2)
  {
    $y = substr($tgl1, -4);
      $m = substr($tgl1, -7,-5);
      $d = substr($tgl1,0,2);
       $tgll = $y.'-'.$m.'-'.$d;

      $y2 = substr($tgl2, -4);
      $m2 = substr($tgl2, -7,-5);
      $d2 = substr($tgl2,0,2);
        $tgl2 = $y2.'-'.$m2.'-'.$d2;

      $monitoring = DB::Table('d_sales')
          ->whereDate('s_date','>=',$tgll)
          ->whereDate('s_date','<=',$tgl2)
          ->get();

    return view('produksi.rencanaproduksi.data', compact('monitoring'));
  }

  public function refresh()
  {
    $monitoring = DB::Table('d_sales_dt')
            ->select('sd_item','i_name','s_qty','pp_qty',DB::raw("max(s_date) as date"), DB::raw("sum(sd_qty) as jumlah"), DB::raw("count(sd_sales) as nota"))
            ->join('m_item', 'm_item.i_id', '=' , 'd_sales_dt.sd_item')
            ->join('d_sales', 'd_sales.s_id', '=' , 'd_sales_dt.sd_sales')        
            ->join('d_stock','d_stock.s_item','=','d_sales_dt.sd_item')
            ->join('d_productplan','d_productplan.pp_item','=','d_sales_dt.sd_item')
            ->where('s_comp','2')->where('s_position','2')
            ->where('s_status','PR')
            ->groupBy('sd_item','i_name','s_qty','pp_qty')
            ->get();
    
    return $monitoring; //view('produksi.rencanaproduksi.data', compact('monitoring'));
  }
}