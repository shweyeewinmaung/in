<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servername;
use App\Http\Requests\ServernameFormRequest;
use App\FTTH;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServernameController extends Controller
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
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-servername"))
        {
            $servernamelistsall=Servername::all();
            $servernamelists = Servername::orderBy('id', 'desc')->paginate(30);
            $s="";
             
            return view('admin.servernames.list',compact('servernamelistsall','servernamelists','s'));
        }abort(404,"Sorry");
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-servername"))
        {
                $s = $request->search;
               
                $servernamelistsall=Servername::where('name', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%");
                
                $servernamelists = Servername::where('name', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('township', 'LIKE', "%{$s}%")
                ->orWhere('city', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(30);
                
                return view('admin.servernames.list',compact('servernamelistsall','servernamelists','s'));
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
    public function store(ServernameFormRequest $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-servername"))
        {
            $servername=Servername::create([
            'name' => $request->get('name'),
            'township' => $request->get('township'), 
            'city' => $request->get('city'), 
            'address' => $request->get('address'),            
            ]);
          Comment::create([
            'content' => Auth::user()->name ." created new Server Name  ".$servername->name,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$servername->id ,
            'commendable_type' => "servernames"
        
        ]);
        // return redirect('/admin/CategoryEntry')->with("status","New Supplier Successfully Saved");
         return redirect()->back()->with('status', 'New Server Name Successfully Saved'); 
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-servername"))
        {
             $servername=Servername::whereId($id)->firstOrFail();
             $servername->name=$request->get('name');
             $servername->township=$request->get('township');
             $servername->city=$request->get('city');
             $servername->address=$request->get('address');
             $servername->updated_at=Carbon::now()->timestamp;
       
            Comment::create([
            'content' => Auth::user()->name ." updated Server Name  ".$servername->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "servernames"
        
        ]);
        $servername->update();
       
        return redirect()->back()->with(['status'=>'Server Name ('.$servername->name.' ) Has Been Updated']);
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
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-servername"))
        {
              $servername=Servername::whereId($id)->firstOrFail(); 
              Comment::create([
                'content' => Auth::user()->name ." deleted Server Name  ".$servername->name,
                'user_id' => Auth::user()->id,
                'commendable_id' =>$id ,
                'commendable_type' => "servernames"
              ]);
              $servername->delete();
      
              return redirect()->back()->with(['status'=>'Server Name ('.$servername->name.' ) Has Been Deleted']);
        }abort(404,"Sorry");
    }
}
