<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobtitle;
use App\Http\Requests\JobtitleFormRequest;
use App\FTTH;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JobtitleController extends Controller
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-jobitle"))
        {
            $jobitlelistsall=Jobtitle::all();
            $jobitlelists = Jobtitle::orderBy('id', 'desc')->paginate(30);
            $s="";
            return view('admin.jobtitles.list',compact('jobitlelistsall','jobitlelists','s'));
        }abort(404,"Sorry");
    }
    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-jobitle"))
        {
                $s = $request->search;

                $jobitlelistsall=Jobtitle::where('name', 'LIKE', "%{$s}%");
                
                $jobitlelists = Jobtitle::where('name', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(30);
                
                return view('admin.jobtitles.list',compact('jobitlelistsall','jobitlelists','s'));
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
    public function store(JobtitleFormRequest $request)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-jobitle"))
        {
            $jobtitle=Jobtitle::create([
            'name' => $request->get('name'),
            ]);
          Comment::create([
            'content' => Auth::user()->name ." created new Job Title ".$jobtitle->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$jobtitle->id ,
            'commendable_type' => "jobtitles"
        
        ]);
        // return redirect('/admin/CategoryEntry')->with("status","New Supplier Successfully Saved");
         return redirect()->back()->with('status', 'New Job Title Successfully Saved'); 
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-jobitle"))
        {
             $jobitle=Jobtitle::whereId($id)->firstOrFail();
             $jobitle->name=$request->get('name');
             $jobitle->updated_at=Carbon::now()->timestamp;
       
            Comment::create([
            'content' => Auth::user()->name ." updated Job Title  ".$jobitle->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "jobitles"
        
        ]);
        $jobitle->update();
       
        return redirect()->back()->with(['status'=>'Job Title ('.$jobitle->name.' ) Has Been Updated']);
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
         if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-jobitle"))
        {
              $jobitle=Jobtitle::whereId($id)->firstOrFail(); 
              Comment::create([
                'content' => Auth::user()->name ." deleted Job Title  ".$jobitle->name,
                'user_id' => Auth::user()->id,
                'commendable_id' =>$id ,
                'commendable_type' => "jobtitles"
              ]);
              $jobitle->delete();
      
              return redirect()->back()->with(['status'=>'Job Title ('.$jobitle->name.' ) Has Been Deleted']);
        }abort(404,"Sorry");
    }
}
