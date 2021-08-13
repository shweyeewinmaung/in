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
use App\Street;
use App\Streetname;
use App\Item;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use Carbon\Carbon;

class StreetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemstreet"))
       {
        $streetnameslistsall=Streetname::all();
        $streetnameslists = Streetname::orderBy('id', 'desc')->paginate(30);
        $s="";
        return view('admin.streets.list',compact('streetnameslistsall','streetnameslists','s'));
       }abort(404,"Sorry");    
    }
    public function searchlist(Request $request)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemstreet"))
       {
            $s = $request->search;
            $streetnameslistsall=Streetname::where('name', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('street', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%");
            $streetnameslists = Streetname::where('name', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('street', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(30);
                
            return view('admin.streets.list',compact('streetnameslistsall','streetnameslists','s'));
        }abort(404,"Sorry");
    }

    public function streetviewshow(Request $request,$name)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemstreet"))
       {
        $street_namesdatas=Streetname::where('name',$name)->firstOrFail();
        $streetbystreet_names=Street::where('streetname_id',$street_namesdatas['id'])->orderBy('id', 'desc')->paginate(50);
        $streetbystreet_namesall=Street::where('streetname_id',$street_namesdatas['id'])->orderBy('id', 'desc')->get();
        $searchdetail="";
         
        return view('admin.streets.streetviewshow',compact('name','streetbystreet_names','streetbystreet_namesall','searchdetail'));  
        }abort(404,"Sorry");     
    }

    public function searchviewshow(Request $request,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemstreet"))
      {
        $searchdetail = $request->searchdetail;
        $street_namesdatas=Streetname::where('name',$name)->firstOrFail();

        if($searchdetail == null)
        {
            $streetbystreet_names=Street::where('streetname_id',$street_namesdatas['id'])
            ->orderBy('id', 'desc')
            ->paginate(50);

            $streetbystreet_namesall=Street::where('streetname_id',$street_namesdatas['id'])
            ->orderBy('id', 'desc');
        }
        else
        {
            $streetbystreet_names=Street::where('streetname_id',$street_namesdatas['id'])
            ->where('street_number', 'LIKE', "%{$searchdetail}%")
            ->orWhereHas('staff', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhere('status', 'LIKE', "%{$searchdetail}%")
            ->orderBy('id', 'desc')
            ->paginate(50);

             $streetbystreet_namesall=Street::where('streetname_id',$street_namesdatas['id'])
            ->where('street_number', 'LIKE', "%{$searchdetail}%")
             ->orWhereHas('staff', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhere('status', 'LIKE', "%{$searchdetail}%")
            ->orderBy('id', 'desc');
        }

        return view('admin.streets.streetviewshow',compact('name','streetbystreet_names','streetbystreet_namesall','searchdetail')); 
       }abort(404,"Sorry");                
    }

    
    public function streetdetailconfirm(Request $request,$id,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("confirm-itemserver"))
      {
       $street=Street::whereId($id)->firstOrFail();
       $street->confirm_user_id=Auth::user()->id;
       $street->status='confirm';
       $street->updated_at=Carbon::now()->timestamp;

        Comment::create([
            'content' => Auth::user()->name ." Confirm  Street Name  ".$name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "streets"
        
        ]);
        $street->update();
       
        return redirect()->back()->with(['status'=>'Street Name '.$name.' Has Been Confirm']);
      }abort(404,"Sorry");              
    }
    public function streetdetailredoconfirm(Request $request,$id,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("redoconfirm-itemserver"))
      {
       $street=Street::whereId($id)->firstOrFail();
       $street->confirm_user_id="";
       $street->status='pending';
       $street->updated_at=Carbon::now()->timestamp;

        Comment::create([
            'content' => Auth::user()->name ." Redo Confirm for Street Name  ".$name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "streets"
        
        ]);
        $street->update();
       
        return redirect()->back()->with(['status'=>'Street Name '.$name.' Has Been ReConfirm']);
      }abort(404,"Sorry");              
    }

    public function streetviewdetailshow(Request $request,$name,$street_number)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemstreet"))
      {
        $streetdetail=Street::where('street_number',$street_number)->first();
        
        // $serveritemsbydetails=$serverdetail->items()->paginate(30); 
        // $serverbyitemsarrays=$serveritemsbydetails->pluck('id')->toArray();
       
       // $itembydetails=$serverdetail->items()->groupBy('itemname_id')->paginate(1); 
        //dd($aas);
        $itembyname=$streetdetail->items()->pluck('itemname_id')->toArray();
        $itembyname_counts=array_count_values($itembyname);

        $itemsarrays=array();
        
        foreach($itembyname_counts as $k=>$itembyname_count)
        { 
              $itemname=Itemname::where('id',$k)->first();
              $catname=$itemname->category->title;
              $catmac=$itemname->category->mac;
              $catserial=$itemname->category->serial;


            $items = DB::table('items')
                      ->join('item_street', 'item_street.item_id', '=', 'items.id')
                      ->join('streets', 'streets.id', '=', 'item_street.street_id')          
                      ->where('items.itemname_id', '=', $k)
                      ->where('streets.id', $streetdetail['id'])->get();

            $items_damage = DB::table('items')
                      ->join('item_street', 'item_street.item_id', '=', 'items.id')
                      ->join('streets', 'streets.id', '=', 'item_street.street_id')          
                      ->where('items.itemname_id', '=', $k)
                      ->where('items.damage_qty', '=', 1)
                      ->where('streets.id', $streetdetail['id'])->get();
          
 
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
       
        return view('admin.streets.streetviewdetailshow',compact('name','streetdetail','street_number','itemsarrays'));
         
      }abort(404,"Sorry");     
    }

    public function streetviewalldetail(Request $request,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemstreet"))
      {
        $streetname=Streetname::where('name',$name)->firstOrFail();
        $streets=Street::where('streetname_id',$streetname['id'])
        ->where('status','=','confirm')
        ->get();
        
        // $server_items=array();
        // $items=array();
        $itemnamedatas=array();
        $itemsarrays=array();
        $itemdatas=array();
        foreach($streets as $street)
        {
             $streetdata=Street::whereId($street['id'])->first();
             $itembyname=$streetdata->items()->get();
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
                      ->join('item_street', 'item_street.item_id', '=', 'items.id')
                      ->join('streets', 'streets.id', '=', 'item_street.street_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('streets.streetname_id', '=', $streetname->id)
                      ->get();
              $items_damage = DB::table('items')
                      ->join('item_street', 'item_street.item_id', '=', 'items.id')
                      ->join('streets', 'streets.id', '=', 'item_street.street_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('streets.streetname_id', '=', $streetname->id)
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
        //    foreach($itemnamedatas  as $k=>$itemnamedata)
        //    { 
        //     foreach($itemnamedata as $kk=>$aa)
        //     {
        //        $itemname=Itemname::where('id',$kk)->first();
        //    $catname=$itemname->category->title;
        //    $catmac=$itemname->category->mac;
        //    $catserial=$itemname->category->serial;

        //   $items = DB::table('items')
        //               ->join('item_street', 'item_street.item_id', '=', 'items.id')
        //               ->join('streets', 'streets.id', '=', 'item_street.street_id')
        //               ->where('items.itemname_id', '=', $kk)
        //               ->where('streets.streetname_id', '=', $streetname->id)
        //               ->get();
        //   $items_damage = DB::table('items')
        //               ->join('item_street', 'item_street.item_id', '=', 'items.id')
        //               ->join('streets', 'streets.id', '=', 'item_street.street_id')
        //               ->where('items.itemname_id', '=', $kk)
        //               ->where('streets.streetname_id', '=', $streetname->id)
        //               ->where('items.damage_qty', '=', 1)                       
        //               ->get();

        //   if($catserial == 1 || $catmac == 1)
        //   {
        //            $storedItem = ['id'=>$kk, 'itemname'=>$itemname->name,'categoryname'=>$catname,'count' =>$aa,'itemswithsandm'=> $items,'itemsbycount'=>"",'itemsbycountwithcount'=>""];
            
        //           array_push($itemsarrays,$storedItem);
        //   }
        //   else
        //   {
        //     $storedItem = ['id'=>$kk, 'itemname'=>$itemname->name,'categoryname'=>$catname,'count' =>$aa,'itemswithsandm'=> "",'itemsbycount'=>$items,'itemsbycountwithcount'=>$items_damage->count()];
            
        //     array_push($itemsarrays,$storedItem); 
        //   }                      
        // }
     //  }
          
        return view('admin.streets.alldetail',compact('name','streetname','itemsarrays'));
        }
      }abort(404,"Sorry"); 
       
    }
  
    public function itembyitemstreetupdate(Request $request,$id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemstreet"))
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

    public function itembyitemstreetcountupdate(Request $request,$itemname_id,$street_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemstreet"))
      {         
        $items = DB::table('items')
                ->join('item_street', 'item_street.item_id', '=', 'items.id')
                ->join('streets', 'streets.id', '=', 'item_street.street_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                ->where('streets.id', $street_id)
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
                ->join('item_street', 'item_street.item_id', '=', 'items.id')
                ->join('streets', 'streets.id', '=', 'item_street.street_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                ->where('streets.id', $street_id)
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
    
    public function itembyitemstreetredocountupdate(Request $request,$itemname_id,$street_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemstreet"))
      {
        $items = DB::table('items')
                ->join('item_street', 'item_street.item_id', '=', 'items.id')
                ->join('streets', 'streets.id', '=', 'item_street.street_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 1)
                ->where('streets.id', $street_id)->orderBy('items.id','asc')->get();
         
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
                ->join('item_street', 'item_street.item_id', '=', 'items.id')
                ->join('streets', 'streets.id', '=', 'item_street.street_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=',1)
                ->where('streets.id', $street_id)
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
  
   
    public function itembyitemstreetcountupdatealldetail(Request $request,$itemname_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemstreet"))
      {    
          $items = DB::table('items')
                ->join('item_street', 'item_street.item_id', '=', 'items.id')
                ->join('streets', 'streets.id', '=', 'item_street.street_id')          
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
                ->join('item_street', 'item_street.item_id', '=', 'items.id')
                ->join('streets', 'streets.id', '=', 'item_street.street_id')          
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

    public function itembyitemstreetredocountupdatealldetail(Request $request,$itemname_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemstreet"))
      {
        $items = DB::table('items')
                ->join('item_street', 'item_street.item_id', '=', 'items.id')
                ->join('streets', 'streets.id', '=', 'item_street.street_id')          
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
                ->join('item_street', 'item_street.item_id', '=', 'items.id')
                ->join('streets', 'streets.id', '=', 'item_street.street_id')          
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
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemstreet"))
      {
            $categories=null;
            $s="";
            $items=null;
            $store=null;
            $data=null;
            $stores=Store::all();
            $streetnames=Streetname::all();
            $staffs=Staff::all();
            if(session('itemsstreets') || session('itemsstreetsserials') ||session('itemsstreetsmacs'))
            {
              Session::forget('itemsstreets');
              Session::forget('itemsstreetsserials');
              Session::forget('itemsstreetsmacs');
            }
          
            return view('admin.streets.create',compact('data','categories','items','store','stores','s','streetnames','staffs'));  
        }abort(404,"Sorry");    
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemstreet"))
        {
          $s = $request->search;
          $store=Store::where('name', 'LIKE', "%{$s}%")->first();
          $categories=Category::all();         
          $items=Item::all();
          
          $data="";
          $stores=Store::all(); 
          $streetnames=Streetname::all();
          $staffs=Staff::all();
         // dd($store->id);
          if(session('itemsstreets'))
          {
            // $aa=array_map(function($x){
            //   return $x['store_id'];
            // },session('itemsservers'));
 
            foreach(session('itemsstreets') as $k=>$val)
            {
              if(is_array($val))
              {
                if($val['store_id']!=$store->id)
                {
                  Session::forget('itemsstreets');
                  Session::forget('itemsstreetsserials');
                  Session::forget('itemsstreetsmacs');
                }
              }
            }
          }
       
         
         return view('admin.streets.create',compact('data','categories','items','store','stores','s','streetnames','staffs'));
        }abort(404,"Sorry"); 
    }

    public function savestreetserial(Request $request,$itemname_id,$store_id)
    {        
        if($request->serial == null)
        {
            Session::forget('itemsstreetsserials');
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
            $request->session()->put('itemsstreetsserials',$itemsserials);
            return redirect()->back()->with('status', 'New Serial Successfully Saved');
        }        
    }
    public function additemstreetserial(Request $request,$id,$store_id)
    { 
        // Session::forget('itemsserials');
        //     Session::forget('itemsservers'); 
         $dataserials = Session::has('itemsstreetsserials') ? Session::get('itemsstreetsserials') : '';
         
         if($dataserials == "")
         {
            $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';
           //return redirect()->back()->with('errorstatus', 'Please Check Serial Number');
            return response()->json($data)->with(['errorstatus'=>'Please Check Serial Number']);
         }
         else
         { 
              $itemsstreets=array();
              $ids=array();
              $itemsserialbyserial=array();
              
             if($request->session()->has('itemsstreets'))
             {  
                $itemsstreets=$request->session()->get('itemsstreets');
                
                foreach($itemsstreets as $itemsserver)
                {                     
                  $id1=$itemsserver['id'];
                  array_push($ids,$id1);
                }
                  if(!in_array($id, $ids))
                  {
                    $itemnamebyid=Itemname::whereId($id)->first();
                    $catbyid=$itemnamebyid->category->title;
                    $count=0;
                  
                    $itemsstreetsserials=$request->session()->get('itemsstreetsserials');
                   foreach($itemsstreetsserials[0]['same_id'] as $itemsserial)
                   {
                       $count+=1;
                       array_push($itemsserialbyserial,$itemsserial);
                   }
                    //dd($count);
                  $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>$itemsserialbyserial,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
                    array_push($itemsstreets,$storedItem);
                    Session::forget('itemsstreetsserials');
                    $request->session()->put('itemsstreets',$itemsstreets);
                    $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';
                    return response()->json($data);
                    //return redirect()->back()->with('status', 'Successfully Saved');
                       
                  } 
             }
            else
            {                  
                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                $count=0;               
                $itemsstreetsserials=$request->session()->get('itemsstreetsserials');

               foreach($itemsstreetsserials[0]['same_id'] as $itemsserial)
               {
                   $count+=1;
                   array_push($itemsserialbyserial,$itemsserial);
               }
               
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>$itemsserialbyserial,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
                array_push($itemsstreets,$storedItem);
                Session::forget('itemsstreetsserials');
                 $request->session()->put('itemsstreets',$itemsstreets);
                $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';
                return response()->json($data);
                //return redirect()->back()->with('status', 'Successfully Saved');
            }
           
         }                                     
    }
    public function savestreetmac(Request $request,$itemname_id,$store_id)
    {        
        if($request->mac == null)
        {
            Session::forget('itemsstreetsmacs');
            return redirect()->back()->with('errorstatus', 'Please check for MAC');
        }
        else 
        {
            $itemsstreetsmacs=array();
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
            
            array_push($itemsstreetsmacs,$storedItemMac) ;
            $request->session()->put('itemsstreetsmacs',$itemsstreetsmacs);
            return redirect()->back()->with('status', 'New MAC Successfully Saved');
        }        
    }

    public function additemstreetmac(Request $request,$id,$store_id)
    { 
        // Session::forget('itemsmacs');
        //     Session::forget('itemsservers'); 
         $datamacs = Session::has('itemsstreetsmacs') ? Session::get('itemsstreetsmacs') : '';
       
         if($datamacs == "")
         {
            $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';
            //return redirect()->back()->with('errorstatus', 'Please Check Serial Number');
            return response()->json($data)->with(['errorstatus'=>'Please Check MAC Number']);
         }
         else
         { 
              $itemsstreets=array();
              $ids=array();
              $itemsserialbyserial=array();
              
             if($request->session()->has('itemsstreets'))
             {  
                $itemsstreets=$request->session()->get('itemsstreets');
                
                foreach($itemsstreets as $itemsserver)
                {                     
                  $id1=$itemsserver['id'];
                  array_push($ids,$id1);
                }
                  if(!in_array($id, $ids))
                  {
                    $itemnamebyid=Itemname::whereId($id)->first();
                    $catbyid=$itemnamebyid->category->title;
                    $count=0;
                  
                    $itemsstreetsmacs=$request->session()->get('itemsstreetsmacs');
                   foreach($itemsstreetsmacs[0]['same_id'] as $itemsmac)
                   {
                       $count+=1;
                       array_push($itemsserialbyserial,$itemsmac);
                   }
                    //dd($count);
                  $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>$itemsserialbyserial,'itemsserialbycount'=>""];
                    array_push($itemsstreets,$storedItem);
                    Session::forget('itemsstreetsmacs');
                   // dd($itemsservers);
                    $request->session()->put('itemsstreets',$itemsstreets);
                    $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';
                    return response()->json($data);
                    //return redirect()->back()->with('status', 'Successfully Saved');
                       
                  } 
             }
            else
            {   

                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                $count=0;               
                $itemsstreetsmacs=$request->session()->get('itemsstreetsmacs');
               
               foreach($itemsstreetsmacs[0]['same_id'] as $itemsmac)
               {
                   $count+=1;
                   array_push($itemsserialbyserial,$itemsmac);
               }
               
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>$itemsserialbyserial,'itemsserialbycount'=>""];
                array_push($itemsstreets,$storedItem);
               //dd($itemsservers);
                Session::forget('itemsstreetsmacs');
                $request->session()->put('itemsstreets',$itemsstreets);
                $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';
               return response()->json($data);
              //return redirect()->back()->with('status', 'Successfully Saved');
            }
           
         }  
                                   
    }

    public function additemsstreetcount(Request $request,$id,$store_id,$count)
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
          $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';
          return response()->json($data)->with(['statuserror'=>'Insert not greater than avalible Item']);           
        }

        $itemsstreets=array();
        $ids=array();
        $idsbycount=array();
        foreach($itemstakecounts as $item)
        {
            array_push($idsbycount,$item->id);
        }
        
        if($request->session()->has('itemsstreets'))
        {
           $itemsstreets=$request->session()->get('itemsstreets');
           foreach($itemsstreets as $itemsserver)
           {
              $id1=$itemsserver['id'];
              array_push($ids,$id1);            

           }

          if(!in_array($id, $ids))
          {
                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>"",'itemsserialbycount'=>$idsbycount];
            array_push($itemsstreets,$storedItem);          
          }               
        }
        else
        {
           $itemnamebyid=Itemname::whereId($id)->first();
           $catbyid=$itemnamebyid->category->title;

            $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>"",'itemsserialbycount'=>$idsbycount];
            array_push($itemsstreets,$storedItem);          
        }

        $request->session()->put('itemsstreets',$itemsstreets);
        
        $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';
        return response()->json($data);
        //return redirect()->back()->with('status', 'Successfully Saved');
    }
    public function remove_itemstreetsitems($id)
    { 
     $itemsstreets = Session::get('itemsstreets');
     foreach($itemsstreets as $key=>$item)
     {
        if($id == $item['id'])
        {
         unset($itemsstreets[$key]);
         Session()->put('itemsstreets',$itemsstreets);
        }
     }
     $data = Session::has('itemsstreets') ? Session::get('itemsstreets') : '';

       return response()->json($data);
     //return redirect()->back();
    }
    public function getstreetstaffdata($id) 
    { 
        $positions=DB::table("staff")
            ->join('jobtitles','jobtitles.id','=','staff.jobtitle_id')
            ->where('staff.id',$id)
            ->pluck('jobtitles.name','jobtitles.id');

        return json_encode($positions);
    }

    public function itemstreetcheckout(Request $request,$store_id) 
    { 
        $itemsstreets=Session::has('itemsstreets') ? Session::get('itemsstreets') : null;
        $itemms_ids=array();

        foreach(session('itemsstreets') as $k=>$val)
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
            $folderPath = public_path().'/images/ftth/streets/'; 

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
        $street = new Street;
        $last_streetno = Street::orderBy('id','DESC')->first();
        if($last_streetno == null)
        { 
          $street->street_number = 'ST-000001';
        }
        else
        {
         $street->street_number = 'ST-'.str_pad($last_streetno->id + 1, 6, "0", STR_PAD_LEFT);
        }
        $street->streetname_id = $request->get('streetname_id');
        $street->store_id = $store_id;
        $street->user_id=Auth::user()->id;
        $street->staff_id=$request->get('staff_id');
        $street->street_sign_file=$file;
        $street->status=$request->get('status');
        $street->save();
        $street->items()->sync($itemms_ids);

        Comment::create([
            'content' => Auth::user()->name ." created new Street Items  ".$street->street_number ."(".count($itemms_ids).'items)',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$street->id ,
            'commendable_type' => "streets"
        
        ]);
        Session::forget('itemsstreets');       
       return redirect()->back()->with('status', 'Successfully Saved Street');
        // 
        // return redirect('/admin/ItemsServerList')->with("status","Server Entry Successfully Added");
    }

    public function destroy($id)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemstreet"))
       {
        $street=Street::whereId($id)->firstOrFail();
        $item_streets=DB::table('item_street')->where('street_id',$id)->get();
        foreach($item_streets as $item_street)
        {
            $items_id=Item::where('id',$item_street->item_id)->first();
            $items_id->used_qty=0;
            $items_id->updated_at=Carbon::now()->timestamp;          
            $items_id->update();
        }
        if($street->street_sign_file)
        {
          $image_path = public_path().'/images/ftth/streets/'.$street->street_sign_file;
          unlink($image_path);
        }
         
       
        $street->items()->detach();
        $street->delete();

        Comment::create([
            'content' => Auth::user()->name ." deleted Street  ".$street->street_number,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "streets"
        
        ]);
         return redirect()->back()->with(['status'=>'Street ('.$street->street_number.' ) Has Been Deleted']);
     }abort(404,"Sorry");           
    }

    public function edit($street_number)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemstreet"))
      {
        $street = Street::where('street_number',$street_number)->first();
        $store=Store::whereId( $street->store_id)->first();
        $categories=Category::all();         
        $items=Item::all();          
        //   $data="";
        $stores=Store::all(); 
        $streetnames=Streetname::all();
        $staffs=Staff::all();

        $items=array();
        $itemsarrays=array();
        $itemnamesfrompivot=$street->items()->pluck('itemname_id')->toArray();
        $itemsfromarrays=array_count_values($itemnamesfrompivot);

        $items_ids=$street->items()->pluck('item_id')->toArray();
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
      

        session()->put('itemsstreetsdb',$itemsarrays); 
        return view('admin.streets.edit',compact( 'categories','items','store','stores','streetnames','staffs','street'));
      }abort(404,"Sorry");       
        
      
    }

    public function removeolditemssamdmstreet($itemname_id,$id,$street_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemstreet"))
      {
        $street = Street::whereId($street_id)->first();       
        $street->items()->detach($id);

        $items_id=Item::whereId($id)->first();
        $items_id->used_qty=0;
        $items_id->updated_at=Carbon::now()->timestamp;          
        $items_id->update();

        $itemname=Itemname::whereId($itemname_id)->first();
        Comment::create([
             'content' => Auth::user()->name ." deleted Item From Street ".$street->street_number ."& Item Name (".$itemname['name'].")",
              'user_id' => Auth::user()->id,
              'commendable_id' =>$id ,
              'commendable_type' => "item_street"
          
          ]);
        return redirect()->back()->with(['status'=>'Item for Item Name '.$itemname['name']. ' Has Been Deleted']);

        }abort(404,"Sorry");    
   }

   public function update(Request $request, $street_number)
   { 
   	 if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemstreet"))
     {
      $street = Street::where('street_number',$street_number)->first();    
      $itemsstreets=Session::has('itemsstreets') ? Session::get('itemsstreets') : null;
      $itemms_ids=array();
      
      if(session('itemsstreets'))
      {
        foreach(session('itemsstreets') as $k=>$val)
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
     if(session('itemsstreetsdb'))
     {
      foreach(session('itemsstreetsdb') as $kold=>$valold)
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
            $folderPath = public_path().'/images/ftth/streets/'; 

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
            $file= $street->street_sign_file;
        }
   
           $street->streetname_id = $request->get('streetname_id');
           $street->user_id=Auth::user()->id;
           $street->staff_id=$request->get('staff_id');
           $street->street_sign_file=$file;
           $street->status=$request->get('status');
           $street->updated_at=Carbon::now()->timestamp;
           $street->update();
           $street->items()->sync($itemms_ids);

        Comment::create([
            'content' => Auth::user()->name ." updated  Street Items  ".$street->street_number ."(".count($itemms_ids).'items)',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$street->id ,
            'commendable_type' => "streets"
        
        ]);
        Session::forget('itemsstreets');       
         return redirect()->back()->with('status', 'Successfully Updated Street'.$street->street_number);
        // 
        // return redirect('/admin/ItemsServerList')->with("status","Server Entry Successfully Added");
        }abort(404,"Sorry");    
       
    }


}
