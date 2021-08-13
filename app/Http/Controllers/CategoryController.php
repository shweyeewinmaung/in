<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryFormRequest;
use App\Category;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CategoryController extends Controller
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
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-category"))
        {
            $categorylistsall=Category::all();
            $categorylists = Category::orderBy('id', 'asc')->paginate(24);
            $s="";
           
            return view('admin.categories.list',compact('categorylistsall','categorylists','s'));
        }abort(404,"Sorry");       
    }

    public function searchpost(Request $request)
    {
        if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-category"))
        {
            $s = $request->search;
            $categorylistsall=Category::where('title', 'LIKE', "%{$s}%");            
             $categorylists = Category::where('title', 'LIKE', "%{$s}%")
            ->orderBy('id','asc')
            ->paginate(24);
            
            return view('admin.categories.list',compact('categorylistsall','categorylists','s'));
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
    public function store(CategoryFormRequest $request)
    {    

      if(Auth::user()->isSuper() || Auth::user()->hasPermission("create-category"))
      {     
        $file=$request->file('file');
        $filename=uniqid().'_'.$file->getClientOriginalName();
        $file->move(public_path().'/images/ftth',$filename);

        if($request->mac == 'on'){$mac='1';}else{$mac='0';};
        if($request->serial == 'on'){$serial='1';}else{$serial='0';};

        $category=Category::create([
            'title' => $request->get('title'),
            'file' =>$filename,
            'mac' => $mac,
            'serial' => $serial
            ]);

        Comment::create([
            'content' => Auth::user()->name ." created New FTTH  ".$category->title ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$category->id ,
            'commendable_type' => "categories"        
        ]);

         return redirect('/admin/FTTH')->with("status","New FTTH Successfully Saved");
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
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-category"))
      {
        $category=Category::whereId($id)->firstOrFail();
        $category->title=$request->get('title');
        $category->updated_at=Carbon::now()->timestamp;

        if($request->hasFile('file'))
         {
           $file=$request->file('file');
           $filename=uniqid().'-'.$file->getClientOriginalName();
           $file->move(public_path().'/images/ftth/',$filename);       
           $category->file=$filename;    
        }
        Comment::create([
            'content' => Auth::user()->name ." updated FTTH ".$category->title ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "categories"
        
        ]);
        $category->update();
       
        return redirect()->back()->with(['status'=>'FTTH  ('.$category->title.' ) Has Been Updated']);
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
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-category"))
      {
         $category=Category::whereId($id)->firstOrFail(); 
         $category->delete();
         Comment::create([
            'content' => Auth::user()->name ." deleted FTTH ".$category->title,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id ,
            'commendable_type' => "categories"
        
        ]);
         return redirect()->back()->with(['status'=>'FTTH ('.$category->title.' ) Has Been Deleted']);
      }abort(404,"Sorry");
    }
}
