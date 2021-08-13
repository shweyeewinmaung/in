<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Streetname;
use App\Http\Requests\StreetnameFormRequest;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StreetnameController extends Controller
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
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-streetname"))
       {
            $streetnamelistsall=Streetname::all();
            $streetnamelists = Streetname::orderBy('id', 'desc')->paginate(30);
            $s="";
             
            return view('admin.streetnames.list',compact('streetnamelistsall','streetnamelists','s'));
        }abort(404,"Sorry");
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-streetname"))
        {
                $s = $request->search;
               
                $streetnamelistsall=Streetname::where('name', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%");
                
                $streetnamelists = Streetname::where('name', 'LIKE', "%{$s}%")
                ->orWhere('lat', 'LIKE', "%{$s}%")
                ->orWhere('lng', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(30);
                
                return view('admin.streetnames.list',compact('streetnamelistsall','streetnamelists','s'));
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
    public function store(StreetnameFormRequest $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-streetname"))
        {
            $streetname=Streetname::create([
            'name' => $request->get('name'),
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
            'street' => $request->get('street'),
            'township' => $request->get('township'), 
            'city' => $request->get('city'), 
            'address' => $request->get('address'),            
            ]);
          Comment::create([
            'content' => Auth::user()->name ." created new Street Name  ".$streetname->name,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$streetname->id ,
            'commendable_type' => "streetnames"
        
        ]);
        
         return redirect()->back()->with('status', 'New Street Name Successfully Saved'); 
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-streetname"))
        {
             $streetname=Streetname::whereId($id)->firstOrFail();
             $streetname->name=$request->get('name');
             $streetname->lat=$request->get('lat');
             $streetname->lng=$request->get('lng');
             $streetname->street=$request->get('street');
             $streetname->township=$request->get('township');
             $streetname->city=$request->get('city');
             $streetname->address=$request->get('address');
             $streetname->updated_at=Carbon::now()->timestamp;
       
            Comment::create([
            'content' => Auth::user()->name ." updated Street Name  ".$streetname->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "streetnames"
        
        ]);
        $streetname->update();
       
        return redirect()->back()->with(['status'=>'Street Name ('.$streetname->name.' ) Has Been Updated']);
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
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-streetname"))
       {
              $streetname=Streetname::whereId($id)->firstOrFail(); 
              Comment::create([
                'content' => Auth::user()->name ." deleted Street Name  ".$streetname->name,
                'user_id' => Auth::user()->id,
                'commendable_id' =>$id ,
                'commendable_type' => "streetnames"
              ]);
              $streetname->delete();
      
              return redirect()->back()->with(['status'=>'Street Name ('.$streetname->name.' ) Has Been Deleted']);
        }abort(404,"Sorry");
    }
}
