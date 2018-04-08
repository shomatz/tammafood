<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\d_transferItem;
use App\d_transferItemDt;
use DB;
use Validator;
class transferItemController extends Controller
{
    public function index(){
        return view('transfer.index');
    }
     public function dataTransfer(){
        $transferItem=d_transferItem::paginate();
        return view('transfer.table-transfer',compact('transferItem'));
    }
    public function simpanTransfer(Request $request)
    {
    return DB::transaction(function () use ($request) {
    	
    	$ti_id=d_transferItem::max('ti_id')+1;
    	d_transferItem::create([
    				'ti_id'			=>$ti_id,
    				'ti_time'		=>date('Y-m-d',strtotime($request->ri_tanggal)), 
    				'ti_code'		=>$request->ri_nomor, 
    				'ti_order'		=>'RT',
    				//'ti_orderstaff'	=>,
    				'ti_note'		=>$request->ri_keterangan,
    				
    	]);
    
    	for ($i=0; $i <count($request->kode_item) ; $i++) { 
    			$tidt_id=d_transferItemDt::where('tidt_id',$ti_id)->max('tidt_detail')+1;
    			 d_transferItemDt::create([
    				'tidt_id'			=>$ti_id,
    				'tidt_detail'		=>$tidt_id, 
    				'tidt_item'		=>$request->kode_item[$i], 
    				'tidt_qty'		=>$request->sd_qty[$i]
    			]);
    	}

    	$data=['status'=>'sukses'];    	
    	return json_encode($data);
       
    });

    }

    public function editTransfer(Request $request,$id)
    {
        
        $transferItem=d_transferItem::where('ti_id',$id)->first();
        $transferItemDt=d_transferItemDt::
                        join('m_item','d_transferitem_dt.tidt_item','=','m_item.i_id')->
                        where('tidt_id',$id)->get();
                        

        return view('transfer.edit-transfer',compact('transferItem','transferItemDt'));

    }

    public function updateTransfer(Request $request)
    {
    	DB::transaction(function () use ($request) {
    		/*$rules = [
                'ti_code'=> 'unique:d_transferItem,ti_code,'.$request->ri_nomor,
                ];   

            $validator = Validator::make($request->all(), $rules);
            DD($validator);
            if ($validator->fails()) {
                return response()->json([
                            'status' => 'error1',
                            'data' => $validator
                ]);
            }*/
    	$ti_id=d_transferItem::max('ti_id')+1;
    	d_transferItem::create([
    				'ti_id'			=>$ti_id,
    				'ti_time'		=>date('Y-m-d',strtotime($request->ri_tanggal)), 
    				'ti_code'		=>$request->ri_nomor, 
    				'ti_order'		=>'RT',
    				//'ti_orderstaff'	=>,
    				'ti_note'		=>$request->ri_keterangan,
    				
    	]);
    
    	for ($i=0; $i <count($request->kode_item) ; $i++) { 
    			$tidt_id=d_transferItemDt::where('tidt_id',$ti_id)->max('tidt_detail')+1;
    			 d_transferItemDt::create([
    				'tidt_id'			=>$ti_id,
    				'tidt_detail'		=>$tidt_id, 
    				'tidt_item'		=>$request->kode_item[$i], 
    				'tidt_qty'		=>$request->sd_qty[$i]
    			]);
    	}
       
    });

    }



    public function indexGrosir(){
        return view('transfer.index-grosir');
    }
   
    public function grosirTransfer(){
        $transferItem=d_transferItem::paginate();
        return view('transfer.grosil-transfer',compact('transferItem'));
    }
    public function approveTransfer($id){

        $transferItem=d_transferItem::where('ti_id',$id)->first();
        $transferItemDt=d_transferItemDt::
                        join('m_item','d_transferitem_dt.tidt_item','=','m_item.i_id')->
                        where('tidt_id',$id)->get();

        return view('transfer.approve-transfer',compact('transferItem','transferItemDt'));
    }

    public function simpanApprove(Request $request){

            for ($i=0; $i <count($request->tidt_id) ; $i++) { 
                $transferItemDt=d_transferItemDt::                        
                                where('tidt_id',$request->tidt_id[$i])->
                                where('tidt_id',$request->tidt_id[$i]);

                $transferItemDt->update([
                    'tidt_qty_appr'=>$request->qtyAppr[$i],
                    'tidt_apprtime'=>date('Y-m-d g:i:s'),
                    'tidt_qty_send'=>$request->qtySend[$i],
                    'tidt_sendtime'=>date('Y-m-d g:i:s'),
                ]);
            }
               
            
           
    }
}
