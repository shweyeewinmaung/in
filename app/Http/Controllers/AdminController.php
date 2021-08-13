<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use Auth;
use App\Agent;
use App\Role;
use App\Comment;
use App\Http\Requests\AdminRegisterFormRequest;
use Carbon\Carbon;
use Validator;

class AdminController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth:admin');
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Contracts\Support\Renderable
  */
  public function index()
  {
    return view('admin');
  }
  public function logout()
  {

    Auth::guard('admin')->logout();
    //return redirect('/');
    return redirect('admin/login');
  }
  public function list()
  {
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-admin"))
    {
      $adminlistsall = Admin::where('is_super', '!=', 1);
      $adminlists = Admin::where('is_super', '!=', 1)->orderBy('id', 'desc')->paginate(30);
      $s="";

      return view('admin.admins.list',compact('adminlistsall','adminlists','s'));
    }abort(404,"Sorry");
  }
  public function searchpost(Request $request)
  {
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-admin"))
    {

      $s = $request->search;
      if($s != null)
      {
         $adminlistsall=Admin::where('is_super', '!=', 1)
        ->where('name', 'LIKE', "%{$s}%")
        ->orWhereHas('role', function($q) use ($s){
          return $q->where('name','like','%'. $s . '%');
        })
        ->orWhereHas('agent', function($q) use ($s){
          return $q->where('name','like','%'. $s . '%');
        });
        $adminlists = Admin::where('is_super', '!=', 1)
        ->where('name', 'LIKE', "%{$s}%")
        ->orWhereHas('role', function($q) use ($s){
          return $q->where('name','like','%'. $s . '%');
        })
        ->orWhereHas('agent', function($q) use ($s){
          return $q->where('name','like','%'. $s . '%');
        })
        ->orderBy('id','desc')
        ->paginate(30);
      }
      else
      {
        $adminlistsall = Admin::where('is_super', '!=', 1);
        $adminlists = Admin::where('is_super', '!=', 1)->orderBy('id', 'desc')->paginate(30);
      }
     

      return view('admin.admins.list',compact('adminlistsall','adminlists','s'));
    }abort(404,"Sorry");
  }
  public function create()
  {
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-admin"))
    {

      $roles = Role::all();
      $agents = Agent::all();
      return view('admin.admins.create',compact('roles','agents'));
    }abort(404,"Sorry");

  }
  public function store(AdminRegisterFormRequest $request)
  {

    if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-admin"))
    {
          
      $admins=Admin::create([
        'name' => $request->get('name'),
        'email' => $request->get('email'),
        'password' => bcrypt($request->get('password')),       
        'role_id' => $request->get('role'),
        'agent_id' =>$request->get('agent'),
      ]);

      Comment::create([
            'content' => Auth::user()->name ." created New admin  ".$admins->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$admins->id ,
            'commendable_type' => "admins"
        
        ]);
      return redirect('admin/adminslist')->with("status","Successfully Saved Admin Data");

     }abort(404,"Sorry");
  }
  public function edit($id)
  {
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-admin"))
    {
      $roles = Role::all();
      $agents = Agent::all();
      $admin = Admin::whereId($id)->firstOrFail();
      return view('admin.admins.edit',compact('roles','agents','admin'));
    }abort(404,"Sorry");

  }
  public function update($id,Request $request)
  {
    //  $validated_data = Validator::make($request->all(), [
    //     'name' => 'required',
    //     'email' => 'required|unique:admins',
    //     'role' => 'required',
    //     'agent' => 'required',

    // ]);

    // if ($validated_data->fails())
    // {
    //     return redirect()->back()->with(['errorstatuses'=>$validated_data->errors()]);
    // }
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-admin"))
    {

    $admin=Admin::whereId($id)->firstOrFail();

    if($request->get('password') == "")
    {
      $password= $admin->password;
    }
    else
    {
      $password=bcrypt($request->get('password'));
    }

    $admin->name=$request->get('name');
    $admin->email=$request->get('email');
    $admin->password=$password;

   
    $admin->role_id = $request->get('role');
    $admin->agent_id =$request->get('agent');
    $admin->updated_at=Carbon::now()->timestamp;
    $admin->update();
    
    Comment::create([
            'content' => Auth::user()->name ." updated admin  ".$admin->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$admin->id ,
            'commendable_type' => "admins"
        
        ]);

    return redirect()->back()->with(['status'=>$admin->name.' Has Been Updated']);
    }abort(404,"Sorry");
  }

  public function destory($id)
  {
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-admin"))
    {
      $admin=Admin::whereId($id)->firstOrFail();

      Comment::create([
            'content' => Auth::user()->name ." deleted admin  ".$admin->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$admin->id ,
            'commendable_type' => "admins"
        
        ]);


      $admin->delete();

      return redirect()->back()->with(['status'=>$admin->name.' Has Been Deleted']);
    }abort(404,"Sorry");
  }

  public function profile()
  {
   if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-profile"))
   {
    $admin=Admin::whereId(Auth::user()->id)->firstOrFail();

    // if($admin->avator_id != null)
    // {
    //    $adminavator=Auth::user()->media()->where('id', $admin->avator_id)->firstOrFail();
    // } 
    // else
    // {
    //    $adminavator=null;
    // }
   
    $avator=null;
    return view('admin.admins.profile',compact('admin','avator'));

    }abort(404,"Sorry");
  }
  public function profileupdate($id,Request $request)
  {

    $admin= Admin::whereId(Auth::user()->id)->firstOrFail();
    if($request->get('password') == "")
    {
      $password= $admin->password;
    }
    else
    {
      $password=bcrypt($request->get('password'));
    }

    $admin->name=$request->get('name');
    $admin->email=$request->get('email');
    $admin->password=$password;

   // $admin->avator_id = $request->get('avator');
   // $admin->role_id = $request->get('role');
   // $admin->agent_id =$request->get('agent');
    $admin->updated_at=Carbon::now()->timestamp;
    $admin->update();   

    return redirect()->back()->with(['status'=>$admin->name.' Has Been Updated']); 
  }

  public function avatorlist()
  {
    $avators = Auth::user()->media()->where('collection_name', 'avator')->paginate(20);
    return view('admin.admins.avatorlist',compact('avators'));   
  }

  public function avatorstore(Request $request)    
  {
        $validated_data = Validator::make($request->all(), [ 
            'avator' => 'required',                      
        ]);

        if ($validated_data->fails()) 
        { 
            return redirect()->back()->with(['errorstatus'=>'File Field is required'], 401);
        }
        
       $user=Auth::user();      
       $user->addMedia($request->file('avator'))->toMediaCollection('avator');      
       return redirect()->back()->with("status","Successfully Saved Avator File");
      
  }
  public function avatordelete($id)
  {
      $avator=Media::whereId($id)->firstOrFail();      
      $avator->delete();
      
      return redirect()->back()->with(['status'=>$avator->file_name.' Has Been Deleted']);
  }
  public function avatorselect(Request $request, $id)
  {
    $avator = Auth::user()->media()->where('id', $id)->firstOrFail();
   
    $admin=Admin::whereId(Auth::user()->id)->firstOrFail();
    $adminavator=null;

    return view('admin.admins.profile',compact('avator','admin','adminavator'));
  }
}
