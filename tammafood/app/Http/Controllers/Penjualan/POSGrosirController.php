<?php

namespace App\Http\Controllers\Penjualan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Response;
use App\Http\Requests;
use Illuminate\Http\Request;

class POSGrosirController extends Controller
{
   public function grosir()
    { 
        $year = carbon::now()->format('y');
        $month = carbon::now()->format('m');
        $date = carbon::now()->format('d');

        //select max dari um_id dari table d_uangmuka
        $maxid = DB::Table('m_customer')->select('c_id')->max('c_id');
        $idfatkur = DB::Table('d_sales')->select('s_id')->max('s_id');
        $idreq = DB::table('d_transferitem')->select('ti_id')->max('ti_id');
        //untuk +1 nilai yang ada,, jika kosong maka maxid = 1 , 
        if ($maxid <= 0 || $maxid <= '') {
          $maxid  = 1;
        }else{
          $maxid += 1;
        }

        if ($idfatkur <= 0 || $idfatkur <= '') {
          $idfatkur  = 1;
        }else{
          $idfatkur += 1;
        }

        if ($idreq <= 0 || $idreq <= '') {
          $idreq  = 1;
        }else{
          $idreq += 1;
        }
        //jika kurang dari 100 maka maxid mimiliki 00 didepannya
        if ($maxid < 100) {
          $maxid = '00'.$maxid;
        }
          $id_cust = 'CUS' . $month . $year . '/' . 'C001' . '/' .  $maxid; 
          $fatkur = 'XX'  . $year . $month . $date . $idfatkur;
          $idreq = 'REQ'  . $year . $month . $date . $idreq;

          $stock  = DB::table('d_stock')->where('s_comp','3')->where('s_position','3')
            ->join('m_item', 'm_item.i_id', '=', 'd_stock.s_item')
            ->get();

          $ket = 'create';

        return view('/penjualan/POSgrosir/index',compact('id_cust','fatkur', 'idreq','stock','ket'));
    }

    public function edit_sales($id)
    { 
        $year = carbon::now()->format('y');
        $month = carbon::now()->format('m');
        $date = carbon::now()->format('d');

        //select max dari um_id dari table d_uangmuka
        $maxid = DB::Table('m_customer')->select('c_id')->max('c_id');
        $idfatkur = DB::Table('d_sales')->select('s_id')->max('s_id');
        $idreq = DB::table('d_transferitem')->select('ti_id')->max('ti_id');
        //untuk +1 nilai yang ada,, jika kosong maka maxid = 1 , 
        if ($maxid <= 0 || $maxid <= '') {
          $maxid  = 1;
        }else{
          $maxid += 1;
        }

        if ($idfatkur <= 0 || $idfatkur <= '') {
          $idfatkur  = 1;
        }else{
          $idfatkur += 1;
        }

        if ($idreq <= 0 || $idreq <= '') {
          $idreq  = 1;
        }else{
          $idreq += 1;
        }
        //jika kurang dari 100 maka maxid mimiliki 00 didepannya
        if ($maxid < 100) {
          $maxid = '00'.$maxid;
        }
          $id_cust = 'CUS' . $month . $year . '/' . 'C001' . '/' .  $maxid; 
          $fatkur = 'XX'  . $year . $month . $date . $idfatkur;
          $idreq = 'REQ'  . $year . $month . $date . $idreq;

          $stock  = DB::table('d_stock')->where('s_comp','3')->where('s_position','3')
            ->join('m_item', 'm_item.i_id', '=', 'd_stock.s_item')
            ->get();

          $edit = DB::table('d_sales')
          ->select('c_id','c_code','c_name','c_hp','c_address','c_type','c_insert','c_update',
                    'd_sales.s_id as sd_id','s_channel','s_date','s_note','s_staff','s_customer','s_gross',
                    's_disc_percent','s_disc_value','s_tax','s_net','s_status','d_sales.s_insert as sd_insert',
                    'd_sales.s_update as sd_update','sd_sales','sd_detailid','sd_item','sd_qty','sd_price','sd_disc_percent',
                    'sd_disc_value','sd_total','i_id','i_code','i_type','i_group','i_name','i_unit',
                    'i_price','i_price','i_insert','i_update','d_stock.s_id as d_id','s_comp',
                    's_position','s_item','s_qty','d_stock.s_insert as d_insert','d_stock.s_update as d_update')
          ->join('m_customer', 'm_customer.c_id', '=' , 'd_sales.s_customer')
          ->join('d_sales_dt','d_sales_dt.sd_sales','=','d_sales.s_id')
          ->join('m_item','m_item.i_id','=','d_sales_dt.sd_item')
          ->join('d_stock','d_stock.s_item','=','d_sales_dt.sd_item')
          ->where('s_comp','3')
          ->where('s_position','3')
          ->where('d_sales.s_id',$id)
          ->get();

          $ket = 'edit';

        return view('/penjualan/POSgrosir/index',compact('id_cust','fatkur', 'idreq','stock','edit','ket'));
    }

