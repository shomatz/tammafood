<?php 

namespace App\Http\Controllers\Penjualan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\m_item;

class stockController extends Controller
{
  public function tableStock(Request $request){
    if($request->numberload=='')
      $request->numberload=10;
    $stock=m_item::leftjoin('d_stock',function($join){
        $join->on('i_id', '=', 's_item');        
        $join->on('s_comp', '=', 's_position');                
        $join->on('s_comp', '=',DB::raw("'11'"));           
    })    
    ->where('i_type', '=',DB::raw("'BJ'"))
    ->orWhere('i_type', '=',DB::raw("'BP'"))   
    ->orderBy('i_name')
    //->toSql();
    ->paginate($request->numberload);    
    

       if ($request->ajax()) {
            return view('penjualan.POSretail.stokRetail.table-stock', compact('stock'));

        }
        
    return view('penjualan.POSretail.StokRetail.stock',compact('stock'));
  }

  public function transferItem(Request $request){
    $term = $request->term;

    $results = array();

    $queries = m_item::  
    where('i_type', '=', DB::raw("'BP'"))
    ->where('i_name', 'like', DB::raw('"%'.$request->term.'%"'))        
    
    ->orWhere('i_type', '=', DB::raw("'BJ'"))
    ->where('i_name', 'like', DB::raw('"%'.$request->term.'%"'))       

    ->get();
    
    if ($queries == null) {
      $results[] = [ 'id' => null, 'label' =>'tidak di temukan data terkait'];
    } else {
      foreach ($queries as $query) 
      {
        if($query->s_qty=='')
          $query->s_qty=0;
        $results[] = [ 'id' => $query->i_id, 'label' =>$query->i_code.'-'. $query->i_name, 'code' => $query->i_id,
                       'name' => $query->i_name ];
      }
    }
 
    return Response::json($results);
  }

  public function transferItemGrosir(Request $request){
    
    $term = $request->term;

    $results = array();


    $queries=m_item::leftjoin('d_stock',function($join) use ($request){
        $join->on('i_id', '=', 's_item');        
        $join->on('s_comp', '=', 's_position');                
        $join->on('s_comp', '=',DB::raw("'3'"));                   
    })    
    ->where('i_type', '=',DB::raw("'BJ'"))    
    ->where('i_name', 'like',DB::raw('"%'.$request->term.'%"')) 
    ->orWhere('i_type', '=',DB::raw("'BP'"))       
    ->where('i_name', 'like',DB::raw('"%'.$request->term.'%"'))
    ->orderBy('i_name')
    //->toSql();
    ->get();   
    
    if ($queries == null) {
      $results[] = [ 'id' => null, 'label' =>'tidak di temukan data terkait'];
    } else {
      foreach ($queries as $query) 
      {
        if($query->s_qty=='')
          $query->s_qty=0;
        $results[] = [ 'id' => $query->i_id, 'label' =>$query->i_code.'-'. $query->i_name.'('. $query->s_qty .')', 'code' => $query->i_id,
                       'name' => $query->i_name ];
      }
    }
 
    return Response::json($results);
  }

  public function update(Request $request, $s_item)
  {
    $data = array(
        's_comp' => 11,
        's_position' => 11,
        's_item' => $s_item,
        's_qty' => $request->s_qty
      );

    //cek item 
    $cek = DB::Table('d_stock')
            ->where('s_item',$s_item)
            ->get();
    //jika item ada
    if(count($cek) == 1){
      $simpan = DB::Table('d_stock')
            ->where('s_item',$s_item)
            ->update($data);
    }

    //jika belum ada item
    if(count($cek) == 0){
      $id = DB::Table('d_stock')->select('s_id')->max('s_id');
        if ($id <= 0 || $id <= '') {
          $id  = 1;
        }else{
          $id += 1;
        }
        $data['s_insert'] = carbon::now()->format('Y-m-d');
        $data['s_id'] = $id;
        $simpan = DB::Table('d_stock')->insert($data);
    }        
    //$request['ajax()'] = TRUE;
    return $this->tableStock($request);
  }
}

