<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ItemnameFormRequest;
use App\Category;
use App\Store;
use App\Supplier;
use App\Itemname;
use App\Item;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use DB;
//use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Input;
//use Validator;
use Carbon\Carbon;

class ItemnameController extends Controller
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
    public function index(Category $title)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-agent"))
       {
         $itemnamelistsall=Itemname::where('category_id',$title->id);
         $itemnamelists=Itemname::where('category_id',$title->id)->paginate(30);
         $s="";

        $namesfetfromitems = DB::table('items')
        ->join('itemnames', 'itemnames.id', '=', 'items.itemname_id')
        ->groupBy('items.itemname_id')
        ->where('items.category_id', '=', $title->id)
        ->paginate(30);
     
      $itembycat_groupsitemnames=$namesfetfromitems->groupBy('itemname_id');
     //dd($itembycat_groupsitemnames);
      $itembycats=Item::where('category_id',$title->id)->get();         
      // $itembycat_groupsitemnames=$itembycats->groupBy('itemname_id');
      $itembycat_groupsstores=$itembycats->sortBy('store_id',SORT_REGULAR,false)->groupBy('store_id'); 
      //$itembycat_groupsstores=$itembycat_groupsstores1->groupBy('store_id');
      
      //dd($itembycat_groupsstores);
      // return view('admin.items.itemlist',compact('title','AllItems','itembycats','itembycat_groupsitemnames','itembycat_groupsstores','namesfetfromitems'));





         return view('admin.itemnames.list',compact('title','itemnamelistsall','itemnamelists','s','itembycats','itembycat_groupsitemnames','itembycat_groupsstores','namesfetfromitems'));
       }abort(404,"Sorry");
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
    public function store(ItemnameFormRequest $request)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-itemname"))
       {
           //  $rules = array('name' => 'unique:itemnames');
           //  $validator = Validator::make(input::all(), $rules);

           // if ($validator->fails()) 
           // {
           //      return redirect()->back()->withErrors($validator);
           // } 
           // else 
           // {
             if($request->hasFile('itemname_file'))
             {
                 $category=Category::find($request->get('category_id'));
                $itemname_file=$request->file('itemname_file');
                //dd($itemname_file);
                
                $itemname_filename=uniqid().'_'.$itemname_file->getClientOriginalName();
                $itemname_file->move(public_path().'/images/ftth/itemnames',$itemname_filename);
                
                $itemname_table=Itemname::create([
                    'name' => $request->get('name'),
                    'account_code' => $request->get('account_code'),
                    'category_id' => $request->get('category_id'),
                    'itemname_file' =>$itemname_filename
                    ]);
                
                 Comment::create([
                    'content' => Auth::user()->name ." created new Item Name  ".$itemname_table->name ." for FTTH (".$category->title.')',
                    'user_id' => Auth::user()->id,
                    'commendable_id' =>$itemname_table->id ,
                    'commendable_type' => "itemnames"
                
                ]);
            
                return redirect()->back()->with('status', 'New Item Name '.$itemname_table->name.' Successfully Saved');
             }
             else
             {
                return redirect()->back()->withErrors(['status'=>'Picture must be inserted']);
             }
               
            //}
       }abort(404,"Sorry");
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
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-itemname"))
       {
        $itemname=Itemname::whereId($id)->firstOrFail();
        $itemname->name=$request->get('name');
        $itemname->account_code=$request->get('account_code');
        $itemname->category_id=$request->get('category_id');
        $itemname->updated_at=Carbon::now()->timestamp;
        if($request->hasFile('itemname_file'))
         {

           $itemname_file=$request->file('itemname_file');
           $itemname_filename=uniqid().'-'.$itemname_file->getClientOriginalName();
           $itemname_file->move(public_path().'/images/ftth/itemnames/',$itemname_filename);       
           $itemname->itemname_file=$itemname_filename;    
        }
        Comment::create([
            'content' => Auth::user()->name ." updated Item Name  ".$itemname->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "itemnames"
        
        ]);
        $itemname->update();
       
        return redirect()->back()->with(['status'=>$itemname->name.'Has Been Updated']);
         
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
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-itemname"))
       {
         $itemname=Itemname::whereId($id)->firstOrFail(); 
          Comment::create([
            'content' => Auth::user()->name ." deleted Item Name ".$itemname->name,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "itemnames"
        
        ]);
         $itemname->delete();
        
         return redirect()->back()->with(['status'=>'Item Name ('.$itemname->name.' ) Has Been Deleted']);
       }
    }
}
