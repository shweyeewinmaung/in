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
use App\Customer;
use App\Customername;
use App\Item;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemcustomer"))
       {
        $customernameslistsall=Customername::all();
        $customernameslists = Customername::orderBy('id', 'desc')->paginate(30);
        $customers=0;
        $s="";
        return view('admin.customers.list',compact('customernameslistsall','customernameslists','customers','s'));
       }abort(404,"Sorry");    
    }

    public function searchlist(Request $request)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemcustomer"))
       {
            $s = $request->search;
            $customernameslistsall=Customername::where('name', 'LIKE', "%{$s}%")
                ->orWhere('code', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%");

            $customernameslists = Customername::where('name', 'LIKE', "%{$s}%")
                ->orWhere('code', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(50);

             $customers=Customername::all()->count();
                
            return view('admin.customers.list',compact('customernameslistsall','customernameslists','customers','s'));
        }abort(404,"Sorry");
    }

    public function customerviewshow(Request $request,$code)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemcustomer"))
       {
        $customer_namesdatas=Customername::where('code',$code)->firstOrFail();
        $customerbycustomer_names=Customer::where('customername_id',$customer_namesdatas['id'])->orderBy('id', 'desc')->paginate(50);
        $customerbycustomer_namesall=Customer::where('customername_id',$customer_namesdatas['id'])->orderBy('id', 'desc')->get();
        $searchdetail="";
        //dd($customer_namesdatas['id']);
         
        return view('admin.customers.customerviewshow',compact('code','customerbycustomer_names','customerbycustomer_namesall','searchdetail'));  
        }abort(404,"Sorry");     
    }
    public function searchviewshow(Request $request,$code)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemcustomer"))
      {
        $searchdetail = $request->searchdetail;
        $customer_namesdatas=Customername::where('code',$code)->firstOrFail();

        if($searchdetail == null)
        {
            $customerbycustomer_names=Customer::where('customername_id',$customer_namesdatas['id'])
            ->orderBy('id', 'desc')
            ->paginate(50);

            $customerbycustomer_namesall=Customer::where('customername_id',$customer_namesdatas['id'])
            ->orderBy('id', 'desc');
        }
        else
        {
            $customerbycustomer_names=Customer::where('customername_id',$customer_namesdatas['id'])
            ->where('customer_number', 'LIKE', "%{$searchdetail}%")
            ->orWhereHas('staff', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhere('status', 'LIKE', "%{$searchdetail}%")
            ->orderBy('id', 'desc')
            ->paginate(50);

             $customerbycustomer_namesall=Customer::where('customername_id',$customer_namesdatas['id'])
            ->where('customer_number', 'LIKE', "%{$searchdetail}%")
             ->orWhereHas('staff', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhere('status', 'LIKE', "%{$searchdetail}%")
            ->orderBy('id', 'desc');
        }

        return view('admin.customers.customerviewshow',compact('code','customerbycustomer_names','customerbycustomer_namesall','searchdetail'));  
       }abort(404,"Sorry");                
    }

    public function customerdetailconfirm(Request $request,$id,$code)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("confirm-itemcustomer"))
      {
       $customer=Customer::whereId($id)->firstOrFail();
       $customer->confirm_user_id=Auth::user()->id;
       $customer->status='confirm';
       $customer->updated_at=Carbon::now()->timestamp;

        Comment::create([
            'content' => Auth::user()->name ." Confirm  Customer Code  ".$code ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "customers"
        
        ]);
        $customer->update();
       
        return redirect()->back()->with(['status'=>'Customer Code '.$code.' Has Been Confirm']);
      }abort(404,"Sorry");              
    }
    public function customerdetailredoconfirm(Request $request,$id,$code)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("redoconfirm-itemcustomer"))
      {
       $customer=Customer::whereId($id)->firstOrFail();
       $customer->confirm_user_id="";
       $customer->status='pending';
       $customer->updated_at=Carbon::now()->timestamp;

        Comment::create([
            'content' => Auth::user()->name ." Redo Confirm for Customer Code  ".$code ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "customers"
        
        ]);
        $customer->update();
       
        return redirect()->back()->with(['status'=>'Customer Code '.$code.' Has Been ReConfirm']);
      }abort(404,"Sorry");              
    }
    
    public function customerviewdetailshow(Request $request,$code,$customer_number)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemcustomer"))
      {
        $customerdetail=Customer::where('customer_number',$customer_number)->first();
        
        // $serveritemsbydetails=$serverdetail->items()->paginate(30); 
        // $serverbyitemsarrays=$serveritemsbydetails->pluck('id')->toArray();
       
       // $itembydetails=$serverdetail->items()->groupBy('itemname_id')->paginate(1); 
        //dd($aas);
        $itembyname=$customerdetail->items()->pluck('itemname_id')->toArray();
        $itembyname_counts=array_count_values($itembyname);

        $itemsarrays=array();
        
        foreach($itembyname_counts as $k=>$itembyname_count)
        { 
              $itemname=Itemname::where('id',$k)->first();
              $catname=$itemname->category->title;
              $catmac=$itemname->category->mac;
              $catserial=$itemname->category->serial;


            $items = DB::table('items')
                      ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                      ->join('customers', 'customers.id', '=', 'customer_item.customer_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('customers.id', $customerdetail['id'])->get();

            $items_damage = DB::table('items')
                      ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                      ->join('customers', 'customers.id', '=', 'customer_item.customer_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('items.damage_qty', '=', 1)
                      ->where('customers.id', $customerdetail['id'])->get();
          
 
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
       
        return view('admin.customers.customerviewdetailshow',compact('code','customerdetail','customer_number','itemsarrays'));
         
      }abort(404,"Sorry");     
    }

    public function itembyitemcustomerupdate(Request $request,$id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemcustomer"))
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
    
    public function itembyitemcustomercountupdate(Request $request,$itemname_id,$customer_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemcustomer"))
      {         
        $items = DB::table('items')
                ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                ->join('customers', 'customers.id', '=', 'customer_item.customer_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                ->where('customers.id', $customer_id)
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
                ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                ->join('customers', 'customers.id', '=', 'customer_item.customer_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                ->where('customers.id', $customer_id)
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

    public function itembyitemcustomerredocountupdate(Request $request,$itemname_id,$customer_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemcustomer"))
      {
        $items = DB::table('items')
                ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                ->join('customers', 'customers.id', '=', 'customer_item.customer_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 1)
                ->where('customers.id', $customer_id)->orderBy('items.id','asc')->get();
         
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
                ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                ->join('customers', 'customers.id', '=', 'customer_item.customer_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=',1)
                ->where('customers.id', $customer_id)
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
    
    public function customerviewalldetail(Request $request,$code)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemcustomer"))
      {
        $customername=Customername::where('code',$code)->firstOrFail();
        $customers=Customer::where('customername_id',$customername['id'])
        ->where('status','=','confirm')
        ->get();
        
        // $server_items=array();
        // $items=array();
        $itemnamedatas=array();
        $itemsarrays=array();
        $itemdatas=array();
        foreach($customers as $customer)
        {
          $customerdata=Customer::whereId($customer['id'])->first();
          $itembyname=$customerdata->items()->get();
          array_push($itemnamedatas,$itembyname);

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
           return view('admin.customers.alldetail',compact('code','customername','itemsarrays'));
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
                      ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                      ->join('customers', 'customers.id', '=', 'customer_item.customer_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('customers.customername_id', '=', $customername['id'])
                      ->get();

              $items_damage = DB::table('items')
                      ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                      ->join('customers', 'customers.id', '=', 'customer_item.customer_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('customers.customername_id', '=', $customername['id'])
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

                             
        }
       
        return view('admin.customers.alldetail',compact('code','customername','itemsarrays'));
       
      }abort(404,"Sorry"); 
       
    }

    public function itembyitemcustomercountupdatealldetail(Request $request,$itemname_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemcustomer"))
      {    
          $items = DB::table('items')
                ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                ->join('customers', 'customers.id', '=', 'customer_item.customer_id')    
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
                ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                ->join('customers', 'customers.id', '=', 'customer_item.customer_id')
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

    public function itembyitemcustomerredocountupdatealldetail(Request $request,$itemname_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemcustomer"))
      {
        $items = DB::table('items')
                ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                ->join('customers', 'customers.id', '=', 'customer_item.customer_id')
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
                ->join('customer_item', 'customer_item.item_id', '=', 'items.id')
                ->join('customers', 'customers.id', '=', 'customer_item.customer_id')
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

    public function create()
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemcustomer"))
      {
            $categories=null;
            $s="";
            $items=null;
            $store=null;
            $data=null;
            $stores=Store::all();
            $customername="";
            $staffs=Staff::all();
            if(session('itemscustomers') || session('itemscustomersserials') ||session('itemscustomersmacs'))
            {
              Session::forget('itemscustomers');
              Session::forget('itemscustomersserials');
              Session::forget('itemscustomersmacs');
            }
          
            return view('admin.customers.create',compact('data','categories','items','store','stores','s','customername','staffs'));  
        }abort(404,"Sorry");    
    }
    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemcustomer"))
        {
          $s = $request->search;
          $store=Store::where('name', 'LIKE', "%{$s}%")->first();
          $categories=Category::all();         
          $items=Item::all();
          
          $data="";
          $stores=Store::all(); 
          
          $customername="";
          $staffs=Staff::all();
         // dd($store->id);
          if(session('itemscustomers'))
          {
            // $aa=array_map(function($x){
            //   return $x['store_id'];
            // },session('itemsservers'));
 
            foreach(session('itemscustomers') as $k=>$val)
            {
              if(is_array($val))
              {
                if($val['store_id']!=$store->id)
                {
                  Session::forget('itemscustomers');
                  Session::forget('itemscustomersserials');
                  Session::forget('itemscustomersmacs');
                }
              }
            }
          }
       
         
         return view('admin.customers.create',compact('data','categories','items','store','stores','s','customername','staffs'));
        }abort(404,"Sorry"); 
    }

    public function savecustomerserial(Request $request,$itemname_id,$store_id)
    {        
        if($request->serial == null)
        {
            Session::forget('itemscustomersserials');
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
            $request->session()->put('itemscustomersserials',$itemsserials);
            return redirect()->back()->with('status', 'New Serial Successfully Saved');
        }        
    }

    public function additemcustomerserial(Request $request,$id,$store_id)
    { 
        // Session::forget('itemsserials');
        //     Session::forget('itemsservers'); 
         $dataserials = Session::has('itemscustomersserials') ? Session::get('itemscustomersserials') : '';
         
         if($dataserials == "")
         {
            $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';
           //return redirect()->back()->with('errorstatus', 'Please Check Serial Number');
            return response()->json($data)->with(['errorstatus'=>'Please Check Serial Number']);
         }
         else
         { 
              $itemscustomers=array();
              $ids=array();
              $itemsserialbyserial=array();
              
             if($request->session()->has('itemscustomers'))
             {  
                $itemscustomers=$request->session()->get('itemscustomers');
                
                foreach($itemscustomers as $itemsserver)
                {                     
                  $id1=$itemsserver['id'];
                  array_push($ids,$id1);
                }
                  if(!in_array($id, $ids))
                  {
                    $itemnamebyid=Itemname::whereId($id)->first();
                    $catbyid=$itemnamebyid->category->title;
                    $count=0;
                  
                    $itemscustomersserials=$request->session()->get('itemscustomersserials');
                   foreach($itemscustomersserials[0]['same_id'] as $itemsserial)
                   {
                       $count+=1;
                       array_push($itemsserialbyserial,$itemsserial);
                   }
                    //dd($count);
                  $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>$itemsserialbyserial,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
                    array_push($itemscustomers,$storedItem);
                    Session::forget('itemscustomersserials');
                    $request->session()->put('itemscustomers',$itemscustomers);
                    $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';
                    return response()->json($data);
                    //return redirect()->back()->with('status', 'Successfully Saved');
                       
                  } 
             }
            else
            {                  
                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                $count=0;               
                $itemscustomersserials=$request->session()->get('itemscustomersserials');

               foreach($itemscustomersserials[0]['same_id'] as $itemsserial)
               {
                   $count+=1;
                   array_push($itemsserialbyserial,$itemsserial);
               }
               
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>$itemsserialbyserial,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
                array_push($itemscustomers,$storedItem);
                Session::forget('itemscustomersserials');
                 $request->session()->put('itemscustomers',$itemscustomers);
                $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';
                return response()->json($data);
                //return redirect()->back()->with('status', 'Successfully Saved');
            }
           
         }                                     
    }



    public function savecustomermac(Request $request,$itemname_id,$store_id)
    {        
        if($request->mac == null)
        {
            Session::forget('itemscustomersmacs');
            return redirect()->back()->with('errorstatus', 'Please check for MAC');
        }
        else 
        {
            $itemscustomersmacs=array();
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
            
            array_push($itemscustomersmacs,$storedItemMac) ;
            $request->session()->put('itemscustomersmacs',$itemscustomersmacs);
            return redirect()->back()->with('status', 'New MAC Successfully Saved');
        }        
    }

    public function additemcustomermac(Request $request,$id,$store_id)
    { 
        // Session::forget('itemsmacs');
        //     Session::forget('itemsservers'); 
         $datamacs = Session::has('itemscustomersmacs') ? Session::get('itemscustomersmacs') : '';
       
         if($datamacs == "")
         {
            $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';
            //return redirect()->back()->with('errorstatus', 'Please Check Serial Number');
            return response()->json($data)->with(['errorstatus'=>'Please Check MAC Number']);
         }
         else
         { 
              $itemscustomers=array();
              $ids=array();
              $itemsserialbyserial=array();
              
             if($request->session()->has('itemscustomers'))
             {  
                $itemscustomers=$request->session()->get('itemscustomers');
                
                foreach($itemscustomers as $itemsserver)
                {                     
                  $id1=$itemsserver['id'];
                  array_push($ids,$id1);
                }
                  if(!in_array($id, $ids))
                  {
                    $itemnamebyid=Itemname::whereId($id)->first();
                    $catbyid=$itemnamebyid->category->title;
                    $count=0;
                  
                    $itemscustomersmacs=$request->session()->get('itemscustomersmacs');
                   foreach($itemscustomersmacs[0]['same_id'] as $itemsmac)
                   {
                       $count+=1;
                       array_push($itemsserialbyserial,$itemsmac);
                   }
                    //dd($count);
                  $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>$itemsserialbyserial,'itemsserialbycount'=>""];
                    array_push($itemscustomers,$storedItem);
                    Session::forget('itemscustomersmacs');
                   // dd($itemsservers);
                    $request->session()->put('itemscustomers',$itemscustomers);
                    $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';
                    return response()->json($data);
                    //return redirect()->back()->with('status', 'Successfully Saved');
                       
                  } 
             }
            else
            {   

                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                $count=0;               
                $itemscustomersmacs=$request->session()->get('itemscustomersmacs');
               
               foreach($itemscustomersmacs[0]['same_id'] as $itemsmac)
               {
                   $count+=1;
                   array_push($itemsserialbyserial,$itemsmac);
               }
               
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>$itemsserialbyserial,'itemsserialbycount'=>""];
                array_push($itemscustomers,$storedItem);
               //dd($itemsservers);
                Session::forget('itemscustomersmacs');
                $request->session()->put('itemscustomers',$itemscustomers);
                $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';
               return response()->json($data);
              //return redirect()->back()->with('status', 'Successfully Saved');
            }
           
         }  
                                   
    }

    public function additemscustomercount(Request $request,$id,$store_id,$count)
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
          $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';
          return response()->json($data)->with(['statuserror'=>'Insert not greater than avalible Item']);           
        }

        $itemscustomers=array();
        $ids=array();
        $idsbycount=array();
        foreach($itemstakecounts as $item)
        {
            array_push($idsbycount,$item->id);
        }
        
        if($request->session()->has('itemscustomers'))
        {
           $itemscustomers=$request->session()->get('itemscustomers');
           foreach($itemscustomers as $itemsserver)
           {
              $id1=$itemsserver['id'];
              array_push($ids,$id1);            

           }

          if(!in_array($id, $ids))
          {
                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>"",'itemsserialbycount'=>$idsbycount];
            array_push($itemscustomers,$storedItem);          
          }               
        }
        else
        {
           $itemnamebyid=Itemname::whereId($id)->first();
           $catbyid=$itemnamebyid->category->title;

            $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>"",'itemsserialbycount'=>$idsbycount];
            array_push($itemscustomers,$storedItem);          
        }

        $request->session()->put('itemscustomers',$itemscustomers);
        
        $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';
        return response()->json($data);
        //return redirect()->back()->with('status', 'Successfully Saved');
    }

    public function remove_itemcustomersitems($id)
    { 
     $itemscustomers = Session::get('itemscustomers');
     foreach($itemscustomers as $key=>$item)
     {
        if($id == $item['id'])
        {
         unset($itemscustomers[$key]);
         Session()->put('itemscustomers',$itemscustomers);
        }
     }
     $data = Session::has('itemscustomers') ? Session::get('itemscustomers') : '';

       return response()->json($data);
     //return redirect()->back();
    }

    public function getcustomernamelist($storename)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemcustomer"))
      { 
        $customernamelistsall=Customername::all();
        $customernameslists = Customername::orderBy('id', 'desc')->paginate(50);
        $s="";

        return view('admin.customers.customernameslist',compact('customernamelistsall','customernameslists','s','storename')); 
        }abort(404,"Sorry");
    }

    public function getsearchlistcustomername(Request $request,$storename)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemcustomer"))
      { 
                $s = $request->search;

                $customernamelistsall=CustomerName::where('name', 'LIKE', "%{$s}%")
                ->orWhere('code', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%");
                
                $customernameslists = CustomerName::where('name', 'LIKE', "%{$s}%")
                ->orWhere('code', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(50);
                
                return view('admin.customers.customernameslist',compact('customernamelistsall','customernameslists','s','storename'));
        }abort(404,"Sorry");
    }

    public function getstoreandcustomername($storename,$customernamecode)
    {

          $s = $storename;          
          $store=Store::where('name', 'LIKE', "%{$s}%")->first();
          $categories=Category::all();         
          $items=Item::all();
          
          $data="";
          $stores=Store::all();          
          $staffs=Staff::all();
 
          $customernames=Customername::all();
          $customername=Customername::where('code',$customernamecode)->first();

          return view('admin.customers.create',compact('data','categories','items','store','stores','s','customernames','customername','staffs'));        
    }

    public function getcustomerstaffdata($id) 
    { 
        $positions=DB::table("staff")
            ->join('jobtitles','jobtitles.id','=','staff.jobtitle_id')
            ->where('staff.id',$id)
            ->pluck('jobtitles.name','jobtitles.id');

        return json_encode($positions);
    }

    public function itemcustomercheckout(Request $request,$store_id) 
    { 
        $itemscustomers=Session::has('itemscustomers') ? Session::get('itemscustomers') : null;
        $itemms_ids=array();

        foreach(session('itemscustomers') as $k=>$val)
        {
          if(is_array($val))
          {   
            if($val['itemsserialbyserial'] != "") 
            {
                foreach($val['itemsserialbyserial'] as $s=>$itemserial)
                {
                    array_push($itemms_ids,$itemserial);
                    $itemupdate=Item::whereId($itemserial)->firstOrFail();
                    $itemupdate->used_qty='1';
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
                    $itemupdate->used_qty='1';
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
                    $itemupdate->used_qty='1';
                    $itemupdate->updated_at=Carbon::now()->timestamp;
                    $itemupdate->update();
                }
            }  
          }
        }
       
        if($request->signed)
        {
            $folderPath = public_path().'/images/ftth/customers/'; 

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
        $customer = new Customer;
        $last_customerno = Customer::orderBy('id','DESC')->first();
        if($last_customerno == null)
        { 
          $customer->customer_number = 'CU-000001';
        }
        else
        {
         $customer->customer_number = 'CU-'.str_pad($last_customerno->id + 1, 6, "0", STR_PAD_LEFT);
        }
        $customer->customername_id = $request->get('customername_id');
        $customer->store_id = $store_id;
        $customer->user_id=Auth::user()->id;
        $customer->staff_id=$request->get('staff_id');
        $customer->customer_sign_file=$file;
        $customer->status=$request->get('status');
        $customer->save();
        $customer->items()->sync($itemms_ids);

        Comment::create([
            'content' => Auth::user()->name ." created new Customer Items  ".$customer->customer_number ."(".count($itemms_ids).'items)',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$customer->id ,
            'commendable_type' => "customers"
        
        ]);
        Session::forget('itemscustomers');   

        $categories=null;
            $s="";
            $items=null;
            $store=null;
            $data=null;
            $stores=Store::all();
            $customername="";
            $staffs=Staff::all();

      //return redirect()->back()->with('status', 'Successfully Saved Customer');
      return view('admin.customers.create',['data'=>$data,'categories'=>$categories,'items'=>$items,'store'=>$store,'stores'=>$stores,'s'=>$s,'customername'=>$customername,'staffs'=>$staffs,'status'=>'Successfully Saved Customer']);
       //return view('admin.customers.create',compact('data','categories','items','store','stores','s','customernames','staffs'));
        // 
        // return redirect('/admin/ItemsServerList')->with("status","Server Entry Successfully Added");
    }

    public function destroy($id)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemcustomer"))
       {
        $customer=Customer::whereId($id)->firstOrFail();
        $item_customers=DB::table('customer_item')->where('customer_id',$id)->get();
        foreach($item_customers as $item_customer)
        {
            $items_id=Item::where('id',$item_customer->item_id)->first();
            $items_id->used_qty=0;
            $items_id->updated_at=Carbon::now()->timestamp;          
            $items_id->update();
        }
        if($customer->customer_sign_file)
        {
          $image_path = public_path().'/images/ftth/customers/'.$customer->customer_sign_file;
          unlink($image_path);
        }
         
       
        $customer->items()->detach();
        $customer->delete();

        Comment::create([
            'content' => Auth::user()->name ." deleted Customer  ".$customer->customer_number,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "customers"
        
        ]);
         return redirect()->back()->with(['status'=>'Customer ('.$customer->customer_number.' ) Has Been Deleted']);
     }abort(404,"Sorry");           
    }
   
    public function edit($customer_number)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemcustomer"))
      {
        $customer = Customer::where('customer_number',$customer_number)->first();
        $store=Store::whereId($customer->store_id)->first();
        $categories=Category::all();         
        $items=Item::all();          
        //   $data="";
        $stores=Store::all(); 
        $customername=Customername::where('id',$customer->customername_id)->first();
        $staffs=Staff::all();

        $items=array();
        $itemsarrays=array();
        $itemnamesfrompivot=$customer->items()->pluck('itemname_id')->toArray();
        $itemsfromarrays=array_count_values($itemnamesfrompivot);

        $items_ids=$customer->items()->pluck('item_id')->toArray();
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
      

        session()->put('itemscustomersdb',$itemsarrays); 
        return view('admin.customers.edit',compact( 'categories','items','store','stores','customername','staffs','customer'));
      }abort(404,"Sorry");
    }

    public function removeolditemssamdmcustomer($itemname_id,$id,$customer_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemcustomer"))
      {
        $customer = Customer::whereId($customer_id)->first();       
        $customer->items()->detach($id);

        $items_id=Item::whereId($id)->first();
        $items_id->used_qty=0;
        $items_id->updated_at=Carbon::now()->timestamp;          
        $items_id->update();

        $itemname=Itemname::whereId($itemname_id)->first();
        Comment::create([
             'content' => Auth::user()->name ." deleted Item From Customer ".$customer->customer_number ."& Item Name (".$itemname['name'].")",
              'user_id' => Auth::user()->id,
              'commendable_id' =>$id ,
              'commendable_type' => "customer_street"
          
          ]);
        return redirect()->back()->with(['status'=>'Item for Item Name '.$itemname['name']. ' Has Been Deleted']);

        }abort(404,"Sorry");    
   }

   public function getcustomernamelistupdate($storename,$customer_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemcustomer"))
      { 
        $customernamelistsall=Customername::all();
        $customernameslists = Customername::orderBy('id', 'desc')->paginate(50);
        $s="";

        return view('admin.customers.customernameslistupdate',compact('customernamelistsall','customernameslists','s','storename','customer_id')); 
        }abort(404,"Sorry");
    }

    public function getsearchlistcustomernameupdate(Request $request,$storename,$customer_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemcustomer"))
      { 
                $s = $request->search;

                $customernamelistsall=CustomerName::where('name', 'LIKE', "%{$s}%")
                ->orWhere('code', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%");
                
                $customernameslists = CustomerName::where('name', 'LIKE', "%{$s}%")
                ->orWhere('code', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(50);
                
                return view('admin.customers.customernameslistupdate',compact('customernamelistsall','customernameslists','s','storename','customer_id'));
        }abort(404,"Sorry");
    }

    public function getstoreandcustomernameupdate($customer_id,$customernamecode)
    {

      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemcustomer"))
      {
        $customer = Customer::where('id',$customer_id)->first();
         
        $store=Store::whereId($customer->store_id)->first();
        $categories=Category::all();         
        $items=Item::all();          
        //   $data="";
        $stores=Store::all(); 
        //$customername=Customername::where('id',$customer->customername_id)->first();
        $staffs=Staff::all();
        $customername=Customername::where('code',$customernamecode)->first();
        
         $items=array();
        $itemsarrays=array();
        $itemnamesfrompivot=$customer->items()->pluck('itemname_id')->toArray();
        $itemsfromarrays=array_count_values($itemnamesfrompivot);

        $items_ids=$customer->items()->pluck('item_id')->toArray();
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
      

        session()->put('itemscustomersdb',$itemsarrays);  

        
        return view('admin.customers.edit',compact( 'categories','items','store','stores','customername','staffs','customer'));
      }abort(404,"Sorry");

          // $s = $storename;          
          // $store=Store::where('name', 'LIKE', "%{$s}%")->first();
          // $categories=Category::all();         
          // $items=Item::all();
          
          // $data="";
          // $stores=Store::all();          
          // $staffs=Staff::all();
 
          // $customernames=Customername::all();
          // $customername=Customername::where('code',$customernamecode)->first();

          // return view('admin.customers.edit',compact('data','categories','items','store','stores','s','customernames','customername','staffs'));        
    }

   public function update(Request $request, $customer_number)
   { 
   	 if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemcustomer"))
     {
      $customer = Customer::where('customer_number',$customer_number)->first();    
      $itemscustomers=Session::has('itemscustomers') ? Session::get('itemscustomers') : null;
      $itemms_ids=array();
      
      if(session('itemscustomers'))
      {
        foreach(session('itemscustomers') as $k=>$val)
      {
        if(is_array($val))
        {   
           if($val['itemsserialbyserial'] != "") 
           {
                foreach($val['itemsserialbyserial'] as $s=>$itemserial)
                {
                    array_push($itemms_ids,$itemserial);
                    $itemupdate=Item::whereId($itemserial)->firstOrFail();
                    $itemupdate->used_qty='1';
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
                    $itemupdate->used_qty='1';
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
                    $itemupdate->used_qty='1';
                    $itemupdate->updated_at=Carbon::now()->timestamp;
                    $itemupdate->update();
                }
            }  
        }
      }
     }
     if(session('itemscustomersdb'))
     {
      foreach(session('itemscustomersdb') as $kold=>$valold)
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
            $folderPath = public_path().'/images/ftth/customers/'; 

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
            $file= $customer->customer_sign_file;
        }
   
           $customer->customername_id = $request->get('customername_id');
           $customer->user_id=Auth::user()->id;
           $customer->staff_id=$request->get('staff_id');
           $customer->customer_sign_file=$file;
           $customer->status=$request->get('status');
           $customer->updated_at=Carbon::now()->timestamp;
           $customer->update();
           $customer->items()->sync($itemms_ids);

        Comment::create([
            'content' => Auth::user()->name ." updated  Customer Items  ".$customer->customer_number ."(".count($itemms_ids).'items)',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$customer->id ,
            'commendable_type' => "customers"
        
        ]);
        Session::forget('itemscustomers');       
         return redirect()->back()->with('status', 'Successfully Updated Customer'.$customer->customer_number);
        // 
        // return redirect('/admin/ItemsServerList')->with("status","Server Entry Successfully Added");
        }abort(404,"Sorry");    
       
    }

}
