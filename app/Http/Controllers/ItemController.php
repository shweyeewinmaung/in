<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use Carbon\Carbon;
use App\Category;
use App\Supplier;
use App\Store;
use App\Comment;
use App\Itemname;
use App\Item;
use App\Voucher;
use App\Http\Requests\ItemFormRequest;

class ItemController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function itemsbuylist()
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itembuy"))
       {
        $s="";
        $voucherlistsall = Voucher::all();
        $voucherlists = Voucher::orderBy('id', 'desc')->paginate(30);

        return view('admin.itembuys.list',compact('voucherlistsall','voucherlists','s'));
       
       }abort(404,"Sorry");
    }
    public function searchitemsbuy(Request $request)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itembuy"))
      {
       $s = $request->search;

       $voucherlistsall= Voucher::where('voucher_code', 'LIKE', "%{$s}%")
         ->orWhereHas('supplier', function($q) use ($s){
          return $q->where('name','like','%'. $s . '%');
        })
        ->orWhereHas('store', function($q) use ($s){
          return $q->where('name','like','%'. $s . '%');
        });

       $voucherlists= Voucher::where('voucher_code', 'LIKE', "%{$s}%")
         ->orWhereHas('supplier', function($q) use ($s){
          return $q->where('name','like','%'. $s . '%');
        })
        ->orWhereHas('store', function($q) use ($s){
          return $q->where('name','like','%'. $s . '%');
        })
        ->orderBy('id','asc')
        ->paginate(30);

        return view('admin.itembuys.list',compact('voucherlistsall','voucherlists','s'));
       }abort(404,"Sorry");
    }

    public function entry()
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itembuy"))
       {
         $categories=Category::all();
         $suppliers=Supplier::all();
         $stores=Store::all();
         return view('admin.itembuys.create',compact('categories','suppliers','stores'));
       }abort(404,"Sorry");
    }
    public function getitemname($id) 
    {         
        $names = DB::table("itemnames")->where("category_id",$id)->pluck("name","id");       
        return json_encode($names);
    }

    public function additemsbuy(Request $request,$itemname_id,$category_id,$qty,$amount,$mac)
    { 
      $itemnames_id=$itemname_id;
      if($mac=="null")
      {
        $mac="";
      }
      
      $itemsbuy=array();
      $ids=array();
      if($request->session()->has('itemsbuycreate'))
      {
       $itemsbuy=$request->session()->get('itemsbuycreate');

           foreach($itemsbuy as $item)
           {
              $id1=$item['id'];
              array_push($ids,$id1);
           }

           if(!in_array($itemnames_id, $ids))
           {
                $catbyid=Category::whereId($category_id)->first();
                $itemnamebyid=Itemname::whereId($itemnames_id)->first();
                $storedItemsBuy = ['id'=>$itemnames_id,'itemname'=>$itemnamebyid['name'],'categoryname'=>$catbyid['title'],'qty' =>$qty,'amount'=>$amount,'mac'=>$mac];
                array_push($itemsbuy,$storedItemsBuy);
           }
      }
      else
      {
        $catbyid=Category::whereId($category_id)->first();
        $itemnamebyid=Itemname::whereId($itemnames_id)->first();
        $storedItemsBuy = ['id'=>$itemnames_id,'itemname'=>$itemnamebyid['name'],'categoryname'=>$catbyid['title'],'qty' =>$qty,'amount'=>$amount,'mac'=>$mac];
        array_push($itemsbuy,$storedItemsBuy);
      }
      $request->session()->put('itemsbuycreate',$itemsbuy);
      //$request->session()->flush();
      //return redirect()->back();
    
       $data = Session::has('itemsbuycreate') ? Session::get('itemsbuycreate') : '';
       return response()->json($data);
    }
    public function remove_itemsbuy($id)
    { 
     $itemsbuy = Session::get('itemsbuycreate');
     foreach($itemsbuy as $key=>$item)
     {
        if($id == $item['id'])
        {
         unset($itemsbuy[$key]);
         Session()->put('itemsbuycreate',$itemsbuy);
        }
     }
     $data = Session::has('itemsbuycreate') ? Session::get('itemsbuycreate') : '';
     return response()->json($data);
     //return redirect()->back();
    }
     public function itemsbuycheckout(ItemFormRequest $request)
    {
      //dd($request->input('store_id'));
      if($request->hasFile('voucher_file'))
      {
           $voucher_file=$request->file('voucher_file');
           $voucher_filename=$request->get('voucher_code').'-'.uniqid().'-'.$voucher_file->getClientOriginalName();
           $voucher_file->move(public_path().'/images/ftth/voucher/',$voucher_filename);       
      }
      else
      {
        $voucher_filename="";
      }
      $voucher=Voucher::create([
            'voucher_file' =>$voucher_filename,
            'supplier_id' => $request->input('supplier_id'),
            'store_id' => $request->input('store_id'),
            'admin_id' =>Auth::user()->id,
            'voucher_code' =>$request->input('voucher_code'),                    
        ]);

      $itemsbuy=Session::has('itemsbuycreate') ? Session::get('itemsbuycreate') : null;
      
      foreach($itemsbuy as $key=>$itembuy)
      {
        $category= Category::where('title',$itembuy['categoryname'])->firstOrfail();
        $qty= $itembuy['qty'];
        $amountbyqty= $itembuy['amount']/$qty;

        for ($i=1; $i <= $qty; $i++)
        {
          $dataqty[] = array(                   
            'itemname_id' => $itembuy['id'],
            'model' => '',
            'mac' => $itembuy['mac'],
            'serial_number' => '',
            'voucher_id' => $voucher->id,
            'store_id' => $request->input('store_id'),
            'unit_price' => $amountbyqty,
            'amount'=>  $itembuy['amount'],                              
            'total_qty' => $itembuy['qty'],
            'qty' => 1,
            'used_qty' => 0,
            'transfer_qty' => 0,     
            'damage_qty' => 0,
            'damage_reason' => $request->input('damage_reason'),
            'category_id' => $category['id'],
            );
        }//end loop fpr $damageqty
        
      }
      DB::table('items') -> insert($dataqty);
      Session::forget('itemsbuycreate');
      Comment::create([
            'content' => Auth::user()->name ." created new Items Buy ".$voucher->voucher_code ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>"0" ,
            'commendable_type' => "items"
        
        ]);        
         return redirect()->back()->with('status', 'New Item Successfully Saved');
      }

    public function viewdetail(Request $request,$voucher_code)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-itembuy"))
      {
        $voucher=Voucher::where('voucher_code',$voucher_code)->first();
        $itemsbyvouchersall = Item::where('voucher_id',$voucher->id);
        $itemsbyvouchers=Item::where('voucher_id',$voucher->id)->orderBy('id','asc')->paginate(50);
     
        return view('admin.itembuys.voucherdetail',compact('voucher','itemsbyvouchers','itemsbyvouchersall')); 
      }abort(404,"Sorry");    
    }
    public function viewdetailedit(Request $request,$id)
    { 
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itembuy"))
      {

       $itembuy=Item::whereId($id)->firstOrfail();
      
       $itembuy->model=$request->get('model');
       $itembuy->mac=$request->get('mac');
       $itembuy->serial_number=$request->get('serial_number');
       $itembuy->damage_qty=$request->get('damage_qty');
       $itembuy->damage_reason=$request->get('damage_reason');
       $itembuy->updated_at=Carbon::now()->timestamp;
       $itembuy->update();
       Comment::create([
            'content' => Auth::user()->name ." updated Items Buy Item " ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "items"
        
        ]);
       return redirect()->back()->with(['status'=>'Items Buy Item Has Been Updated']);
      }abort(404,"Sorry");    
    }
    public function viewdetaildelete($id)
    { 
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itembuy"))
      {
        $itembuy=Item::whereId($id)->firstOrfail(); 
        $itembuy->delete();
           Comment::create([
              'content' => Auth::user()->name ." deleted Items Buy Item for ID".$id ,
              'user_id' => Auth::user()->id,
              'commendable_id' =>$id ,
              'commendable_type' => "items"
          
          ]);
           return redirect()->back()->with(['status'=>'Items Buy Item Has Been Deleted']);
      }abort(404,"Sorry");  
    }

    public function itemsbuydestroy($id)
    { 
      $voucher = Voucher::whereId($id)->firstOrFail();
      $items=Item::where('voucher_id',$voucher->id)->get(); 
 
      
      foreach($items as $item)
      {
        $itembyid=Item::whereId($item['id'])->firstOrFail();
        $itembyid->delete();
      }
      if($server->voucher_file)
      {
          $image_path = public_path().'/images/ftth/voucher/'.$voucher->voucher_file;
          unlink($image_path);
      }
       
      $voucher->delete();
      Comment::create([
            'content' => Auth::user()->name ." deleted Items Buy From Voucher ".$voucher->voucher_code,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "vouchers"
        
        ]);
        return redirect()->back()->with(['status'=>'Items Buy for '.$voucher->voucher_code. ' Has Been Deleted']);
    }

    public function itemsbuyedit($voucher_code)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itembuy"))
       {
         $categories=Category::all();
         $suppliers=Supplier::all();
         $stores=Store::all();
         
         $voucher = Voucher::where('voucher_code',$voucher_code)->first();
         $getitems=Item::where('voucher_id',$voucher->id)->first();
         $itemnamesbyvouchers=Item::where('voucher_id',$voucher->id)->pluck('itemname_id')->toArray();
        $itemnamesarrays=array();
        $itemnamesfromarrays=array_count_values($itemnamesbyvouchers);

        foreach($itemnamesfromarrays as $k=>$itemnamesfromarray)
        { 
            $getamountbyid=Item::where('voucher_id',$voucher->id)->where('itemname_id',$k)->first();
            $getamount=$getamountbyid->amount;
            //$getamount=$getamountbyid->amount * $itemnamesfromarray; 

            $itemname=Itemname::where('id',$k)->first();
            $categoryname=$itemname->category->title;
            $storedItem = ['id'=>$k,'itemname' =>$itemname['name'],'categoryname'=>$categoryname,'qty'=>$itemnamesfromarray,'amount'=>$getamount,'mac'=>$getamountbyid->mac];
            array_push($itemnamesarrays,$storedItem);
          }
          session()->put('itemsbuydb',$itemnamesarrays);
         
         return view('admin.itembuys.edit',compact('categories','suppliers','stores','getitems','voucher'));
       }abort(404,"Sorry");
     
      return view('admin.items.itemsbuylistedit',compact('categories','suppliers','stores','getitems'));
    }
    public function additemsbuyupdate(Request $request,$itemname_id,$category_id,$qty,$amount,$mac)
    { 
      $itemsbuydbs=Session::has('itemsbuydb') ? Session::get('itemsbuydb') : null;
      if($mac=="null")
      {
        $mac="";
      }
      $itemsbuydbid1s=array();
      foreach($itemsbuydbs as $itemsbuydb)
      {
         $itemsbuydbid1=$itemsbuydb['id'];
         array_push($itemsbuydbid1s,$itemsbuydbid1);
      }
      if(in_array($itemname_id, $itemsbuydbid1s))
      {
        $catbyid=Category::whereId($category_id)->first();
        $itemnamebyid=Itemname::whereId($itemnames_id)->first();
        $storedItemsBuy = ['id'=>$itemnames_id,'itemname'=>$itemnamebyid['name'],'categoryname'=>$catbyid['title'],'qty' =>$qty,'amount'=>$amount,'mac'=>$mac];
        array_push($itemsbuy,$storedItemsBuy);
      }       
      else
      {
         $itemnames_id=$itemname_id;       
      
          $itemsbuy=array();
          $ids=array();
          if($request->session()->has('itemsbuy'))
          {
           $itemsbuy=$request->session()->get('itemsbuy');
            foreach($itemsbuy as $item)
            {
              $id1=$item['id'];
              array_push($ids,$id1);
            }

            if(!in_array($itemnames_id, $ids))
            {
                    $catbyid=Category::whereId($category_id)->first();
                    $itemnamebyid=Itemname::whereId($itemnames_id)->first();
                    $storedItemsBuy = ['id'=>$itemnames_id,'itemname'=>$itemnamebyid['name'],'categoryname'=>$catbyid['title'],'qty' =>$qty,'amount'=>$amount,'mac'=>$mac];
                    array_push($itemsbuy,$storedItemsBuy);
               }
          }
          else
          {
            $catbyid=Category::whereId($category_id)->first();
            $itemnamebyid=Itemname::whereId($itemnames_id)->first();
            $storedItemsBuy = ['id'=>$itemnames_id,'itemname'=>$itemnamebyid['name'],'categoryname'=>$catbyid['title'],'qty' =>$qty,'amount'=>$amount,'mac'=>$mac];
            array_push($itemsbuy,$storedItemsBuy);
          }
        }
          $request->session()->put('itemsbuy',$itemsbuy);
          //$request->session()->flush();
          //return redirect()->back();
        
           $data = Session::has('itemsbuy') ? Session::get('itemsbuy') : '';
           return response()->json($data);
    }
     public function remove_itemsbuyupdate($id)
    { 
     $itemsbuy = Session::get('itemsbuy');
     foreach($itemsbuy as $key=>$item)
     {
        if($id == $item['id'])
        {
         unset($itemsbuy[$key]);
         Session()->put('itemsbuy',$itemsbuy);
        }
     }
     $data = Session::has('itemsbuy') ? Session::get('itemsbuy') : '';
     return response()->json($data);
     //return redirect()->back();
    }
    public function itemsbuyupdate(Request $request, $voucher_code)
    {
      //$itemsbuy=Session::has('itemsbuy') ? Session::get('itemsbuy') : null;
      $voucher=Voucher::where('voucher_code',$voucher_code)->first();
      if($request->hasFile('voucher_file'))
      {
           $voucher_file=$request->file('voucher_file');
           $voucher_filename=$request->get('voucher_code').'-'.uniqid().'-'.$voucher_file->getClientOriginalName();
           $voucher_file->move(public_path().'/images/ftth/voucher/',$voucher_filename);       
      }
      else
      {
        $voucher_filename=$voucher->voucher_file;
      }
      if(Session('itemsbuy'))
      {
        // $itemsbuy=Session::has('itemsbuy') ? Session::get('itemsbuy') : null;
        // dd($itemsbuy);
          // $itemsbuys=Item::where('voucher_id',$voucher->id)->get();
          // foreach($itemsbuys as $item)
          // {
          //   $itembyid=Item::whereId($item['id'])->firstOrFail();
          //   $itembyid->delete();
          // }

          $itemsbuy=Session::has('itemsbuy') ? Session::get('itemsbuy') : null;
      
          foreach($itemsbuy as $key=>$itembuy)
          {
            $category= Category::where('title',$itembuy['categoryname'])->firstOrfail();
            $qty= $itembuy['qty'];
            $amountbyqty= $itembuy['amount']/$qty;

          for ($i=1; $i <= $qty; $i++)
          {
            $dataqty[] = array(                   
              'itemname_id' => $itembuy['id'],
              'model' => '',
              'mac' => $itembuy['mac'],
              'serial_number' => '',
              'voucher_id' => $voucher->id,
              'store_id' => $request->input('store_id'),
              'unit_price' => $amountbyqty,
              'amount'=>  $itembuy['amount'],                              
              'total_qty' => $itembuy['qty'],
              'qty' => 1,
              'used_qty' => 0,
              'transfer_qty' => 0,     
              'damage_qty' => 0,
              'damage_reason' => $request->input('damage_reason'),
              'category_id' => $category['id'],
              );
          }//end loop fpr $damageqty
            
       }
          DB::table('items') -> insert($dataqty);
          Session::forget('itemsbuy');
          Session::forget('itemsbuydb');
      }
      // else
      // {
         // $itemsbuys=Item::where('voucher_id',$voucher->id)->get();
         // foreach($itemsbuys as $itemsbuy)
         // {
         //   $items=Item::whereId($itemsbuy['id'])->firstOrfail();
         //   $items->supplier_voucher=$request->get('supplier_voucher');
         //   $items->supplier_id=$request->get('supplier_id');
         //   $items->store_id=$request->get('store_id');
         //   $items->updated_at=Carbon::now()->timestamp;
         //   if($request->hasFile('voucherm_file'))
         //   {
         //    $items->item_file=$item_filename;
         //   }
         
         //   $items->update();           
         // }
         
      //}
       $voucher->voucher_file = $voucher_filename;
       $voucher->supplier_id =$request->input('supplier_id');
       $voucher->store_id =$request->input('store_id');
       $voucher->admin_id =$request->input('admin_id');
       $voucher->voucher_code =$request->input('voucher_code');
       $voucher->updated_at=Carbon::now()->timestamp;
       $voucher->update();
       
       Comment::create([
            'content' => Auth::user()->name ." updated Items Buy ".$voucher->voucher_code ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>"0" ,
            'commendable_type' => "items"
        
        ]);       
     //return redirect('/admin/ItemsBuyList')->with("status","Items Buy for ".$voucher->voucher_code."Successfully Updated");
      return redirect()->back()->with(['status'=>"Items Buy for ".$voucher->voucher_code."Successfully Updated"          ]);
     
    }

    public function removeold_itemsbuy($itemname_id,$voucher_code)
    { 
      $voucher = Voucher::where('voucher_code',$voucher_code)->first();
      $itemname = Itemname::whereId($itemname_id)->first();
      $items = Item::where('itemname_id',$itemname_id)->where('voucher_id',$voucher->id)->get();
      foreach($items as $item)
      {
        $itembyid=Item::whereId($item['id'])->firstOrFail();
        $itembyid->delete();
      }
      // $voucher->delete();
      Comment::create([
            'content' => Auth::user()->name ." deleted Items Buy From Voucher ".$voucher->voucher_code ."& Item Name (".$itemname->name.")",
            'user_id' => Auth::user()->id,
            'commendable_id' =>$itemname_id ,
            'commendable_type' => "items"
        
        ]);
      return redirect()->back()->with(['status'=>'Items Buy for Item Name '.$itemname->name. ' Has Been Deleted']);    
   }
   
    public function show1(Request $request,Category $title,$key,$storename)
    { 
      // if($title->mac == 0 && $title->serial == 0)
      // {
        $getstore_id=Store::select('id')->where('name',$storename)->first();
        $getitemname=Itemname::where('id',$key)->first();
        $store = Store::where('name',$storename)->get();
        $items = DB::table('items')
        ->join('categories', 'categories.id', '=', 'items.category_id')
        ->join('itemnames', 'itemnames.id', '=', 'items.itemname_id')
        ->groupBy('items.itemname_id')
        ->where('items.category_id', '=', $title->id)
        ->where('categories.mac', '=', 0)
        ->where('store_id', $getstore_id['id'])
        ->where('categories.serial', '=', 0)->get();

        $totalqtyitems = DB::table('items')
        ->join('categories', 'categories.id', '=', 'items.category_id')
        ->join('itemnames', 'itemnames.id', '=', 'items.itemname_id')
        ->groupBy('items.itemname_id')
        ->where('items.category_id', '=', $title->id)
        ->where('items.qty', '=', 1)
        ->where('categories.mac', '=', 0)
        ->where('store_id', $getstore_id['id'])
        ->where('categories.serial', '=', 0);

        $transferqtyitems = DB::table('items')
        ->join('categories', 'categories.id', '=', 'items.category_id')
        ->join('itemnames', 'itemnames.id', '=', 'items.itemname_id')
        ->groupBy('items.itemname_id')
        ->where('items.category_id', '=', $title->id)
        ->where('items.transfer_qty', '=', 1)
        ->where('categories.mac', '=', 0)
        ->where('store_id', $getstore_id['id'])
        ->where('categories.serial', '=', 0);

        $usedqtyitems = DB::table('items')
        ->join('categories', 'categories.id', '=', 'items.category_id')
        ->join('itemnames', 'itemnames.id', '=', 'items.itemname_id')
        ->groupBy('items.itemname_id')
        ->where('items.category_id', '=', $title->id)
        ->where('items.used_qty', '=', 1)
        ->where('categories.mac', '=', 0)
        ->where('store_id', $getstore_id['id'])
        ->where('categories.serial', '=', 0);

        $damageqtyitems = DB::table('items')
        ->join('categories', 'categories.id', '=', 'items.category_id')
        ->join('itemnames', 'itemnames.id', '=', 'items.itemname_id')
        ->groupBy('items.itemname_id')
        ->where('items.category_id', '=', $title->id)
        ->where('items.damage_qty', '=', 1)
        ->where('categories.mac', '=', 0)
        ->where('store_id', $getstore_id['id'])
        ->where('categories.serial', '=', 0);

       $transferqtyitemscount = $transferqtyitems->count();
       $damageqtyitemscount = $damageqtyitems->count();
       $usedqtyitemscount = $usedqtyitems->count();
       $totalqtyitemscount = $totalqtyitems->count();
       
     
       $avalibleqtyitemscount = $totalqtyitemscount - ($transferqtyitemscount +$damageqtyitemscount + $usedqtyitemscount);
     
      return view('admin.itembuys.itemdetail1',compact('title','items','getitemname','store','usedqtyitemscount','damageqtyitemscount','transferqtyitemscount','totalqtyitemscount','avalibleqtyitemscount'));      
    }
    public function searchpost(Request $request,Category $title,$key,$storename)
    {
        $s = $request->search;
        $getstore_id=Store::select('id')->where('name',$storename)->first();
        $store = Store::where('name',$storename)->get();
        $getitemname=Itemname::where('id',$key)->first();
        $itemname_id=$key;

      if($s != null)
      {       

        $itemsall=Item::where('category_id',$title->id)
       ->where('itemname_id',$key)
       ->where('store_id', $getstore_id['id'])
       ->WhereHas('vouchers', function($q) use ($s){
          return $q->where('voucher_code','like','%'. $s . '%');
        })
        ->orWhere('model', 'LIKE', "%{$s}%")
        ->orWhere('mac', 'LIKE', "%{$s}%")
        ->orWhere('serial_number', 'LIKE', "%{$s}%");
        
        $items=Item::where('category_id',$title->id)
       ->where('itemname_id',$key)
       ->where('store_id', $getstore_id['id'])
       ->WhereHas('vouchers', function($q) use ($s){
          return $q->where('voucher_code','like','%'. $s . '%');
        })
       
        ->orWhere('model', 'LIKE', "%{$s}%")
        ->orWhere('mac', 'LIKE', "%{$s}%")
        ->orWhere('serial_number', 'LIKE', "%{$s}%")
        ->orderBy('created_at','desc')
        ->paginate(50);;
      }
      else
      {
        $items=Item::where('category_id',$title->id)
       ->where('itemname_id',$key)
       ->where('store_id', $getstore_id['id'])
       ->orderBy('id','desc')
       ->paginate(50);

       $itemsall=Item::where('category_id',$title->id)
       ->where('itemname_id',$key)
       ->where('store_id', $getstore_id['id']);
      }
 
       return view('admin.itembuys.itemdetail',compact('title','items','storename','getitemname','s','key','getstore_id','itemname_id','store','itemsall'));
            
             
    }
   public function show(Request $request,Category $title,$key,$storename)
   {
       $getstore_id=Store::select('id')->where('name',$storename)->first();
       $store = Store::where('name',$storename)->get();
       
       $getitemname=Itemname::where('id',$key)->first();
       $itemname_id=$key;
       $items=Item::where('category_id',$title->id)
       ->where('itemname_id',$key)
       ->where('store_id', $getstore_id['id'])
       ->orderBy('id','desc')
       ->paginate(50);

       $itemsall=Item::where('category_id',$title->id)
       ->where('itemname_id',$key)
       ->where('store_id', $getstore_id['id']);
       
       
       $s="";
     
       return view('admin.itembuys.itemdetail',compact('title','items','storename','getitemname','s','key','getstore_id','itemname_id','store','itemsall'));

    }
     public function viewdetailitemedit(Request $request,$id)
    { 
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-viewitem"))
      {

       $itembuy=Item::whereId($id)->firstOrfail();
      
       $itembuy->model=$request->get('model');
       $itembuy->mac=$request->get('mac');
       $itembuy->serial_number=$request->get('serial_number');
       $itembuy->damage_qty=$request->get('damage_qty');
       $itembuy->damage_reason=$request->get('damage_reason');
       $itembuy->updated_at=Carbon::now()->timestamp;
       $itembuy->update();
       Comment::create([
            'content' => Auth::user()->name ." updated Item " ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "items"
        
        ]);
       return redirect()->back()->with(['status'=>'Item Has Been Updated']);
      }abort(404,"Sorry");    
    }

    public function viewdetailitemdelete($id)
    { 
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-viewitem"))
      {
        $itembuy=Item::whereId($id)->firstOrfail(); 
        $itembuy->delete();
           Comment::create([
              'content' => Auth::user()->name ." deleted Item for ID".$id ,
              'user_id' => Auth::user()->id,
              'commendable_id' =>$id ,
              'commendable_type' => "items"
          
          ]);
           return redirect()->back()->with(['status'=>'Item Has Been Deleted']);
      }abort(404,"Sorry");  
    }
}
