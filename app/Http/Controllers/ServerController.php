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
use App\Server;
use App\ServerName;
use App\Item;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use Carbon\Carbon;

class ServerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemserver"))
       {
        $servernameslistsall=ServerName::all();
        $servernameslists = ServerName::orderBy('id', 'desc')->paginate(30);
        $s="";
        return view('admin.servers.list',compact('servernameslistsall','servernameslists','s'));
       }abort(404,"Sorry");    
    }
    public function searchlist(Request $request)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemserver"))
       {
            $s = $request->search;
            $servernameslistsall=ServerName::where('name', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%");
            $servernameslists = ServerName::where('name', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(30);
                
            return view('admin.servers.list',compact('servernameslistsall','servernameslists','s'));
        }abort(404,"Sorry");
    }
    public function serverviewshow(Request $request,$name)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemserver"))
       {
        $server_namesdatas=ServerName::where('name',$name)->firstOrFail();
        $serverbyserver_names=Server::where('servername_id',$server_namesdatas['id'])->orderBy('id', 'desc')->paginate(50);
        $serverbyserver_namesall=Server::where('servername_id',$server_namesdatas['id'])->orderBy('id', 'desc')->get();
        $searchdetail="";
         
        return view('admin.servers.serverviewshow',compact('name','serverbyserver_names','serverbyserver_namesall','searchdetail'));  
        }abort(404,"Sorry");     
    }
    public function searchviewshow(Request $request,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemserver"))
      {
        $searchdetail = $request->searchdetail;
        $server_namesdatas=ServerName::where('name',$name)->firstOrFail();

        if($searchdetail == null)
        {
            $serverbyserver_names=Server::where('servername_id',$server_namesdatas['id'])
            ->orderBy('id', 'desc')
            ->paginate(50);

            $serverbyserver_namesall=Server::where('servername_id',$server_namesdatas['id'])
            ->orderBy('id', 'desc');
        }
        else
        {
            $serverbyserver_names=Server::where('servername_id',$server_namesdatas['id'])
            ->where('server_number', 'LIKE', "%{$searchdetail}%")
            ->orWhereHas('staff', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhere('status', 'LIKE', "%{$searchdetail}%")
            ->orderBy('id', 'desc')
            ->paginate(50);

             $serverbyserver_namesall=Server::where('servername_id',$server_namesdatas['id'])
            ->where('server_number', 'LIKE', "%{$searchdetail}%")
             ->orWhereHas('staff', function($q) use ($searchdetail){
              return $q->where('name','like','%'. $searchdetail . '%');
            })
            ->orWhere('status', 'LIKE', "%{$searchdetail}%")
            ->orderBy('id', 'desc');
        }

        return view('admin.servers.serverviewshow',compact('name','serverbyserver_names','serverbyserver_namesall','searchdetail')); 
       }abort(404,"Sorry");                
    }
    public function serverdetailconfirm(Request $request,$id,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("confirm-itemserver"))
      {
       $server=Server::whereId($id)->firstOrFail();
       $server->confirm_user_id=Auth::user()->id;
       $server->status='confirm';
       $server->updated_at=Carbon::now()->timestamp;

        Comment::create([
            'content' => Auth::user()->name ." Confirm  Server Name  ".$name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "servers"
        
        ]);
        $server->update();
       
        return redirect()->back()->with(['status'=>'Server Name '.$name.' Has Been Confirm']);
      }abort(404,"Sorry");              
    }
    public function serverdetailredoconfirm(Request $request,$id,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("redoconfirm-itemserver"))
      {
       $server=Server::whereId($id)->firstOrFail();
       $server->confirm_user_id="";
       $server->status='pending';
       $server->updated_at=Carbon::now()->timestamp;

        Comment::create([
            'content' => Auth::user()->name ." Redo Confirm for Server Name  ".$name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "servers"
        
        ]);
        $server->update();
       
        return redirect()->back()->with(['status'=>'Server Name '.$name.' Has Been Confirm']);
      }abort(404,"Sorry");              
    }
    public function serverviewdetailshow(Request $request,$name,$server_number)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemserver"))
      {
        $serverdetail=Server::where('server_number',$server_number)->first();
        
        // $serveritemsbydetails=$serverdetail->items()->paginate(30); 
        // $serverbyitemsarrays=$serveritemsbydetails->pluck('id')->toArray();
       
       // $itembydetails=$serverdetail->items()->groupBy('itemname_id')->paginate(1); 
        //dd($aas);
        $itembyname=$serverdetail->items()->pluck('itemname_id')->toArray();
        $itembyname_counts=array_count_values($itembyname);

        $itemsarrays=array();
        
        foreach($itembyname_counts as $k=>$itembyname_count)
        { 
              $itemname=Itemname::where('id',$k)->first();
              $catname=$itemname->category->title;
              $catmac=$itemname->category->mac;
              $catserial=$itemname->category->serial;


            $items = DB::table('items')
                      ->join('item_server', 'item_server.item_id', '=', 'items.id')
                      ->join('servers', 'servers.id', '=', 'item_server.server_id')          
                      ->where('items.itemname_id', '=', $k)
                      ->where('servers.id', $serverdetail['id'])->get();

            $items_damage = DB::table('items')
                      ->join('item_server', 'item_server.item_id', '=', 'items.id')
                      ->join('servers', 'servers.id', '=', 'item_server.server_id')          
                      ->where('items.itemname_id', '=', $k)
                      ->where('items.damage_qty', '=', 1)
                      ->where('servers.id', $serverdetail['id'])->get();
          
 
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
       
        return view('admin.servers.serverviewdetailshow',compact('name','serverdetail','server_number','itemsarrays'));
         
      }abort(404,"Sorry");     
    }

    public function serverviewalldetail(Request $request,$name)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itemserver"))
      {
        $servername=ServerName::where('name',$name)->firstOrFail();
        $servers=Server::where('servername_id',$servername['id'])
        ->where('status','=','confirm')
        ->get();
        
        // $server_items=array();
        // $items=array();
        $itemnamedatas=array();
        $itemsarrays=array();
        $itemdatas=array();
        foreach($servers as $server)
        {
             $serverdata=Server::whereId($server['id'])->first();
             $itembyname=$serverdata->items()->get();
             array_push($itemnamedatas,$itembyname);
            // $server_item=$serverdata->items()->get();
             // $itembyname=$serverdata->items()->pluck('itemname_id')->toArray();
             // $itembyname_counts=array_count_values($itembyname);
             // array_push($itemnamedatas,$itembyname_counts);
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
           return view('admin.servers.alldetail',compact('name','servername','itemsarrays'));
        }
        else
        {
          // foreach($itemnamedatas  as $k=>$itemnamedata)
          // { 
          foreach($itemnamebyitem_counts as $k=>$itemnamebyitem_count)
          {
              $itemname=Itemname::where('id',$k)->first();
              $catname=$itemname->category->title;
              $catmac=$itemname->category->mac;
              $catserial=$itemname->category->serial;

              $items = DB::table('items')
                      ->join('item_server', 'item_server.item_id', '=', 'items.id')
                      ->join('servers', 'servers.id', '=', 'item_server.server_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('servers.servername_id', '=', $servername->id)
                      ->get();
              $items_damage = DB::table('items')
                      ->join('item_server', 'item_server.item_id', '=', 'items.id')
                      ->join('servers', 'servers.id', '=', 'item_server.server_id')
                      ->where('items.itemname_id', '=', $k)
                      ->where('servers.servername_id', '=', $servername->id)
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
        //     foreach($itemnamedata as $kk=>$aa)
        //     {
        //       $itemname=Itemname::where('id',$kk)->first();
        //    $catname=$itemname->category->title;
        //    $catmac=$itemname->category->mac;
        //    $catserial=$itemname->category->serial;

        //   $items = DB::table('items')
        //               ->join('item_server', 'item_server.item_id', '=', 'items.id')
        //               ->join('servers', 'servers.id', '=', 'item_server.server_id')
        //               ->where('items.itemname_id', '=', $kk)
        //               ->where('servers.servername_id', '=', $servername->id)
        //               ->get();
        //   $items_damage = DB::table('items')
        //               ->join('item_server', 'item_server.item_id', '=', 'items.id')
        //               ->join('servers', 'servers.id', '=', 'item_server.server_id')
        //               ->where('items.itemname_id', '=', $kk)
        //               ->where('servers.servername_id', '=', $servername->id)
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
        //}
           
        return view('admin.servers.alldetail',compact('name','servername','itemsarrays'));
        }
       }abort(404,"Sorry");  
       
    }

    public function itembyitemserverupdate(Request $request,$id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemserver"))
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
    public function itembyitemservercountupdate(Request $request,$itemname_id,$server_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemserver"))
      {         
        $items = DB::table('items')
                ->join('item_server', 'item_server.item_id', '=', 'items.id')
                ->join('servers', 'servers.id', '=', 'item_server.server_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                ->where('servers.id', $server_id)
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
                ->join('item_server', 'item_server.item_id', '=', 'items.id')
                ->join('servers', 'servers.id', '=', 'item_server.server_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 0)
                ->where('servers.id', $server_id)
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
    public function itembyitemservercountupdatealldetail(Request $request,$itemname_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemserver"))
      {    
          $items = DB::table('items')
                ->join('item_server', 'item_server.item_id', '=', 'items.id')
                ->join('servers', 'servers.id', '=', 'item_server.server_id')          
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
                ->join('item_server', 'item_server.item_id', '=', 'items.id')
                ->join('servers', 'servers.id', '=', 'item_server.server_id')          
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


    public function itembyitemserverredocountupdate(Request $request,$itemname_id,$server_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemserver"))
      {
        $items = DB::table('items')
                ->join('item_server', 'item_server.item_id', '=', 'items.id')
                ->join('servers', 'servers.id', '=', 'item_server.server_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=', 1)
                ->where('servers.id', $server_id)->orderBy('items.id','asc')->get();
         
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
                ->join('item_server', 'item_server.item_id', '=', 'items.id')
                ->join('servers', 'servers.id', '=', 'item_server.server_id')          
                ->where('items.itemname_id', '=', $itemname_id)
                ->where('items.qty', '=', 1)
                ->where('items.damage_qty', '=',1)
                ->where('servers.id', $server_id)
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
    public function itembyitemserverredocountupdatealldetail(Request $request,$itemname_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemserver"))
      {
        $items = DB::table('items')
                ->join('item_server', 'item_server.item_id', '=', 'items.id')
                ->join('servers', 'servers.id', '=', 'item_server.server_id')          
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
                ->join('item_server', 'item_server.item_id', '=', 'items.id')
                ->join('servers', 'servers.id', '=', 'item_server.server_id')          
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
            'content' => Auth::user()->name ." updated Redo Item Damage Count for".$itemname_id."(".$request->countdamage_qty.")" ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$itemname_id,
            'commendable_type' => "items"
        
        ]);
        return redirect()->back()->with(['status'=>'Item Name '.$itemname_id.'('.$request->countdamage_qty.') Has Been Updated']);
        }     
       

      }abort(404,"Sorry");     
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemserver"))
      {
            $categories=null;
            $s="";
            $items=null;
            $store=null;
            $data=null;
            $stores=Store::all();
            $servernames=ServerName::all();
            $staffs=Staff::all();
            if(session('itemsservers') || session('itemsserials') ||session('itemsmacs'))
            {
              Session::forget('itemsservers');
              Session::forget('itemsserials');
              Session::forget('itemsmacs');
            }
          
            return view('admin.servers.create',compact('data','categories','items','store','stores','s','servernames','staffs'));  
        }abort(404,"Sorry");    
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemserver"))
        {
          $s = $request->search;
          $store=Store::where('name', 'LIKE', "%{$s}%")->first();
          $categories=Category::all();         
          $items=Item::all();
          
          $data="";
          $stores=Store::all(); 
          $servernames=ServerName::all();
          $staffs=Staff::all();
         // dd($store->id);
          if(session('itemsservers'))
          {
            // $aa=array_map(function($x){
            //   return $x['store_id'];
            // },session('itemsservers'));
 
            foreach(session('itemsservers') as $k=>$val)
            {
              if(is_array($val))
              {
                if($val['store_id']!=$store->id)
                {
                  Session::forget('itemsservers');
                  Session::forget('itemsserials');
                  Session::forget('itemsmacs');
                }
              }
            }
          }
       
         
         return view('admin.servers.create',compact('data','categories','items','store','stores','s','servernames','staffs'));
        }abort(404,"Sorry"); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($server_number)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemserver"))
      {
        $server = Server::where('server_number',$server_number)->first();
        $store=Store::whereId( $server->store_id)->first();
        $categories=Category::all();         
        $items=Item::all();          
        //   $data="";
        $stores=Store::all(); 
        $servernames=ServerName::all();
        $staffs=Staff::all();

        $items=array();
        $itemsarrays=array();
        $itemnamesfrompivot=$server->items()->pluck('itemname_id')->toArray();
        $itemsfromarrays=array_count_values($itemnamesfrompivot);

        $items_ids=$server->items()->pluck('item_id')->toArray();
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
      

        session()->put('itemsserversdb',$itemsarrays); 
        return view('admin.servers.edit',compact( 'categories','items','store','stores','servernames','staffs','server'));
      }abort(404,"Sorry");       
        
      
    }
    public function removeolditemssamdm($itemname_id,$id,$server_id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemserver"))
      {
        $server = Server::whereId($server_id)->first();       
        $server->items()->detach($id);

        $items_id=Item::whereId($id)->first();
        $items_id->used_qty=0;
        $items_id->updated_at=Carbon::now()->timestamp;          
        $items_id->update();

        $itemname=Itemname::whereId($itemname_id)->first();
        Comment::create([
             'content' => Auth::user()->name ." deleted Item From Server ".$server->server_number ."& Item Name (".$itemname['name'].")",
              'user_id' => Auth::user()->id,
              'commendable_id' =>$id ,
              'commendable_type' => "item_server"
          
          ]);
        return redirect()->back()->with(['status'=>'Item for Item Name '.$itemname['name']. ' Has Been Deleted']);

        }abort(404,"Sorry");    
   }
   //  public function removeolditemscount($itemname_id,$server_id)
   //  {
   //    if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemserver"))
   //    {
   //      $server = Server::where('id',$server_id)->first();       
   //      $itemsfrompivots=$server->items()->pluck('item_id')->toArray();
   //      $itemname=Itemname::whereId($itemname_id)->first();
   //      $items = DB::table('items')->whereIn('id', $itemsfrompivots)->where('itemname_id',$itemname_id)->get();
   //      //dd($items);
   //      // dd($itemname);
   //    //  $itemsfrompivots=DB::table('item_server')->where('server_id',$server_id)->get();
   //      $itemsarrays=array();
   //      foreach($items as $k=>$item)
   //      {
   //        $server->items()->detach($item->id);
   //        $items_id=Item::where('id',$item->id)->first();
   //        $items_id->used_qty=0;
   //        $items_id->updated_at=Carbon::now()->timestamp;
   //        $items_id->update();           
   //       }
       
   //      Comment::create([
   //           'content' => Auth::user()->name ." deleted Item From Server ".$server->server_number ."& Item Name (".$itemname['name'].")",
   //            'user_id' => Auth::user()->id,
   //            'commendable_id' =>$itemname_id ,
   //            'commendable_type' => "item_server"
          
   //        ]);
   //       return redirect()->back()->with(['status'=>'Item for Item Name '.$itemname['name']. ' Has Been Deleted']);

   //      }abort(404,"Sorry");    
   // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $server_number)
    { 
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemserver"))
     {
      $server = Server::where('server_number',$server_number)->first();    
      $itemsservers=Session::has('itemsservers') ? Session::get('itemsservers') : null;
      $itemms_ids=array();
      
      if(session('itemsservers'))
      {
        foreach(session('itemsservers') as $k=>$val)
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
     if(session('itemsserversdb'))
     {
      foreach(session('itemsserversdb') as $kold=>$valold)
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
            $folderPath = public_path().'/images/ftth/servers/'; 

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
            $file= $server->sign_file;
        }
   
           $server->servername_id = $request->get('servername_id');
           $server->user_id=Auth::user()->id;
           $server->staff_id=$request->get('staff_id');
           $server->sign_file=$file;
           $server->status=$request->get('status');
           $server->updated_at=Carbon::now()->timestamp;
           $server->update();
           $server->items()->sync($itemms_ids);

        Comment::create([
            'content' => Auth::user()->name ." updated  Server Items  ".$server->server_number ."(".count($itemms_ids).'items)',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$server->id ,
            'commendable_type' => "servers"
        
        ]);
        Session::forget('itemsservers');       
         return redirect()->back()->with('status', 'Successfully Updated SERVER'.$server->server_number);
        // 
        // return redirect('/admin/ItemsServerList')->with("status","Server Entry Successfully Added");
        }abort(404,"Sorry");   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemserver"))
       {
        $server=Server::whereId($id)->firstOrFail();
        $item_servers=DB::table('item_server')->where('server_id',$id)->get();
        foreach($item_servers as $item_server)
        {
            $items_id=Item::where('id',$item_server->item_id)->first();
            $items_id->used_qty=0;
            $items_id->updated_at=Carbon::now()->timestamp;          
            $items_id->update();
        }
        if($server->sign_file)
        {
          $image_path = public_path().'/images/ftth/servers/'.$server->sign_file;
          unlink($image_path);
        }
         
       
        $server->items()->detach();
        $server->delete();

        Comment::create([
            'content' => Auth::user()->name ." deleted Server  ".$server->server_number,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "servers"
        
        ]);
         return redirect()->back()->with(['status'=>'Server ('.$server->server_number.' ) Has Been Deleted']); 
       }abort(404,"Sorry");           
    }
    public function saveserial(Request $request,$itemname_id,$store_id)
    {        
        if($request->serial == null)
        {
            Session::forget('itemsserials');
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
            $request->session()->put('itemsserials',$itemsserials);
            return redirect()->back()->with('status', 'New Serial Successfully Saved');
        }        
    }
    public function additemserverserial(Request $request,$id,$store_id)
    { 
        // Session::forget('itemsserials');
        //     Session::forget('itemsservers'); 
         $dataserials = Session::has('itemsserials') ? Session::get('itemsserials') : '';
         
         if($dataserials == "")
         {
            $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';
           //return redirect()->back()->with('errorstatus', 'Please Check Serial Number');
            return response()->json($data)->with(['errorstatus'=>'Please Check Serial Number']);
         }
         else
         { 
              $itemsservers=array();
              $ids=array();
              $itemsserialbyserial=array();
              
             if($request->session()->has('itemsservers'))
             {  
                $itemsservers=$request->session()->get('itemsservers');
                
                foreach($itemsservers as $itemsserver)
                {                     
                  $id1=$itemsserver['id'];
                  array_push($ids,$id1);
                }
                  if(!in_array($id, $ids))
                  {
                    $itemnamebyid=Itemname::whereId($id)->first();
                    $catbyid=$itemnamebyid->category->title;
                    $count=0;
                  
                    $itemsserials=$request->session()->get('itemsserials');
                   foreach($itemsserials[0]['same_id'] as $itemsserial)
                   {
                       $count+=1;
                       array_push($itemsserialbyserial,$itemsserial);
                   }
                    //dd($count);
                  $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>$itemsserialbyserial,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
                    array_push($itemsservers,$storedItem);
                    Session::forget('itemsserials');
                    $request->session()->put('itemsservers',$itemsservers);
                    $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';
                    return response()->json($data);
                    //return redirect()->back()->with('status', 'Successfully Saved');
                       
                  } 
             }
            else
            {                  
                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                $count=0;               
                $itemsserials=$request->session()->get('itemsserials');

               foreach($itemsserials[0]['same_id'] as $itemsserial)
               {
                   $count+=1;
                   array_push($itemsserialbyserial,$itemsserial);
               }
               
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>$itemsserialbyserial,'itemsserialbymac'=>"",'itemsserialbycount'=>""];
                array_push($itemsservers,$storedItem);
                Session::forget('itemsserials');
                 $request->session()->put('itemsservers',$itemsservers);
                $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';
                return response()->json($data);
                //return redirect()->back()->with('status', 'Successfully Saved');
            }
           
         }                                     
    }
    public function savemac(Request $request,$itemname_id,$store_id)
    {        
        if($request->mac == null)
        {
            Session::forget('itemsmacs');
            return redirect()->back()->with('errorstatus', 'Please check for MAC');
        }
        else 
        {
            $itemsmacs=array();
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
            
            array_push($itemsmacs,$storedItemMac) ;
            $request->session()->put('itemsmacs',$itemsmacs);
            return redirect()->back()->with('status', 'New MAC Successfully Saved');
        }        
    }
    public function additemservermac(Request $request,$id,$store_id)
    { 
        // Session::forget('itemsmacs');
        //     Session::forget('itemsservers'); 
         $datamacs = Session::has('itemsmacs') ? Session::get('itemsmacs') : '';
       
         if($datamacs == "")
         {
            $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';
            //return redirect()->back()->with('errorstatus', 'Please Check Serial Number');
            return response()->json($data)->with(['errorstatus'=>'Please Check MAC Number']);
         }
         else
         { 
              $itemsservers=array();
              $ids=array();
              $itemsserialbyserial=array();
              
             if($request->session()->has('itemsservers'))
             {  
                $itemsservers=$request->session()->get('itemsservers');
                
                foreach($itemsservers as $itemsserver)
                {                     
                  $id1=$itemsserver['id'];
                  array_push($ids,$id1);
                }
                  if(!in_array($id, $ids))
                  {
                    $itemnamebyid=Itemname::whereId($id)->first();
                    $catbyid=$itemnamebyid->category->title;
                    $count=0;
                  
                    $itemsmacs=$request->session()->get('itemsmacs');
                   foreach($itemsmacs[0]['same_id'] as $itemsmac)
                   {
                       $count+=1;
                       array_push($itemsserialbyserial,$itemsmac);
                   }
                    //dd($count);
                  $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>$itemsserialbyserial,'itemsserialbycount'=>""];
                    array_push($itemsservers,$storedItem);
                    Session::forget('itemsmacs');
                   // dd($itemsservers);
                    $request->session()->put('itemsservers',$itemsservers);
                    $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';
                    return response()->json($data);
                    //return redirect()->back()->with('status', 'Successfully Saved');
                       
                  } 
             }
            else
            {   

                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                $count=0;               
                $itemsmacs=$request->session()->get('itemsmacs');
               
               foreach($itemsmacs[0]['same_id'] as $itemsmac)
               {
                   $count+=1;
                   array_push($itemsserialbyserial,$itemsmac);
               }
               
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>$itemsserialbyserial,'itemsserialbycount'=>""];
                array_push($itemsservers,$storedItem);
               //dd($itemsservers);
                Session::forget('itemsmacs');
                $request->session()->put('itemsservers',$itemsservers);
                $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';
               return response()->json($data);
              //return redirect()->back()->with('status', 'Successfully Saved');
            }
           
         }  
                                   
    }
    public function additemsservercount(Request $request,$id,$store_id,$count)
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
          $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';
          return response()->json($data)->with(['statuserror'=>'Insert not greater than avalible Item']);           
        }

        $itemsservers=array();
        $ids=array();
        $idsbycount=array();
        foreach($itemstakecounts as $item)
        {
            array_push($idsbycount,$item->id);
        }
        
        if($request->session()->has('itemsservers'))
        {
           $itemsservers=$request->session()->get('itemsservers');
           foreach($itemsservers as $itemsserver)
           {
              $id1=$itemsserver['id'];
              array_push($ids,$id1);            

           }

          if(!in_array($id, $ids))
          {
                $itemnamebyid=Itemname::whereId($id)->first();
                $catbyid=$itemnamebyid->category->title;
                
                $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>"",'itemsserialbycount'=>$idsbycount];
            array_push($itemsservers,$storedItem);          
          }               
        }
        else
        {
           $itemnamebyid=Itemname::whereId($id)->first();
           $catbyid=$itemnamebyid->category->title;

            $storedItem = ['id'=>$id,'store_id'=>$store_id,'itemname'=>$itemnamebyid->name,'categoryname'=>$catbyid,'count' =>$count,'itemsserialbyserial'=>"",'itemsserialbymac'=>"",'itemsserialbycount'=>$idsbycount];
            array_push($itemsservers,$storedItem);          
        }

        $request->session()->put('itemsservers',$itemsservers);
        
        $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';
        return response()->json($data);
        //return redirect()->back()->with('status', 'Successfully Saved');
    }
   public function remove_itemserversitems($id)
   { 
     $itemsservers = Session::get('itemsservers');
     foreach($itemsservers as $key=>$item)
     {
        if($id == $item['id'])
        {
         unset($itemsservers[$key]);
         Session()->put('itemsservers',$itemsservers);
        }
     }
     $data = Session::has('itemsservers') ? Session::get('itemsservers') : '';

       return response()->json($data);
     //return redirect()->back();
    }
    public function getstaffdata($id) 
    { 
        $positions=DB::table("staff")
            ->join('jobtitles','jobtitles.id','=','staff.jobtitle_id')
            ->where('staff.id',$id)
            ->pluck('jobtitles.name','jobtitles.id');

        return json_encode($positions);
    }
    public function test() 
    { 
          return view('admin.servers.test');
    }
    public function itemsservercheckout(Request $request,$store_id) 
    { 
        $itemsservers=Session::has('itemsservers') ? Session::get('itemsservers') : null;
        $itemms_ids=array();

        foreach(session('itemsservers') as $k=>$val)
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
            $folderPath = public_path().'/images/ftth/servers/'; 

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
        $server = new Server;
        $last_serverno = Server::orderBy('id','DESC')->first();
        if($last_serverno == null)
        { 
          $server->server_number = 'S-000001';
        }
        else
        {
         $server->server_number = 'S-'.str_pad($last_serverno->id + 1, 6, "0", STR_PAD_LEFT);
        }
        $server->servername_id = $request->get('servername_id');
        $server->store_id = $store_id;
        $server->user_id=Auth::user()->id;
        $server->staff_id=$request->get('staff_id');
        $server->sign_file=$file;
        $server->status=$request->get('status');
        $server->save();
        $server->items()->sync($itemms_ids);

        Comment::create([
            'content' => Auth::user()->name ." created new Server Items  ".$server->server_number ."(".count($itemms_ids).'items)',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$server->id ,
            'commendable_type' => "servers"
        
        ]);
        Session::forget('itemsservers');       
       return redirect()->back()->with('status', 'Successfully Saved SERVER');
        // 
        // return redirect('/admin/ItemsServerList')->with("status","Server Entry Successfully Added");
    }
   
}