    public function detail(Request $request)
      {
            $detaliss = DB::table('d_sales_dt')
              ->select('*')
              ->join('d_sales', 'd_sales_dt.sd_sales', '=', 'd_sales.s_id' )
              ->join('m_item', 'm_item.i_id', '=' , 'd_sales_dt.sd_item')
              ->where('sd_sales','=',$request->x)->get();


          return view('/penjualan/POSgrosir/NotaPenjualan.detail',compact('detaliss'));
      }

    public function store(Request $request)
      {

        $maxid = DB::Table('m_customer')->select('c_id')->max('c_id');
         if ($maxid <= 0 || $maxid <= '') {
            $maxid  = 1;
          }else{
            $maxid += 1;
          }

          $tanggal = Carbon::createFromFormat('d/m/Y', $request->tgl_lahir, 'Asia/Jakarta');
          //dd($tanggal);

        $customer = DB::table('m_customer')
              ->insert([
                'c_id' => $maxid,
                'c_code' => $request->id_cus_ut,
                'c_name' => $request->nama_cus,
                'c_birthday' => $tanggal,
                'c_email' => $request->email,
                'c_hp' => $request->no_hp,
                'c_address' => $request->alamat,
                'c_type' =>'GR',
                'c_insert' => Carbon::now(),
                'c_update' => $request->c_update
              ]);
      }      

    public function autocomplete(Request $request)
       {
        $term = $request->term;

        $results = array();
        
        $queries = DB::table('m_customer')
          ->where('m_customer.c_name', 'LIKE', '%'.$term.'%')
          ->take(50)->get();
        
        if ($queries == null) {
          $results[] = [ 'id' => null, 'label' =>'tidak di temukan data terkait'];
        } else {
          foreach ($queries as $query) 
          {
            $results[] = [ 'id' => $query->c_id, 'label' => $query->c_name .'  '.$query->c_address, 'alamat' => $query->c_address.' '.$query->c_hp ];
          }
        } 
      return Response::json($results);
      }

    public function autocompleteitem(Request $request)
      {
        $term = $request->term;

        $results = array();
      
        $queries = DB::table('m_item')

          ->leftJoin('d_stock','m_item.i_id','=', 'd_stock.s_item')
          ->orWhere(function ($b) use ($term) {
                    $b->where('s_comp','3')
                      ->where('s_position','3');
                })
          ->where(function ($d) use ($term) {
                    $d->orWhere('m_item.i_type', 'LIKE', '%'.$term.'%')
                      ->orWhere('m_item.i_name', 'LIKE', '%'.$term.'%');
                })
          ->take(50)->get();

        if ($queries == null) {
          $results[] = [ 'i_id' => null, 'label' =>'tidak di temukan data terkait'];
        } else {
          foreach ($queries as $query) 
          {
            $results[] = [ 'id' => $query->i_id, 'label' => $query->i_code .' - '. $query->i_name,
            // ' ( Rp. '.number_format($query->i_hargajual,0,',','.').')'. 
                           'harga' => $query->i_price, 'kode' => $query->i_id, 'nama' => $query->i_name, 'satuan' => $query->i_unit, 's_qty'=>$query->s_qty ];
          }
        }
     
      return Response::json($results); 
      }

