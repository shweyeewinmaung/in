<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFormRequest;
use App\Store;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;


class StoreController extends Controller
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
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-store"))
      {
        $storelistsall=Store::all();
        $storelists = Store::orderBy('id', 'desc')->paginate(30);
        $s="";
        return view('admin.stores.list',compact('storelistsall','storelists','s'));
      }abort(404,"Sorry");
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-store"))
        {
                $s = $request->search;

                $storelistsall=Store::where('name', 'LIKE', "%{$s}%")
                 ->orWhere('address', 'LIKE', "%{$s}%");
                
                $storelists = Store::where('name', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orderBy('id','asc')
                ->paginate(30);
                
                return view('admin.stores.list',compact('storelistsall','storelists','s'));
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
    public function store(StoreFormRequest $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-store"))
        {
            $store=Store::create([
                'name' => $request->get('name'),
                'address' => $request->get('address'),
               ]);
            Comment::create([
                'content' => Auth::user()->name ." created New Store  ".$store->name ,
                'user_id' => Auth::user()->id,
                'commendable_id' =>$store->id ,
                'commendable_type' => "stores"
            
            ]);
           return redirect()->back()->with('status', 'New Store Successfully Saved');  
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-store"))
        {
        //     $validated_data = Validator::make($request->all(), [
        //       'name' => 'required|unique:stores',
        //     ]);

        //     if ($validated_data->fails())
        //     {
        //       return redirect()->back()->with(['errorstatus'=>'Name is already existed!!!']);
        //     }

             $store=Store::whereId($id)->firstOrFail();
             $store->name=$request->get('name');
             $store->address=$request->get('address');
             $store->updated_at=Carbon::now()->timestamp;
              
            Comment::create([
            'content' => Auth::user()->name ." updated Store  ".$store->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "stores"
        
        ]);
        $store->update();
       
        return redirect()->back()->with(['status'=>'Store ('.$store->name.' ) Has Been Updated']);
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-store"))
        {
            $store=Store::whereId($id)->firstOrFail(); 
            $store->delete();
            Comment::create([
                'content' => Auth::user()->name ." deleted Store ".$store->name,
                'user_id' => Auth::user()->id,
                'commendable_id' =>$id ,
                'commendable_type' => "stores"
            
            ]);
            return redirect()->back()->with(['status'=>'Store ('.$store->name.' ) Has Been Deleted']);
        }abort(404,"Sorry"); 
    }
}
