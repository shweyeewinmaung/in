<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Staff;
use App\Jobtitle;
use App\Supplier;
use App\Store;
use App\Comment;
use App\Itemname;
use App\Transfer; 
use App\Item;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use Carbon\Carbon;

class TransferController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemtransfer"))
       {
        $storenameslistsall=Store::all();
        $storenameslists = Store::orderBy('id', 'desc')->paginate(30);         
        $s="";
        return view('admin.transfers.list',compact('storenameslistsall','storenameslists','s'));
       }abort(404,"Sorry");    
    }
    public function searchlist(Request $request)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemtransfer"))
       {
            $s = $request->search;
            $storenameslistsall=Store::where('name', 'LIKE', "%{$s}%")
                 ->orWhere('address', 'LIKE', "%{$s}%");

            $storenameslists = Store::where('name', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orderBy('id','asc')
                ->paginate(30);
           
                
            return view('admin.transfers.list',compact('storenameslistsall','storenameslists','s'));
        }abort(404,"Sorry");
    }
    public function transferviewalldetail(Request $request,$id,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemtransfer"))
      {
        $storename=Store::where('id',$id)->firstOrFail();
        $transfers=Transfer::where('store_id',$storename['id'])
        ->where('status','=','confirm')
        ->get();

        // $server_items=array();
        // $items=array();
        $itemnamedatas=array();
        $itemsarrays=array();
        $itemdatas=array();
        foreach($transfers as $transfer)
        {
             $transferdata=Transfer::whereId($transfer['id'])->first();
             $itembyname=$transferdata->items()->get();
             array_push($itemnamedatas,$itembyname);
            // // $server_item=$serverdata->items()->get();
            //  $itembyname=$streetdata->items()->pluck('itemname_id')->toArray();
            //  $itembyname_counts=array_count_values($itembyname);
            //  array_push($itemnamedatas,$itembyname_counts);
           //  array_push($server_items,$server_item); 
        }
        foreach($itemnamedatas as $k=>$itemnamedata)
        {
             foreach($itemnamedata as $kk=>$itemdata)
             {
                array_push($itemdatas,$itemdata['id']);
             }
        }
        $itemnamebyitems =Item::whereIn('id', $itemdatas)->pluck('itemname_id')->toArray();
        $itemnamebyitem_counts=array_count_values($itemnamebyitems);
      
        if($itemnamedatas == null || $itemnamedatas == "" || $itemnamedatas ==0)
        {
           $itemnamedatas == null;
           return view('admin.streets.alldetail',compact('name','streetname','itemsarrays'));
        }
        else
        {
          foreach($itemnamebyitem_counts as $k=>$itemnamebyitem_count)
          {
              $itemname=Itemname::where('id',$k)->first();
              $catname=$itemname->category->title;
              $catmac=$itemname->category->mac;
              $catserial=$itemname->category->serial;

              $items = DB::table('items')
                      ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                      ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('transfers.store_id', '=', $storename->id)
                      ->get();
              $items_damage = DB::table('items')
                      ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                      ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('items.store_id', '=', $storename->id)
                      ->where('items.damage_qty', '=', 1)                       
                      ->get();
             if($catserial == 1 || $catmac == 1)
             {
                     $storedItem = ['id'=>$k, 'itemname'=>$itemname->name,'categoryname'=>$catname,'count' =>$itemnamebyitem_count,'itemswithsandm'=> $items,'itemsbycount'=>"",'itemsbycountwithcount'=>""];
              
                    array_push($itemsarrays,$storedItem);
             }
             else
            {
              $storedItem = ['id'=>$k, 'itemname'=>$itemname->name,'categoryname'=>$catname,'count' =>$itemnamebyitem_count,'itemswithsandm'=> "",'itemsbycount'=>$items,'itemsbycountwithcount'=>$items_damage->count()];
              
              array_push($itemsarrays,$storedItem); 
            }                 

          }
        
          
        return view('admin.transfers.alldetail',compact('id','name','storename','itemsarrays'));
        }
      }abort(404,"Sorry"); 
       
    }

     public function itembyitemtransfercountupdatealldetail(Request $request,$itemname_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemtransfer"))
      {    
          $items = DB::table('items')
                ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                // ->where('servers.id', $server_id)
                ->orderBy('items.id','asc')
                ->get();
                  
          $items_count = $items->count();
           
          if($request->get('countdamage_qty') == null || $request->get('countdamage_qty') == 0)
          {
              return redirect()->back()->with(['errorstatus'=>'Damage Qty must be Filled!!!']);
          }
         elseif($request->get('countdamage_qty') > $items_count)
         {
            return redirect()->back()->with(['errorstatus'=>'Damage Qty must not be larger than Item!!!']);
         }
        else
        {
            $itemsbycounts = DB::table('items')
                ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                //->where('servers.id', $server_id)
                ->orderBy('items.id','asc')
                ->take($request->get('countdamage_qty'))
                ->get();
           // dd($itemsbycounts);
           foreach($itemsbycounts as $k=>$item)
           {           
            $itemupdate=Item::whereId($item->item_id)->firstOrFail();
            $itemupdate->damage_qty='1';
            $itemupdate->damage_reason= $request->get('damage_reason');
            $itemupdate->updated_at=Carbon::now()->timestamp;
            $itemupdate->update();
           }
           Comment::create([
            'content' => Auth::user()->name ." updated Item Damage Count for".$itemname_id."(".$request->countdamage_qty.")" ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$itemname_id,
            'commendable_type' => "items"
        
        ]);
        return redirect()->back()->with(['status'=>'Item Name '.$itemname_id.'('.$request->countdamage_qty.') Has Been Updated']);
       } 
      }abort(404,"Sorry");     
    }

    
    public function itembyitemtransferredocountupdatealldetail(Request $request,$itemname_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemtransfer"))
      {
        $items = DB::table('items')
                ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 1)
               // ->where('servers.id', $server_id)
                ->orderBy('items.id','asc')->get();
         
        $items_count = $items->count();
          
        if($request->get('countredodamage_qty') == null || $request->get('countredodamage_qty') == 0)
        {
            return redirect()->back()->with(['errorstatus'=>'Damage Qty must be Filled!!!']);
        }
        elseif($request->get('countredodamage_qty') > $items_count)
        {
            return redirect()->back()->with(['errorstatus'=>'Redo Damage Qty must not be larger than Total Item!!!']);
        }
        else
        {
            $itemsbycounts = DB::table('items')
                ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=',1)
                //->where('servers.id', $server_id)
                ->orderBy('items.id','asc')
                ->take($request->get('countredodamage_qty'))
                ->get();
           foreach($itemsbycounts as $k=>$item)
           {           
            $itemupdate=Item::whereId($item->item_id)->firstOrFail();
            $itemupdate->damage_qty='0';
            $itemupdate->damage_reason= $request->get('redodamage_reason');
            $itemupdate->updated_at=Carbon::now()->timestamp;
            $itemupdate->update();
           }
           Comment::create([
            'content' => Auth::user()->name ." updated Redo Item Damage Count for ".$itemname_id." (".$request->countdamage_qty.")" ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$itemname_id,
            'commendable_type' => "items"
        
        ]);
        return redirect()->back()->with(['status'=>'Item Name '.$itemname_id.' ('.$request->countdamage_qty.') Has Been Updated']);
        }     
       

      }abort(404,"Sorry");     
    }

    public function transferviewshow(Request $request,$id,$name)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemtransfer"))
       {
        $store_namesdatas=Store::where('id',$id)->firstOrFail();
        $transferbystore_names=Transfer::where('store_id',$store_namesdatas['id'])->orderBy('id', 'desc')->paginate(50);
        $transferbystore_namesall=Transfer::where('store_id',$store_namesdatas['id'])->orderBy('id', 'desc')->get();
        $searchdetail="";
         
        return view('admin.transfers.transferviewshow',compact('id','name','transferbystore_names','transferbystore_namesall','searchdetail'));  
        }abort(404,"Sorry");     
    }

    public function searchviewshow(Request $request,$id,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemstreet"))
      {
        $searchdetail = $request->searchdetail;
         $store_namesdatas=Store::where('id',$id)->firstOrFail();

        if($searchdetail == null)
        {
            $transferbystore_names=Transfer::where('store_id',$store_namesdatas['id'])
            ->orderBy('id', 'desc')
            ->paginate(50);

            $transferbystore_namesall=Transfer::where('store_id',$store_namesdatas['id'])
            ->orderBy('id', 'desc');
        }
        else
        {
            $transferbystore_names=Transfer::where('store_id',$store_namesdatas['id'])
            ->where('transfer_number', 'LIKE', "%{$searchdetail}%")
            ->orWhereHas('staff', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhereHas('storefrom', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
             ->orWhereHas('storeto', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhere('status', 'LIKE', "%{$searchdetail}%")
            ->orWhere('content', 'LIKE', "%{$searchdetail}%")
            ->orderBy('id', 'desc')
            ->paginate(50);

             $transferbystore_namesall=Transfer::where('store_id',$store_namesdatas['id'])
            ->where('transfer_number', 'LIKE', "%{$searchdetail}%")
             ->orWhereHas('staff', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhereHas('storefrom', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
             ->orWhereHas('storeto', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhere('status', 'LIKE', "%{$searchdetail}%")
            ->orWhere('content', 'LIKE', "%{$searchdetail}%")
            ->orderBy('id', 'desc');
        }

        return view('admin.transfers.transferviewshow',compact('id','name','transferbystore_names','transferbystore_namesall','searchdetail'));  
       }abort(404,"Sorry");                
    }
    
    public function transfirmdetailconfirm(Request $request,$id,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("confirm-itemtransfer"))
      {
        $transfer = Transfer::whereId($id)->first();
        $itemsfrompivot=$transfer->items()->get();
        foreach($itemsfrompivot as $k=>$v)
        {
          $itemupdate = Item::whereId($v->id)->first();

          $itemcreate=Item::create([
                  'itemname_id' => $itemupdate->itemname_id,
                  'model' => $itemupdate->model,
                  'mac' => $itemupdate->mac,
                  'serial_number' => $itemupdate->serial_number,
                  'voucher_id' => $itemupdate->voucher_id,
                  'store_id' => $transfer->to,
                  'unit_price' => $itemupdate->unit_price,
                  'amount'=> $itemupdate->amount,                             
                  'total_qty' =>$itemupdate->total_qty,
                  'qty' => 1,
                  'used_qty' => 0,
                  'transfer_qty' => 0,     
                  'damage_qty' => 0,
                  'damage_reason' => $itemupdate->damage_reason,
                  'category_id' => $itemupdate->category_id
                  ]);
        }
       // dd($item);
       // $street=Street::whereId($id)->firstOrFail();
       $transfer->confirm_user_id=Auth::user()->id;
       $transfer->status='confirm';
       $transfer->updated_at=Carbon::now()->timestamp;

        Comment::create([
            'content' => Auth::user()->name ." Confirm  Transfer for  ".$name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "transfers"
        
        ]);
        $transfer->update();
       
         return redirect()->back()->with(['status'=>'Transfer for '.$name.' Has Been Confirm']);
      }abort(404,"Sorry");              
    }

    public function transferviewdetailshow(Request $request,$id,$name,$transfer_number)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemtransfer"))
      {
        $transferdetail=Transfer::where('transfer_number',$transfer_number)->first();
        
        // $serveritemsbydetails=$serverdetail->items()->paginate(30); 
        // $serverbyitemsarrays=$serveritemsbydetails->pluck('id')->toArray();
       
       // $itembydetails=$serverdetail->items()->groupBy('itemname_id')->paginate(1); 
        //dd($aas);
        $itembyname=$transferdetail->items()->pluck('itemname_id')->toArray();
        $itembyname_counts=array_count_values($itembyname);

        $itemsarrays=array();
        
        foreach($itembyname_counts as $k=>$itembyname_count)
        { 
              $itemname=Itemname::where('id',$k)->first();
              $catname=$itemname->category->title;
              $catmac=$itemname->category->mac;
              $catserial=$itemname->category->serial;


            $items = DB::table('items')
                      ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                      ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('transfers.id', $transferdetail['id'])->get();

            $items_damage = DB::table('items')
                      ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                      ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')          
                      ->where('items.itemname_id', '=', $k)
                      ->where('items.damage_qty', '=', 1)
                      ->where('transfers.id', $transferdetail['id'])->get();
          
 
            if($catserial == 1 || $catmac == 1)
            {
                   $storedItem = ['id'=>$k, 'itemname'=>$itemname->name,'categoryname'=>$catname,'count' =>$itembyname_count,'itemswithsandm'=> $items,'itemsbycount'=>"",'itemsbycountwithcount'=>""];
            
                  array_push($itemsarrays,$storedItem);
            }
            else
              {
                $storedItem = ['id'=>$k, 'itemname'=>$itemname->name,'categoryname'=>$catname,'count' =>$itembyname_count,'itemswithsandm'=> "",'itemsbycount'=>$items,'itemsbycountwithcount'=>$items_damage->count()];
            
                  array_push($itemsarrays,$storedItem); 
              }                      
        }
       
        return view('admin.transfers.transferviewdetailshow',compact('id','name','transferdetail','transfer_number','itemsarrays'));
         
      }abort(404,"Sorry");     
    }
    
    public function itembyitemtransferupdate(Request $request,$id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemtransfer"))
      {
         $item=Item::whereId($id)->firstOrFail();
         $item->model=$request->get('model');
         $item->mac=$request->get('model');
         $item->serial_number=$request->get('serial_number');
         $item->damage_qty=$request->get('damage_qty');
         $item->damage_reason=$request->get('damage_reason');
         $item->updated_at=Carbon::now()->timestamp;
         $item->update();

         Comment::create([
            'content' => Auth::user()->name ." updated Item ".$id ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id,
            'commendable_type' => "items"
        
        ]);
        return redirect()->back()->with(['status'=>' Item Has Been Updated']);

      }abort(404,"Sorry");     
    }
    
    public function itembyitemtransfercountupdate(Request $request,$itemname_id,$transfer_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemtransfer"))
      {         
        $items = DB::table('items')
                ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                ->where('transfers.id', $transfer_id)
                ->orderBy('items.id','asc')
                ->get();
        $items_count = $items->count();

        if($request->get('countdamage_qty') == null || $request->get('countdamage_qty') == 0)
        {
            return redirect()->back()->with(['errorstatus'=>'Damage Qty must be Filled!!!']);
        }
        elseif($request->get('countdamage_qty') > $items_count)
        {
            return redirect()->back()->with(['errorstatus'=>'Damage Qty must not be larger than Item!!!']);
        }
        else
        {
            $itemsbycounts = DB::table('items')
                ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                ->where('transfers.id', $transfer_id)
                ->orderBy('items.id','asc')
                ->take($request->get('countdamage_qty'))
                ->get();
           // dd($itemsbycounts);
           foreach($itemsbycounts as $k=>$item)
           {           
            $itemupdate=Item::whereId($item->item_id)->firstOrFail();
            $itemupdate->damage_qty='1';
            $itemupdate->damage_reason= $request->get('damage_reason');
            $itemupdate->updated_at=Carbon::now()->timestamp;
            $itemupdate->update();
           }
           Comment::create([
            'content' => Auth::user()->name ." updated Item Damage Count for".$itemname_id."(".$request->countdamage_qty.")" ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$itemname_id,
            'commendable_type' => "items"
        
        ]);
        return redirect()->back()->with(['status'=>'Item Name '.$itemname_id.'('.$request->countdamage_qty.') Has Been Updated']);
        } 
      }abort(404,"Sorry");     
    }
     
    public function itembyitemtransferredocountupdate(Request $request,$itemname_id,$transfer_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemtransfer"))
      {
        $items = DB::table('items')
                ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 1)
                ->where('transfers.id', $transfer_id)->orderBy('items.id','asc')->get();
         
        $items_count = $items->count();
          
        if($request->get('countredodamage_qty') == null || $request->get('countredodamage_qty') == 0)
        {
            return redirect()->back()->with(['errorstatus'=>'Damage Qty must be Filled!!!']);
        }
        elseif($request->get('countredodamage_qty') > $items_count)
        {
            return redirect()->back()->with(['errorstatus'=>'Redo Damage Qty must not be larger than Total Item!!!']);
        }
        else
        {
            $itemsbycounts = DB::table('items')
                ->join('item_transfer', 'item_transfer.item_id', '=', 'items.id')
                ->join('transfers', 'transfers.id', '=', 'item_transfer.transfer_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=',1)
                ->where('transfers.id', $transfer_id)
                ->orderBy('items.id','asc')
                ->take($request->get('countredodamage_qty'))
                ->get();
           foreach($itemsbycounts as $k=>$item)
           {           
            $itemupdate=Item::whereId($item->item_id)->firstOrFail();
            $itemupdate->damage_qty='0';
            $itemupdate->damage_reason= $request->get('redodamage_reason');
            $itemupdate->updated_at=Carbon::now()->timestamp;
            $itemupdate->update();
           }
           Comment::create([
            'content' => Auth::user()->name ." updated Redo Item Damage Count for".$itemname_id."(".$request->countdamage_qty.")" ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$itemname_id,
            'commendable_type' => "items"
        
        ]);
        return redirect()->back()->with(['status'=>'Item Name '.$itemname_id.'('.$request->countdamage_qty.') Has Been Updated']);
        }     
       

      }abort(404,"Sorry");     
    }
  

    public function create()
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemtransfer"))
      {
            $categories=null;
            $s="";
            $items=null;
            $store=null;
            $data=null;
            $stores=Store::all();             
            $staffs=Staff::all();
            if(session('itemstransfers') || session('itemstransfersserials') ||session('itemstransfersmacs'))
            {
              Session::forget('itemstransfers');
              Session::forget('itemstransfersserials');
              Session::forget('itemstransfersmacs');
            }
          
            return view('admin.transfers.create',compact('data','categories','items','store','stores','s','staffs'));  
        }abort(404,"Sorry");    
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemtransfer"))
        {
          $s = $request->search;
          $store=Store::where('name', 'LIKE', "%{$s}%")->first();
          $categories=Category::all();         
          $items=Item::all();
          
          $data="";
          $stores=Store::all(); 
          
          
          $staffs=Staff::all();
         // dd($store->id);
          if(session('itemscustomers'))
          {
            // $aa=array_map(function($x){
            //   return $x['store_id'];
            // },session('itemsservers'));
 
            foreach(session('itemstransfers') as $k=>$val)
            {
              if(is_array($val))
              {
                if($val['store_id']!=$store->id)
                {
                  Session::forget('itemstransfers');
                  Session::forget('itemstransfersserials');
                  Session::forget('itemstransfersmacs');
                }
              }
            }
          }
       
         
         return view('admin.transfers.create',compact('data','categories','items','store','stores','s','staffs'));
        }abort(404,"Sorry"); 
    }

     public function savetransferserial(Request $request,$itemname_id,$store_id)
    {        
        if($request->serial == null)
        {
            Session::forget('itemstransfersserials');
            return redirect()->back()->with('errorstatus', 'Please check for Serial Number');
        }
        else 
        {
            $itemsserials=array();
            $itemids=array();
            $serialitemids=array();
            $dif=array();
            $items = Item::where('itemname_id',$itemname_id)
                    ->where('store_id',$store_id)
                    ->where('qty','=','1')
                    ->where('used_qty','=','0')
                    ->where('damage_qty','=','0')
                    ->where('transfer_qty','=','0')->get();
          
            foreach($items as $k=>$item)
            {   
                array_push($itemids,$item->id);               
               
            }
             
            foreach($request->serial as $k=>$serial)
            {  
                 array_push($serialitemids,$serial);                               
            }
            $itemiddiff = array_diff($itemids, $serialitemids);
            foreach($itemiddiff as $k=>$diff)
            {
                array_push($dif,$diff);
            }
           
            $storedItemSerial = ['same_id'=>$serialitemids,'diff_id'=>$dif,'itemname_id'=>$itemname_id,'store_id'=>$store_id];
            
            array_push($itemsserials,$storedItemSerial) ;
            $request->session()->put('itemstransfersserials',$itemsserials);
            return redirect()->back()->with('status', 'New Serial Successfully Saved');
        }        
    }
     public function additemtransferserial(Request $request,$id,$store_id)
    { 
        // Session::forget('itemsserials');
        //     Session::forget('itemsservers'); 
         $dataserials = Session::has('itemstransfersserials') ? Session::get('itemstransfersserials') : '';
         
         if($dataserials == "")
         {
            $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';
           //return redirect()->back()->with('errorstatus', 'Please Check Serial Number');
            return response()->json($data)->with(['errorstatus'=>'Please Check Serial Number']);
         }
         else
         { 
              $itemstransfers=array();
              $ids=array();
              $itemsserialbyserial=array();
              
             if($request->session()->has('itemstransfers'))
             {  
                $itemstransfers=$request->session()->get('itemstransfers');
                
                foreach($itemstransfers as $itemsserver)
                {                     
                  $id1=$itemsserver['id'];
                  array_push($ids,$id1);
                }
                  if(!in_array($id, $ids))
                  {
                    $itemnamebyid=Itemname::whereId($id)->first();
                    $catbyid=$itemnamebyid->category->title;
                    $count=0;
                  
                    $itemstransfersserials=$request->session()->get('itemstransfersserials');
                   foreach($itemstransfersserials[0]['same_id'] as $itemsserial)
                   {
                       $count+=1;
                       array_push($itemsserialbyserial,$itemsserial);
                   }
                    //dd($count);
                  $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>$itemsserialbyserial,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
                    array_push($itemstransfers,$storedItem);
                    Session::forget('itemstransfersserials');
                    $request->session()->put('itemstransfers',$itemstransfers);
                    $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';
                    return response()->json($data);
                    //return redirect()->back()->with('status', 'Successfully Saved');
                       
                  } 
             }
            else
            {                  
                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                $count=0;               
                $itemstransfersserials=$request->session()->get('itemstransfersserials');

               foreach($itemstransfersserials[0]['same_id'] as $itemsserial)
               {
                   $count+=1;
                   array_push($itemsserialbyserial,$itemsserial);
               }
               
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>$itemsserialbyserial,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
                array_push($itemstransfers,$storedItem);
                Session::forget('itemstransfersserials');
                 $request->session()->put('itemstransfers',$itemstransfers);
                $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';
                return response()->json($data);
                //return redirect()->back()->with('status', 'Successfully Saved');
            }
           
         }                                     
    }


    public function savetransfermac(Request $request,$itemname_id,$store_id)
    {        
        if($request->mac == null)
        {
            Session::forget('itemstransfersmacs');
            return redirect()->back()->with('errorstatus', 'Please check for MAC');
        }
        else 
        {
            $itemstransfersmacs=array();
            $itemids=array();
            $macitemids=array();
            $dif=array();
            $items = Item::where('itemname_id',$itemname_id)
                    ->where('store_id',$store_id)
                    ->where('qty','=','1')
                    ->where('used_qty','=','0')
                    ->where('damage_qty','=','0')
                    ->where('transfer_qty','=','0')->get();
          
            foreach($items as $k=>$item)
            {   
                array_push($itemids,$item->id);               
               
            }
             
            foreach($request->mac as $k=>$mac)
            {  
                 array_push($macitemids,$mac);                               
            }
            $itemiddiff = array_diff($itemids, $macitemids);
            foreach($itemiddiff as $k=>$diff)
            {
                array_push($dif,$diff);
            }
           
            $storedItemMac = ['same_id'=>$macitemids,'diff_id'=>$dif,'itemname_id'=>$itemname_id,'store_id'=>$store_id];
            
            array_push($itemstransfersmacs,$storedItemMac) ;
            $request->session()->put('itemstransfersmacs',$itemstransfersmacs);
            return redirect()->back()->with('status', 'New MAC Successfully Saved');
        }        
    }
     public function additemtransfermac(Request $request,$id,$store_id)
    { 
        // Session::forget('itemsmacs');
        //     Session::forget('itemsservers'); 
         $datamacs = Session::has('itemstransfersmacs') ? Session::get('itemstransfersmacs') : '';
       
         if($datamacs == "")
         {
            $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';
            //return redirect()->back()->with('errorstatus', 'Please Check Serial Number');
            return response()->json($data)->with(['errorstatus'=>'Please Check MAC Number']);
         }
         else
         { 
              $itemstransfers=array();
              $ids=array();
              $itemsserialbyserial=array();
              
             if($request->session()->has('itemstransfers'))
             {  
                $itemstransfers=$request->session()->get('itemstransfers');
                
                foreach($itemstransfers as $itemsserver)
                {                     
                  $id1=$itemsserver['id'];
                  array_push($ids,$id1);
                }
                  if(!in_array($id, $ids))
                  {
                    $itemnamebyid=Itemname::whereId($id)->first();
                    $catbyid=$itemnamebyid->category->title;
                    $count=0;
                  
                    $itemstransfersmacs=$request->session()->get('itemstransfersmacs');
                   foreach($itemstransfersmacs[0]['same_id'] as $itemsmac)
                   {
                       $count+=1;
                       array_push($itemsserialbyserial,$itemsmac);
                   }
                    //dd($count);
                  $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>$itemsserialbyserial,'itemsserialbycount'=>""];
                    array_push($itemstransfers,$storedItem);
                    Session::forget('itemstransfersmacs');
                   // dd($itemsservers);
                    $request->session()->put('itemstransfers',$itemstransfers);
                    $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';
                    return response()->json($data);
                    //return redirect()->back()->with('status', 'Successfully Saved');
                       
                  } 
             }
            else
            {   

                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                $count=0;               
                $itemstransfersmacs=$request->session()->get('itemstransfersmacs');
               
               foreach($itemstransfersmacs[0]['same_id'] as $itemsmac)
               {
                   $count+=1;
                   array_push($itemsserialbyserial,$itemsmac);
               }
               
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>$itemsserialbyserial,'itemsserialbycount'=>""];
                array_push($itemstransfers,$storedItem);
               //dd($itemsservers);
                Session::forget('itemstransfersmacs');
                $request->session()->put('itemstransfers',$itemstransfers);
                $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';
               return response()->json($data);
              //return redirect()->back()->with('status', 'Successfully Saved');
            }
           
         }  
                                   
    }



    public function additemstransfercount(Request $request,$id,$store_id,$count)
    { 
        $items=DB::table('items')
        ->where('itemname_id',$id)
        ->where('store_id',$store_id)
        ->where('qty','=','1')
        ->where('used_qty','=','0')
        ->where('damage_qty','=','0')
        ->where('transfer_qty','=','0');                            
        $item_idscount=$items->count();
        $itemstakecounts=$items ->orderBy('id','asc')
                        ->take($count)
                        ->get();
       
       if($item_idscount < $count)
       {  
       // return redirect()->back()->with(['errorstatus'=>'Insert not greater than avalible Item']);
          $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';
          return response()->json($data)->with(['statuserror'=>'Insert not greater than avalible Item']);           
        }

        $itemstransfers=array();
        $ids=array();
        $idsbycount=array();
        foreach($itemstakecounts as $item)
        {
            array_push($idsbycount,$item->id);
        }
        
        if($request->session()->has('itemstransfers'))
        {
           $itemstransfers=$request->session()->get('itemstransfers');
           foreach($itemstransfers as $itemsserver)
           {
              $id1=$itemsserver['id'];
              array_push($ids,$id1);            

           }

          if(!in_array($id, $ids))
          {
                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>"",'itemsserialbycount'=>$idsbycount];
            array_push($itemstransfers,$storedItem);          
          }               
        }
        else
        {
           $itemnamebyid=Itemname::whereId($id)->first();
           $catbyid=$itemnamebyid->category->title;

            $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>"",'itemsserialbycount'=>$idsbycount];
            array_push($itemstransfers,$storedItem);          
        }

        $request->session()->put('itemstransfers',$itemstransfers);
        
        $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';
        return response()->json($data);
        //return redirect()->back()->with('status', 'Successfully Saved');
    }

    public function remove_itemtransfersitems($id)
    { 
     $itemstransfers = Session::get('itemstransfers');
     foreach($itemstransfers as $key=>$item)
     {
        if($id == $item['id'])
        {
         unset($itemstransfers[$key]);
         Session()->put('itemstransfers',$itemstransfers);
        }
     }
     $data = Session::has('itemstransfers') ? Session::get('itemstransfers') : '';

       return response()->json($data);
     //return redirect()->back();
    }
    public function gettransferstaffdata($id) 
    { 
        $positions=DB::table("staff")
            ->join('jobtitles','jobtitles.id','=','staff.jobtitle_id')
            ->where('staff.id',$id)
            ->pluck('jobtitles.name','jobtitles.id');

        return json_encode($positions);
    }

     public function itemtransfercheckout(Request $request,$store_id) 
    { 
        $itemstransfers=Session::has('itemstransfers') ? Session::get('itemstransfers') : null;
        $itemms_ids=array();

        foreach(session('itemstransfers') as $k=>$val)
        {
          if(is_array($val))
          {   
            if($val['itemsserialbyserial'] != "") 
            {
                foreach($val['itemsserialbyserial'] as $s=>$itemserial)
                {
                    array_push($itemms_ids,$itemserial);
                    $itemupdate=Item::whereId($itemserial)->firstOrFail();
                    $itemupdate->transfer_qty='1';
                    $itemupdate->updated_at=Carbon::now()->timestamp;
                    $itemupdate->update();

            //          $itemcreateserial=Item::create([
			         //    'itemname_id' => $itemupdate->itemname_id,
					      	// 'model' => $itemupdate->model,
			         //    'mac' => $itemupdate->mac,
			         //    'serial_number' => $itemupdate->serial_number,
			         //    'voucher_id' => $itemupdate->voucher_id,
			         //    'store_id' => $request->get('to'),
			         //    'unit_price' => $itemupdate->unit_price,
			         //    'amount'=> $itemupdate->amount,                             
			         //    'total_qty' =>$itemupdate->total_qty,
			         //    'qty' => 1,
			         //    'used_qty' => 0,
			         //    'transfer_qty' => 0,     
			         //    'damage_qty' => 0,
			         //    'damage_reason' => $itemupdate->damage_reason,
			         //    'category_id' => $itemupdate->category_id
			         //    ]);
                }
            }  
            if($val['itemsserialbymac'] != "") 
            {
                foreach($val['itemsserialbymac'] as $s=>$itemmac)
                {
                    array_push($itemms_ids,$itemmac);
                    $itemupdate=Item::whereId($itemmac)->firstOrFail();
                    $itemupdate->transfer_qty='1';
                    $itemupdate->updated_at=Carbon::now()->timestamp;
                    $itemupdate->update();

            //         $itemcreatemac=Item::create([
			         //    'itemname_id' => $itemupdate->itemname_id,
						      // 'model' => $itemupdate->model,
			         //    'mac' => $itemupdate->mac,
			         //    'serial_number' => $itemupdate->serial_number,
			         //    'voucher_id' => $itemupdate->voucher_id,
			         //    'store_id' => $request->get('to'),
			         //    'unit_price' => $itemupdate->unit_price,
			         //    'amount'=> $itemupdate->amount,                             
			         //    'total_qty' =>$itemupdate->total_qty,
			         //    'qty' => 1,
			         //    'used_qty' => 0,
			         //    'transfer_qty' => 0,     
			         //    'damage_qty' => 0,
			         //    'damage_reason' => $itemupdate->damage_reason,
			         //    'category_id' => $itemupdate->category_id
			         //    ]);
                }
            }  
            if($val['itemsserialbycount'] != "") 
            {
                foreach($val['itemsserialbycount'] as $s=>$itemcount)
                {
                    array_push($itemms_ids,$itemcount);
                    $itemupdate=Item::whereId($itemcount)->firstOrFail();
                    $itemupdate->transfer_qty='1';
                    $itemupdate->updated_at=Carbon::now()->timestamp;
                    $itemupdate->update();

             //        $itemcreatecount=Item::create([
			          //   'itemname_id' => $itemupdate->itemname_id,
						       // 'model' => $itemupdate->model,
			          //   'mac' => $itemupdate->mac,
			          //   'serial_number' => $itemupdate->serial_number,
			          //   'voucher_id' => $itemupdate->voucher_id,
			          //   'store_id' => $request->get('to'),
			          //   'unit_price' => $itemupdate->unit_price,
			          //   'amount'=> $itemupdate->amount,                             
			          //   'total_qty' =>$itemupdate->total_qty,
			          //   'qty' => 1,
			          //   'used_qty' => 0,
			          //   'transfer_qty' => 0,     
			          //   'damage_qty' => 0,
			          //   'damage_reason' => $itemupdate->damage_reason,
			          //   'category_id' => $itemupdate->category_id
			          //   ]);
                }
                
            }  
          }
        }
       
        if($request->signed)
        {
            $folderPath = public_path().'/images/ftth/transfers/'; 

            $image_parts = explode(";base64,", $request->signed); 
            $image_type_aux = explode("image/", $image_parts[0]);         
            $image_type = $image_type_aux[1];         
            $image_base64 = base64_decode($image_parts[1]);  
            $file = uniqid() . '.'.$image_type;
            $fileupload =$folderPath . $file;

            file_put_contents($fileupload, $image_base64);
        }
        else
        {
            $file="";
        }
        $transfer = new Transfer;
        $last_transferno = Transfer::orderBy('id','DESC')->first();
        if($last_transferno == null)
        { 
          $transfer->transfer_number = 'TR-000001';
        }
        else
        {
         $transfer->transfer_number = 'TR-'.str_pad($last_transferno->id + 1, 6, "0", STR_PAD_LEFT);
        }
        //$street->streetname_id = $request->get('streetname_id');
        //$street->store_id = $store_id;
        $transfer->user_id=Auth::user()->id;
        $transfer->staff_id=$request->get('staff_id');
        $transfer->transfer_sign_file=$file;
        $transfer->status=$request->get('status');
        $transfer->from=$store_id;
        $transfer->to=$request->get('to');
        $transfer->content=$request->get('content');
        $transfer->store_id = $store_id;
        $transfer->save();
        $transfer->items()->sync($itemms_ids);

        Comment::create([
            'content' => Auth::user()->name ." created new Transfer Items  ".$transfer->transfer_number ."(".count($itemms_ids).'items)',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$transfer->id ,
            'commendable_type' => "transfers"
        
        ]);
        Session::forget('itemstransfers');       
       return redirect()->back()->with('status', 'Successfully Saved Transfer');
        // 
        // return redirect('/admin/ItemsServerList')->with("status","Server Entry Successfully Added");
    }

    public function destroy($id)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemtransfer"))
       {
        $transfer=Transfer::whereId($id)->firstOrFail();
        $item_transfers=DB::table('item_transfer')->where('transfer_id',$id)->get();
        foreach($item_transfers as $item_transfer)
        {
            $items_id=Item::where('id',$item_transfer->item_id)->first();
            $items_id->transfer_qty=0;
            $items_id->updated_at=Carbon::now()->timestamp;          
            $items_id->update();
        }
        if($transfer->transfer_sign_file)
        {
          $image_path = public_path().'/images/ftth/transfers/'.$transfer->transfer_sign_file;
          unlink($image_path);
        }
         
       
        $transfer->items()->detach();
        $transfer->delete();

        Comment::create([
            'content' => Auth::user()->name ." deleted Transfer  ".$transfer->transfer_number,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "streets"
        
        ]);
         return redirect()->back()->with(['status'=>'Transfer ('.$transfer->transfer_number.' ) Has Been Deleted']);
     }abort(404,"Sorry");           
    }

    public function edit($transfer_number)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemtransfer"))
      {
        $transfer = Transfer::where('transfer_number',$transfer_number)->first();
        $store=Store::whereId($transfer->store_id)->first();
        $categories=Category::all();         
        $items=Item::all();          
        //   $data="";
        $stores=Store::all(); 
        
        $staffs=Staff::all();

        $items=array();
        $itemsarrays=array();
        $itemnamesfrompivot=$transfer->items()->pluck('itemname_id')->toArray();
        $itemsfromarrays=array_count_values($itemnamesfrompivot);

        $items_ids=$transfer->items()->pluck('item_id')->toArray();
       // $items = DB::table('items')->whereIn('id', $items_ids)->get();

        foreach($itemsfromarrays as $k=>$itemsfromarray)
        {
          $itemname=Itemname::where('id',$k)->first();
          $catbyid=$itemname->category->title;
          $catdata=Category::where('id',$itemname->category_id)->first();
          $items = DB::table('items')->whereIn('id', $items_ids)->where('itemname_id',$k)->get();
           if($catdata->mac == 1 || $catdata->serial == 1)
           {
               $storedItem = ['id'=>$k,'store_id'=>$store->id,'itemname'=>$itemname->name,'categoryname'=>$catbyid,'count' =>$itemsfromarray,'itemsserialbyserial'=>$items,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
               array_push($itemsarrays,$storedItem);
           }
           else
           {
               $storedItem = ['id'=>$k,'store_id'=>$store->id,'itemname'=>$itemname->name,'categoryname'=>$catbyid,'count' =>$itemsfromarray,'itemsserialbyserial'=>'','itemsserialbymac'=>"",'itemsserialbycount'=>$items];
                array_push($itemsarrays,$storedItem);
           }
         
        }
      

        session()->put('itemstransfersdb',$itemsarrays); 
        return view('admin.transfers.edit',compact( 'categories','items','store','stores','staffs','transfer'));
      }abort(404,"Sorry");       
        
      
    }

    public function removeolditemssamdmtransfer($itemname_id,$id,$transfer_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemtransfer"))
      {
        $transfer = Transfer::whereId($transfer_id)->first();       
        $transfer->items()->detach($id);

        $items_id=Item::whereId($id)->first();
        $items_id->transfer_qty=0;
        $items_id->updated_at=Carbon::now()->timestamp;          
        $items_id->update();

        $itemname=Itemname::whereId($itemname_id)->first();
        Comment::create([
             'content' => Auth::user()->name ." deleted Item From Transfer ".$transfer->transfer_number ."& Item Name (".$itemname['name'].")",
              'user_id' => Auth::user()->id,
              'commendable_id' =>$id ,
              'commendable_type' => "item_street"
          
          ]);
        return redirect()->back()->with(['status'=>'Item for Item Name '.$itemname['name']. ' Has Been Deleted']);

        }abort(404,"Sorry");    
   }
    public function update(Request $request, $transfer_number)
   { 
     if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemtransfer"))
     {
      $transfer = Transfer::where('transfer_number',$transfer_number)->first();    
      $itemstransfers=Session::has('itemstransfers') ? Session::get('itemstransfers') : null;
      $itemms_ids=array();
      
      if(session('itemstransfers'))
      {
        foreach(session('itemstransfers') as $k=>$val)
      {
        if(is_array($val))
        {   
           if($val['itemsserialbyserial'] != "") 
           {
                foreach($val['itemsserialbyserial'] as $s=>$itemserial)
                {
                    array_push($itemms_ids,$itemserial);
                    $itemupdate=Item::whereId($itemserial)->firstOrFail();
                    $itemupdate->transfer_qty='1';
                    $itemupdate->updated_at=Carbon::now()->timestamp;
                    $itemupdate->update();
                }
            }  
            if($val['itemsserialbymac'] != "") 
            {
                foreach($val['itemsserialbymac'] as $s=>$itemmac)
                {
                    array_push($itemms_ids,$itemmac);
                    $itemupdate=Item::whereId($itemmac)->firstOrFail();
                    $itemupdate->transfer_qty='1';
                    $itemupdate->updated_at=Carbon::now()->timestamp;
                    $itemupdate->update();
                }
            }  
            if($val['itemsserialbycount'] != "") 
            {
                foreach($val['itemsserialbycount'] as $s=>$itemcount)
                {
                    array_push($itemms_ids,$itemcount);
                    $itemupdate=Item::whereId($itemcount)->firstOrFail();
                    $itemupdate->transfer_qty='1';
                    $itemupdate->updated_at=Carbon::now()->timestamp;
                    $itemupdate->update();
                }
            }  
        }
      }
     }
     if(session('itemstransfersdb'))
     {
      foreach(session('itemstransfersdb') as $kold=>$valold)
      {
        if(is_array($valold))
        {
           if($valold['itemsserialbyserial'] != "") 
           {
                foreach($valold['itemsserialbyserial'] as $s=>$itemserial)
                {
                    array_push($itemms_ids,$itemserial->id);
                }
           }
           if($valold['itemsserialbymac'] != "") 
           {
                foreach($valold['itemsserialbymac'] as $s=>$itemmac)
                {
                    array_push($itemms_ids,$itemmac->id);
                }
           } 
           if($valold['itemsserialbycount'] != "") 
           {
                foreach($valold['itemsserialbycount'] as $s=>$itemc)
                {
                    array_push($itemms_ids,$itemc->id);
                }
            }       
        }
      }
     }
      
      
      if($request->signed)
        {
            $folderPath = public_path().'/images/ftth/transfers/'; 

            $image_parts = explode(";base64,", $request->signed); 
            $image_type_aux = explode("image/", $image_parts[0]);         
            $image_type = $image_type_aux[1];         
            $image_base64 = base64_decode($image_parts[1]);  
            $file = uniqid() . '.'.$image_type;
            $fileupload =$folderPath . $file;

            file_put_contents($fileupload, $image_base64);
        }
        else
        {
            $file= $transfer->transfer_sign_file;
        }
       
           $transfer->user_id=Auth::user()->id;
           $transfer->staff_id=$request->get('staff_id');
           $transfer->transfer_sign_file=$file;
           $transfer->status=$request->get('status');
          // $transfer->from=$store_id;
           $transfer->to=$request->get('to');
           $transfer->content=$request->get('content');
           $transfer->updated_at=Carbon::now()->timestamp;
           $transfer->update();
           $transfer->items()->sync($itemms_ids);

        Comment::create([
            'content' => Auth::user()->name ." updated  Transfer Items  ".$transfer->transfer_number ."(".count($itemms_ids).'items)',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$transfer->id ,
            'commendable_type' => "transfers"
        
        ]);
        Session::forget('itemstransfers');       
         return redirect()->back()->with('status', 'Successfully Updated Transfer'.$transfer->transfer_number);
        // 
        // return redirect('/admin/ItemsServerList')->with("status","Server Entry Successfully Added");
        }abort(404,"Sorry");    
       
    }


}
