<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use Auth;
use App\Role;
use App\Comment;
use Carbon\Carbon;
use Validator;

class RoleController extends Controller
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-role"))
        {
          $rolelistsall = Role::all();
          $rolelists = Role::orderBy('id', 'desc')->paginate(30);
          $s="";

          return view('admin.roles.list',compact('rolelistsall','rolelists','s'));
         }abort(404,"Sorry");
    }

    public function searchrole(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-role"))
        {
          $s = $request->search;

          $rolelistsall=Role::where('name', 'LIKE', "%{$s}%");
          $rolelists = Role::where('name', 'LIKE', "%{$s}%")
          ->orderBy('id','desc')
          ->paginate(30);

          return view('admin.roles.list',compact('rolelistsall','rolelists','s'));
        }abort(404,"Sorry");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-role"))
        {
          $permissions = config('role-permissions');
          return view('admin.roles.create',compact('permissions'));

        }abort(404,"Sorry");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
     {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-role"))
       {
          $validated_data = $request->validate([
            'name'  => 'required',
            'permissions'   => 'required|array'
          ]);

          Role::create($request->all());    

          Comment::create([
            'content' => Auth::user()->name ." created New Role ".$request->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>'1',
            'commendable_type' => "roles"
        
        ]);

          return redirect('admin/Rolelist')->with("status","Successfully Saved Role Data");
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-role"))
        {
          $role = Role::FindOrFail($id);
          $permissions = config('role-permissions');
          return view('admin.roles.edit',compact('permissions','role'));
         }abort(404,"Sorry");
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-role"))
        {
          $this->validate($request, [
            'name'  => 'required|unique:roles,name,'.$id,
            'permissions'   => 'required|array'
          ]);

          $role = Role::findOrFail($id)->update($request->all());
          $rolename = Role::findOrFail($id);

          Comment::create([
            'content' => Auth::user()->name ." updated for RoleName ".$rolename->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$rolename->id,
            'commendable_type' => "roles"
          ]);
          return redirect()->route('role.list')->with(['status'=>$request->name.' Has Been Updated']);
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-role"))
        {
          $role=Role::whereId($id)->firstOrFail();
          $rolename = Role::findOrFail($id);
         
          Comment::create([
            'content' => Auth::user()->name ." deleted for RoleName ".$rolename->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$rolename->id,
            'commendable_type' => "roles"
          ]);
          
           $role->delete();

          //return redirect()->back()->with(['status'=>$role->name.' Has Been Deleted']);
          return redirect()->route('role.list')->with(['status'=>$role->name.' Has Been Deleted']);
    }abort(404,"Sorry");
  }
}
