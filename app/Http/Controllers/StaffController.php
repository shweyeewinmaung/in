<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Jobtitle;
use App\Http\Requests\StaffFormRequest;
use App\FTTH;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffController extends Controller
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-staff"))
        {
            $stafflistsall=Staff::all();
            $stafflists = Staff::orderBy('id', 'desc')->paginate(30);
            $s="";
            $jobtitles = Jobtitle::all();
            return view('admin.staffs.list',compact('stafflistsall','stafflists','s','jobtitles'));
        }abort(404,"Sorry");
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-staff"))
        {
                $s = $request->search;
                $jobtitles = Jobtitle::all();

                $stafflistsall=Staff::where('name', 'LIKE', "%{$s}%")
                ->orWhereHas('jobtitle', function($q) use ($s){
                  return $q->where('name','like','%'. $s . '%');
                })              
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%");
                
                $stafflists = Staff::where('name', 'LIKE', "%{$s}%")
                 ->orWhereHas('jobtitle', function($q) use ($s){
                  return $q->where('name','like','%'. $s . '%');
                }) 
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(30);
                
                return view('admin.staffs.list',compact('stafflists','stafflistsall','s','jobtitles'));
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
    public function store(StaffFormRequest $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-staff"))
        {
             $staff=Staff::create([
            'name' => $request->get('name'),
            'jobtitle_id' => $request->get('jobtitle_id'),
            'email' => $request->get('email'), 
            'phone' => $request->get('phone'), 
            'address' => $request->get('address'),            
            ]);
          Comment::create([
            'content' => Auth::user()->name ." created new staff  ".$staff->name,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$staff->id ,
            'commendable_type' => "staffs"
        
        ]);
        // return redirect('/admin/CategoryEntry')->with("status","New Supplier Successfully Saved");
         return redirect()->back()->with('status', 'New Staff Successfully Saved'); 
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
         if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-staff"))
        {
             $staff=Staff::whereId($id)->firstOrFail();
             $staff->name=$request->get('name');
             $staff->jobtitle_id=$request->get('jobtitle_id');
             $staff->address=$request->get('address');
             $staff->email=$request->get('email');
             $staff->phone=$request->get('phone');
             $staff->updated_at=Carbon::now()->timestamp;
       
            Comment::create([
            'content' => Auth::user()->name ." updated Staff  ".$staff->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "staff"
        
        ]);
        $staff->update();
       
        return redirect()->back()->with(['status'=>'Staff ('.$staff->name.' ) Has Been Updated']);
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
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-staff"))
        {
              $staff=Staff::whereId($id)->firstOrFail(); 
              Comment::create([
                'content' => Auth::user()->name ." deleted Staff  ".$staff->name,
                'user_id' => Auth::user()->id,
                'commendable_id' =>$id ,
                'commendable_type' => "staff"
              ]);
              $staff->delete();
      
              return redirect()->back()->with(['status'=>'Staff ('.$staff->name.' ) Has Been Deleted']);
        }abort(404,"Sorry");
    }
}