    public function sal_save_final(Request $request)
      { 

          $customer = DB::table('d_sales')
                ->insert([
                  's_id' =>$request->s_id,
                  's_channel' =>'GR',
                  's_date' =>date('Y-m-d',strtotime($request->s_date)),
                  's_note' =>$request->s_nota,
                  's_staff' =>$request->s_staff,
                  's_customer' => $request->id_cus,
                  's_disc_percent' => $request->s_disc_percent,
                  's_disc_value' => $request->s_disc_value,
                  's_gross' => ($this->konvertRp($request->s_gross)),
                  's_tax' => $request->s_pajak,
                  's_net' => ($this->konvertRp($request->s_gross)),
                  's_status' => 'FN',
                  's_insert' => Carbon::now(),
                  's_update' => $request->s_update
                
              ]);

              $s_id= DB::table('d_sales')->max('s_id');

              for ($i=0; $i < count($request->kode_item); $i++) 
              {

              $stokGrosir = DB::table('d_stock')
              ->where('s_comp','3')
              ->where('s_position','3')
              ->where('s_item',$request->kode_item[$i])->first(); 

              $d_sales_dt = DB::table('d_sales_dt')
                  ->insert([
                      'sd_sales'=>$s_id,
                      'sd_detailid'=>$i+1,
                      'sd_item'=>$request->kode_item[$i],
                      'sd_qty'=>$request->sd_qty[$i],
                      'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                      'sd_total'=>($this->konvertRp($request->hasil[$i])),
                      'sd_disc_percent'=>$request->sd_disc_percent[$i],
                      'sd_disc_value'=>$request->sd_disc_value[$i]

                ]);

              $stokBaru = $stokGrosir->s_qty - $request->sd_qty[$i];

              DB::table("d_stock")
              ->where('s_comp','3')
              ->where('s_position','3')
              ->where("s_id", $stokGrosir->s_id)
              ->update(['s_qty' => $stokBaru]);
              }
      }

    public function sal_save_draft(Request $request)
      {

            $customer = DB::table('d_sales')
                ->insert([
                  's_id' =>$request->s_id,
                  's_channel' =>'GR',
                  's_date' =>date('Y-m-d',strtotime($request->s_date)),
                  's_note' =>$request->s_nota,
                  's_staff' =>$request->s_staff,
                  's_customer' => $request->id_cus,
                  's_disc_percent' => $request->s_disc_percent,
                  's_disc_value' => $request->s_disc_value,
                  's_gross' => ($this->konvertRp($request->s_gross)),
                  's_tax' => $request->s_pajak,
                  's_net' => ($this->konvertRp($request->s_gross)),
                  's_status' => 'DR',
                  's_insert' => Carbon::now(),
                  's_update' => $request->s_update
                  
                ]);

                $s_id= DB::table('d_sales')->max('s_id');

                for ($i=0; $i < count($request->kode_item); $i++) 
                { 

                $d_sales_dt = DB::table('d_sales_dt')
                    ->insert([
                      'sd_sales'=>$s_id,
                      'sd_detailid'=>$i+1,
                      'sd_item'=>$request->kode_item[$i],
                      'sd_qty'=>$request->sd_qty[$i],
                      'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                      'sd_total'=>($this->konvertRp($request->hasil[$i])),
                      'sd_disc_percent'=>$request->sd_disc_percent[$i],
                      'sd_disc_value'=>$request->sd_disc_value[$i]
                  ]);
                  
                }

      }

