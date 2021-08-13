<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use Auth;
use App\Agent;
use App\Comment;
use App\Http\Requests\AgentFormRequest;
use Carbon\Carbon;
use Validator;

class AgentController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function __construct()
  {
    $this->middleware('auth:admin');
  }
  public function index()
  {
    //
  }
  public function list()
  {
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-agent"))
    {
      $agentlistsall = Agent::all();
      $agentlists = Agent::orderBy('id', 'desc')->paginate(30);
      $s="";

      return view('admin.agents.list',compact('agentlistsall','agentlists','s'));
    }abort(404,"Sorry");
  }

  public function searchagent(Request $request)
  {
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-agent")){
      $s = $request->search;

      $agentlistsall=Agent::where('name', 'LIKE', "%{$s}%");
      $agentlists = Agent::where('name', 'LIKE', "%{$s}%")
      ->orderBy('id','desc')
      ->paginate(30);

      return view('admin.agents.list',compact('agentlistsall','agentlists','s'));
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
    public function store(AgentFormRequest $request)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-agent"))
      {
        $agent = Agent::create([
          'name' => $request->get('name'),
        ]);

        Comment::create([
            'content' => Auth::user()->name ." created New Agent ".$agent->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$agent->id,
            'commendable_type' => "agents"
        
        ]);
        return redirect('admin/Agentlist')->with("status","Successfully Saved Agent Data");
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
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("update-agent")){

        $validated_data = Validator::make($request->all(), [
          'name' => 'required|unique:agents',
        ]);

        if ($validated_data->fails())
        {
          return redirect()->back()->with(['errorstatus'=>'Name is already existed!!!']);
        }

        $agent=Agent::whereId($id)->firstOrFail();

        $agent->name=$request->get('name');

        $agent->updated_at=Carbon::now()->timestamp;
        $agent->update();

         Comment::create([
            'content' => Auth::user()->name ." updated Agent ".$agent->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$agent->id,
            'commendable_type' => "agents"
        
        ]);

        return redirect()->back()->with(['status'=>$agent->name.' Has Been Updated']);

       }abort(404,"Sorry");

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destory($id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-agent"))
      {
        $agent = Agent::whereId($id)->firstOrFail();

        Comment::create([
            'content' => Auth::user()->name ." deleted Agent ".$agent->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$agent->id,
            'commendable_type' => "agents"
        
        ]);

        $agents->delete();

        return redirect()->back()->with(['status'=>$agent->name.' Has Been Deleted']);
       }abort(404,"Sorry");
    }
  }
