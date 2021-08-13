<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customername;
use App\Http\Requests\CustomernameFormRequest;
use App\FTTH;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomernameController extends Controller
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-customername"))
        {
            $customernamelistsall=Customername::all();
            $customernamelists = Customername::orderBy('id', 'desc')->paginate(50);
            $s="";
             
            return view('admin.customernames.list',compact('customernamelistsall','customernamelists','s'));
        }abort(404,"Sorry");
    }
    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-customername"))
        {
                $s = $request->search;
               
                $customernamelistsall=Customername::where('name', 'LIKE', "%{$s}%")
                ->orWhere('code', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%");
                
                $customernamelists = Customername::where('name', 'LIKE', "%{$s}%")
                ->orWhere('code', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(50);
                
                return view('admin.customernames.list',compact('customernamelistsall','customernamelists','s'));
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
    public function store(CustomernameFormRequest $request)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-customername"))
        {
            $customername=Customername::create([
            'name' => $request->get('name'),
            'code' => $request->get('code'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
            'township' => $request->get('township'), 
            'city' => $request->get('city'), 
            'address' => $request->get('address'),            
            ]);
          Comment::create([
            'content' => Auth::user()->name ." created new Customer Name  ".$customername->name,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$customername->id ,
            'commendable_type' => "customernames"
        
        ]);
        // return redirect('/admin/CategoryEntry')->with("status","New Supplier Successfully Saved");
         return redirect()->back()->with('status', 'New Customer Name Successfully Saved'); 
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-customername"))
        {
             $customername=Customername::whereId($id)->firstOrFail();
             $customername->name=$request->get('name');
             $customername->code=$request->get('code');
             $customername->email=$request->get('email');
             $customername->phone=$request->get('phone');
             $customername->lat=$request->get('lat');
             $customername->lng=$request->get('lng');
             $customername->township=$request->get('township');
             $customername->city=$request->get('city');
             $customername->address=$request->get('address');
             $customername->updated_at=Carbon::now()->timestamp;
       
            Comment::create([
            'content' => Auth::user()->name ." updated Customer Name  ".$customername->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "customernames"
        
        ]);
        $customername->update();
       
        return redirect()->back()->with(['status'=>'Customer Name ('.$customername->name.' ) Has Been Updated']);
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-customername"))
        {
              $customername=Customername::whereId($id)->firstOrFail(); 
              Comment::create([
                'content' => Auth::user()->name ." deleted Customer Name  ".$customername->name,
                'user_id' => Auth::user()->id,
                'commendable_id' =>$id ,
                'commendable_type' => "customernames"
              ]);
              $customername->delete();
      
              return redirect()->back()->with(['status'=>'Customer Name ('.$customername->name.' ) Has Been Deleted']);
        }abort(404,"Sorry");
    }
}