    public function sal_save_onProgres(Request $request)
      {

            $customer = DB::table('d_sales')
                ->insert([
                  's_id' =>$request->s_id,
                  's_channel' =>'GR',
                  's_date' =>date('Y-m-d',strtotime($request->s_date)),
                  's_note' =>$request->s_nota,
                  's_staff' =>$request->s_staff,
                  's_customer' => $request->id_cus,
                  's_disc_percent' => $request->s_disc_percent,
                  's_disc_value' => $request->s_disc_value,
                  's_gross' => ($this->konvertRp($request->s_gross)),
                  's_tax' => $request->s_pajak,
                  's_net' => ($this->konvertRp($request->s_gross)),
                  's_status' => 'PR',
                  's_insert' => Carbon::now(),
                  's_update' => $request->s_update            
                ]);

                $s_id= DB::table('d_sales')->max('s_id');

                for ($i=0; $i < count($request->kode_item); $i++) 
                { 


                $stokRetail = DB::table('d_stock')
                ->where('s_comp','3')
                ->where('s_position','3')
                ->where('s_item',$request->kode_item[$i])->first();

                $d_sales_dt = DB::table('d_sales_dt')
                    ->insert([
                      'sd_sales'=>$s_id,
                      'sd_detailid'=>$i+1,
                      'sd_item'=>$request->kode_item[$i],
                      'sd_qty'=>$request->sd_qty[$i],
                      'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                      'sd_total'=>($this->konvertRp($request->hasil[$i])),
                      'sd_disc_percent'=>$request->sd_disc_percent[$i],
                      'sd_disc_value'=>$request->sd_disc_value[$i]
                  ]);

               $stokBaru = $stokRetail->s_qty - $request->sd_qty[$i];

                DB::table("d_stock")
                ->where('s_comp','3')
                ->where('s_position','3')
                ->where("s_id", $stokRetail->s_id)
                  ->update(['s_qty' => $stokBaru]);
                  
                }

      }

