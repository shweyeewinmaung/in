<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\Http\Requests\SupplierFormRequest;
use App\FTTH;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class SupplierController extends Controller
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-supplier"))
        {
            $supplierlistsall=Supplier::all();
            $supplierlists = Supplier::orderBy('id', 'desc')->paginate(30);
            $s="";
            return view('admin.suppliers.list',compact('supplierlistsall','supplierlists','s'));
        }abort(404,"Sorry");
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-supplier"))
        {
                $s = $request->search;

                $supplierlistsall=Supplier::where('name', 'LIKE', "%{$s}%")
                ->orWhere('company_name', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('supplier_code', 'LIKE', "%{$s}%");
                
                $supplierlists = Supplier::where('name', 'LIKE', "%{$s}%")
                ->orWhere('company_name', 'LIKE', "%{$s}%")
                ->orWhere('address', 'LIKE', "%{$s}%")
                ->orWhere('email', 'LIKE', "%{$s}%")
                ->orWhere('phone', 'LIKE', "%{$s}%")
                ->orWhere('supplier_code', 'LIKE', "%{$s}%")
                ->orderBy('id','desc')
                ->paginate(30);
                
                return view('admin.suppliers.list',compact('supplierlists','supplierlistsall','s'));
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
    public function store(SupplierFormRequest $request)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-supplier"))
        {
            $supplier=Supplier::create([
            'name' => $request->get('name'),
            'company_name' => $request->get('company_name'),
            'supplier_code' => $request->get('supplier_code'),
            'address' => $request->get('address'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            ]);
          Comment::create([
            'content' => Auth::user()->name ." created new supplier  ".$supplier->name ."(".$supplier->supplier_code.')',
            'user_id' => Auth::user()->id,
            'commendable_id' =>$supplier->id ,
            'commendable_type' => "suppliers"
        
        ]);
        // return redirect('/admin/CategoryEntry')->with("status","New Supplier Successfully Saved");
         return redirect()->back()->with('status', 'New Supplier Successfully Saved'); 
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-supplier"))
        {
             $supplier=Supplier::whereId($id)->firstOrFail();
             $supplier->name=$request->get('name');
             $supplier->company_name=$request->get('company_name');
             $supplier->supplier_code=$request->get('supplier_code');
             $supplier->address=$request->get('address');
             $supplier->email=$request->get('email');
             $supplier->phone=$request->get('phone');
             $supplier->updated_at=Carbon::now()->timestamp;
       
            Comment::create([
            'content' => Auth::user()->name ." updated Supplier  ".$supplier->name ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "suppliers"
        
        ]);
        $supplier->update();
       
        return redirect()->back()->with(['status'=>'Supplier ('.$supplier->name.' ) Has Been Updated']);
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-supplier"))
        {
              $supplier=Supplier::whereId($id)->firstOrFail(); 
              Comment::create([
                'content' => Auth::user()->name ." deleted Supplier  ".$supplier->name,
                'user_id' => Auth::user()->id,
                'commendable_id' =>$id ,
                'commendable_type' => "suppliers_table"
              ]);
              $supplier->delete();
      
              return redirect()->back()->with(['status'=>'Supplier ('.$supplier->name.' ) Has Been Deleted']);
        }abort(404,"Sorry");
    }
}