     public function sal_save_finalUpdate(Request $request)
      {
        //dd($request->all());
        return DB::transaction(function () use ($request) {

           $s_id=$request->sd_id;
            
           $m = DB::table('d_sales')->where('s_id',$request->sd_id)->first();

           if ($m->s_status =='DR') {

              DB::table('d_sales')->where('s_id',$request->sd_id)
                ->update([
                  's_channel' =>'GR',
                  's_date' =>date('Y-m-d',strtotime($request->s_date)),
                  's_note' =>$request->s_nota,
                  's_staff' =>$request->s_staff,
                  's_customer' => $request->id_cus,
                  's_disc_percent' => $request->s_disc_percent,
                  's_disc_value' => $request->s_disc_value,
                  's_gross' => ($this->konvertRp($request->s_gross)),
                  's_tax' => $request->s_pajak,
                  's_net' => ($this->konvertRp($request->s_gross)),
                  's_status' => 'FN',
                  's_insert' => Carbon::now(),
                  's_update' => $request->s_update
                  
                ]);

                  for ($i=0; $i < count($request->kode_item); $i++) 
                {

                  $stokGrosir = DB::table('d_stock')
                    ->where('s_comp','3')
                    ->where('s_position','3')
                    ->where('s_item',$request->kode_item[$i])->first(); 

                
                if ($request->sd_sales[$i] == null ){ 
                  $sd_detailid=DB::table('d_sales_dt')->where('sd_sales',$s_id)->max('sd_detailid');
                  $d_sales_dt = DB::table('d_sales_dt')
                    ->where('sd_sales',$s_id)
                      ->insert([
                        'sd_sales' =>$s_id,
                        'sd_detailid'=>$sd_detailid+1,
                        'sd_qty'=>$request->sd_qty[$i],
                        'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                        'sd_item'=>$request->kode_item[$i],
                        'sd_disc_percent'=>$request->sd_disc_percent[$i],
                        'sd_disc_value'=>$request->sd_disc_value[$i],
                        'sd_total'=>($this->konvertRp($request->hasil[$i]))

                ]);
                      
                      
                }else if ($m->s_status =='PR'){

                 $d_sales_dt = DB::table('d_sales_dt')
                    ->where('sd_sales',$s_id)
                    ->where('sd_detailid',$request->sd_detailid[$i])
                      ->update([                        
                        'sd_item'=>$request->kode_item[$i],
                        'sd_qty'=>$request->sd_qty[$i],
                        'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                        'sd_disc_percent'=>$request->sd_disc_percent[$i],
                        'sd_disc_value'=>$request->sd_disc_value[$i],
                        'sd_total'=>($this->konvertRp($request->hasil[$i]))
                ]);

                $stokBaru = $stokGrosir->s_qty - $request->sd_qty[$i];

                DB::table("d_stock")
                ->where('s_comp','3')
                ->where('s_position','3')
                ->where("s_id", $stokGrosir->s_id)
                ->update(['s_qty' => $stokBaru]);
                
                }
              }
           }else{
            DB::table('d_sales')->where('s_id',$request->sd_id)
                ->update([
                  's_channel' =>'GR',
                  's_date' =>date('Y-m-d',strtotime($request->s_date)),
                  's_note' =>$request->s_nota,
                  's_staff' =>$request->s_staff,
                  's_customer' => $request->id_cus,
                  's_disc_percent' => $request->s_disc_percent,
                  's_disc_value' => $request->s_disc_value,
                  's_gross' => ($this->konvertRp($request->s_gross)),
                  's_tax' => $request->s_pajak,
                  's_net' => ($this->konvertRp($request->s_gross)),
                  's_status' => 'FN',
                  's_insert' => Carbon::now(),
                  's_update' => $request->s_update
                  
                ]);

                  for ($i=0; $i < count($request->kode_item); $i++) 
                {

                  $stokGrosir = DB::table('d_stock')
                    ->where('s_comp','3')
                    ->where('s_position','3')
                    ->where('s_item',$request->kode_item[$i])->first(); 

                
                if ($request->sd_sales[$i] == null ){ 
                  $sd_detailid=DB::table('d_sales_dt')->where('sd_sales',$s_id)->max('sd_detailid');
                  $d_sales_dt = DB::table('d_sales_dt')
                    ->where('sd_sales',$s_id)
                      ->insert([
                        'sd_sales' =>$s_id,
                        'sd_detailid'=>$sd_detailid+1,
                        'sd_qty'=>$request->sd_qty[$i],
                        'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                        'sd_item'=>$request->kode_item[$i],
                        'sd_disc_percent'=>$request->sd_disc_percent[$i],
                        'sd_disc_value'=>$request->sd_disc_value[$i],
                        'sd_total'=>($this->konvertRp($request->hasil[$i]))

                ]);
                      
                      
                }else{
                 $d_sales_dt = DB::table('d_sales_dt')
                    ->where('sd_sales',$s_id)
                    ->where('sd_detailid',$request->sd_detailid[$i])
                      ->update([                        
                        'sd_item'=>$request->kode_item[$i],
                        'sd_qty'=>$request->sd_qty[$i],
                        'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                        'sd_disc_percent'=>$request->sd_disc_percent[$i],
                        'sd_disc_value'=>$request->sd_disc_value[$i],
                        'sd_total'=>($this->konvertRp($request->hasil[$i]))
                ]);

                $stokBaru = $stokGrosir->s_qty - $request->sd_qty[$i];

                DB::table("d_stock")
                ->where('s_comp','3')
                ->where('s_position','3')
                ->where("s_id", $stokGrosir->s_id)
                ->update(['s_qty' => $stokBaru]);
                
                }
              }
           }
        });

      }


     public function sal_save_onProgresUpdate(Request $request)
      {
        //dd($request->all());
        return DB::transaction(function () use ($request) {

           $s_id=$request->sd_id;
            
           $m = DB::table('d_sales')->where('s_id',$request->sd_id)->first();

           if ($m->s_status =='DR') {

              DB::table('d_sales')->where('s_id',$request->sd_id)
                ->update([
                  's_channel' =>'GR',
                  's_date' =>date('Y-m-d',strtotime($request->s_date)),
                  's_note' =>$request->s_nota,
                  's_staff' =>$request->s_staff,
                  's_customer' => $request->id_cus,
                  's_disc_percent' => $request->s_disc_percent,
                  's_disc_value' => $request->s_disc_value,
                  's_gross' => ($this->konvertRp($request->s_gross)),
                  's_tax' => $request->s_pajak,
                  's_net' => ($this->konvertRp($request->s_gross)),
                  's_status' => 'PR',
                  's_insert' => Carbon::now(),
                  's_update' => $request->s_update
                  
                ]);

                  for ($i=0; $i < count($request->kode_item); $i++) 
                {

                  $stokGrosir = DB::table('d_stock')
                    ->where('s_comp','3')
                    ->where('s_position','3')
                    ->where('s_item',$request->kode_item[$i])->first(); 

                
                if ($request->sd_sales[$i] == null ){ 
                  $sd_detailid=DB::table('d_sales_dt')->where('sd_sales',$s_id)->max('sd_detailid');
                  $d_sales_dt = DB::table('d_sales_dt')
                    ->where('sd_sales',$s_id)
                      ->insert([
                        'sd_sales' =>$s_id,
                        'sd_detailid'=>$sd_detailid+1,
                        'sd_qty'=>$request->sd_qty[$i],
                        'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                        'sd_item'=>$request->kode_item[$i],
                        'sd_disc_percent'=>$request->sd_disc_percent[$i],
                        'sd_disc_value'=>$request->sd_disc_value[$i],
                        'sd_total'=>($this->konvertRp($request->hasil[$i]))

                ]);
                      
                      
                }else{
                 $d_sales_dt = DB::table('d_sales_dt')
                    ->where('sd_sales',$s_id)
                    ->where('sd_detailid',$request->sd_detailid[$i])
                      ->update([                        
                        'sd_item'=>$request->kode_item[$i],
                        'sd_qty'=>$request->sd_qty[$i],
                        'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                        'sd_disc_percent'=>$request->sd_disc_percent[$i],
                        'sd_disc_value'=>$request->sd_disc_value[$i],
                        'sd_total'=>($this->konvertRp($request->hasil[$i]))
                ]);

                $stokBaru = $stokGrosir->s_qty - $request->sd_qty[$i];

                DB::table("d_stock")
                ->where('s_comp','3')
                ->where('s_position','3')
                ->where("s_id", $stokGrosir->s_id)
                ->update(['s_qty' => $stokBaru]);
                
                }
              }
           }
        });

      }

     public function sal_save_draftUpdate(Request $request)
      {
        //dd($request->all());
        return DB::transaction(function () use ($request) {

           $s_id=$request->sd_id;
            
           $m = DB::table('d_sales')->where('s_id',$request->sd_id)->first();

           if ($m->s_status =='DR') {

              DB::table('d_sales')->where('s_id',$request->sd_id)
                ->update([
                  's_channel' =>'GR',
                  's_date' =>date('Y-m-d',strtotime($request->s_date)),
                  's_note' =>$request->s_nota,
                  's_staff' =>$request->s_staff,
                  's_customer' => $request->id_cus,
                  's_disc_percent' => $request->s_disc_percent,
                  's_disc_value' => $request->s_disc_value,
                  's_gross' => ($this->konvertRp($request->s_gross)),
                  's_tax' => $request->s_pajak,
                  's_net' => ($this->konvertRp($request->s_gross)),
                  's_status' => 'DR',
                  's_insert' => Carbon::now(),
                  's_update' => $request->s_update
                  
                ]);

                  for ($i=0; $i < count($request->kode_item); $i++) 
                {
                
                if ($request->sd_sales[$i] == null ){ 
                  $sd_detailid=DB::table('d_sales_dt')->where('sd_sales',$s_id)->max('sd_detailid');
                  $d_sales_dt = DB::table('d_sales_dt')
                    ->where('sd_sales',$s_id)
                      ->insert([
                        'sd_sales' =>$s_id,
                        'sd_detailid'=>$sd_detailid+1,
                        'sd_qty'=>$request->sd_qty[$i],
                        'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                        'sd_item'=>$request->kode_item[$i],
                        'sd_disc_percent'=>$request->sd_disc_percent[$i],
                        'sd_disc_value'=>$request->sd_disc_value[$i],
                        'sd_total'=>($this->konvertRp($request->hasil[$i]))

                ]);
                      
                      
                }else{
                 $d_sales_dt = DB::table('d_sales_dt')
                    ->where('sd_sales',$s_id)
                    ->where('sd_detailid',$request->sd_detailid[$i])
                      ->update([                        
                        'sd_item'=>$request->kode_item[$i],
                        'sd_qty'=>$request->sd_qty[$i],
                        'sd_price'=>($this->konvertRp($request->harga_item[$i])),
                        'sd_disc_percent'=>$request->sd_disc_percent[$i],
                        'sd_disc_value'=>$request->sd_disc_value[$i],
                        'sd_total'=>($this->konvertRp($request->hasil[$i]))
                ]);

                }
              }
           }
        });

      }
     
    public function distroy($id)
      {
         DB::table('d_sales')->where('s_id',$id)->where('s_status','DR')->delete();

       return redirect('/penjualan/POSgrosir/index');
      }

    public function konvertRp($value)
      {
      $value = str_replace(['Rp', '\\', '.', ' '], '', $value);
      return str_replace(',', '.', $value);
      }

    public function tambahItemReq(Request $request)
      {
        
        return DB::transaction(function() use ($request)
        {

        for ($i=0; $i < count($request->kodeItemReq); $i++) 
          { 
              $stokGrosir = DB::table('d_stock')
                  ->where('s_comp','3')
                  ->where('s_position','3')
                  ->where('s_item',$request->kodeItemReq[$i])->first();

              $s_id= DB::table('d_stock')->max('s_id');

              DB::table('d_stock')
              ->insert([
                  's_id'=>$s_id+1,
                  's_comp'=>'11',
                  's_position'=>'3',
                  's_item'=>$request->kodeItemReq[$i],
                  's_qty'=>$request->tambahItemReq[$i],
                  // 's_insert' => $request->s_insert[$i],
                  // 's_update' => $request->s_update[$i]
              ]);

              DB::table('d_transferitem_dt')
                ->update([
                      'tidt_id'=>$request->rd_request[$i],
                      'tidt_detail'=>$request->rd_detail[$i],
                      'tidt_item'=>$request->rd_item[$i],
                      'tidt_qty'=>$request->rd_qty[$i],
                      'tidt_qty_appr'=>$request->tambahItemReq[$i]
                ]);

              $stokBaru = $stokGrosir->s_qty - $request->tambahItemReq[$i];

              DB::table("d_stock")
                ->where('s_comp','3')
                ->where('s_position','3')
                ->where("s_id", $stokGrosir->s_id)
                ->update(['s_qty' => $stokBaru]);
            }
      });
      }

    function getTanggal($tgl1,$tgl2){

      $y = substr($tgl1, -4);
      $m = substr($tgl1, -7,-5);
      $d = substr($tgl1,0,2);
       $tgll = $y.'-'.$m.'-'.$d;

      $y2 = substr($tgl2, -4);
      $m2 = substr($tgl2, -7,-5);
      $d2 = substr($tgl2,0,2);
        $tgl2 = $y2.'-'.$m2.'-'.$d2;

      $detalis = DB::table('d_sales')
        ->join('m_customer','m_customer.c_id','=','d_sales.s_customer')
        ->where('s_channel','GR')
        ->where('s_date','>=',$tgll)
        ->where('s_date','<=',$tgl2)
        ->get();

      return view('/penjualan/POSgrosir/NotaPenjualan.dt_notaJual',compact('detalis'));
      }

    function getTanggalJual($tgl1,$tgl2){

      $y = substr($tgl1, -4);
      $m = substr($tgl1, -7,-5);
      $d = substr($tgl1,0,2);
       $tgll = $y.'-'.$m.'-'.$d;

      $y2 = substr($tgl2, -4);
      $m2 = substr($tgl2, -7,-5);
      $d2 = substr($tgl2,0,2);
        $tgl2 = $y2.'-'.$m2.'-'.$d2;

      $leagues = DB::table('d_sales_dt')
        ->select('sd_item','s_date','i_name','i_type','i_group', DB::raw("sum(sd_qty) as jumlah"))
        ->join('m_item', 'm_item.i_id', '=' , 'd_sales_dt.sd_item')
        ->join('d_sales', 'd_sales.s_id', '=' , 'd_sales_dt.sd_sales')
        ->where('s_channel','GR')
        ->where('s_status','FN')
        ->where('s_date','>=',$tgll)
        ->where('s_date','<=',$tgl2)
        ->groupBy('sd_item','i_name')
        ->get();

    return view('/penjualan/POSgrosir/ItemPenjualan.Data_JualGrosir',compact('leagues'));
    }

}
