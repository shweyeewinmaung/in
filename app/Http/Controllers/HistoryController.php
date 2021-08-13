<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use Carbon\Carbon;


class HistoryController extends Controller
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
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-history"))
       {
          $historylistsall= Comment::get()
          ->groupBy(function($val) {
          return Carbon::parse($val->updated_at)->format('Y');
         }); 
 
          return view('admin.historys.list',compact('historylistsall'));         
       }
    }

    public function monthlist($year)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-history"))
       {
         $historymonthlistsall = Comment::whereYear('updated_at', '=', $year)->get()
        ->groupBy(function($val) {
          return Carbon::parse($val->updated_at)->format('M');
         });        
          
        return view('admin.historys.monthlist',compact('historymonthlistsall','year'));     
       }
    }
    public function daylist($year,$month)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-history"))
       {
         $date =date("m",strtotime($month));
         $historydaylistsall = Comment::whereYear('updated_at', '=', $year)
              ->whereMonth('updated_at', '=', $date)->get()
             ->groupBy(function($val) {
          return Carbon::parse($val->updated_at)->format('d');
         }); 
         
       return view('admin.historys.daylist',compact('historydaylistsall','year','month')); 
           
       }
    }

    public function datelist($year,$month,$day)
    {
       if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-history"))
       {
         $date =date("m",strtotime($month));
         $s="";
         
         $historydatelistsall = Comment::whereYear('updated_at', '=', $year)
                             ->whereMonth('updated_at', '=', $date)
                             ->whereDay('updated_at', '=', $day)
                             ->orderby('id','desc');
         $historydatelists = Comment::whereYear('updated_at', '=', $year)
                             ->whereMonth('updated_at', '=', $date)
                             ->whereDay('updated_at', '=', $day)
                             ->orderby('id','desc')
                             ->paginate(50);                             
                              
       
         return view('admin.historys.datelist',compact('s','historydatelistsall','historydatelists','day','month','year'));
      }
    }

    public function searchdate(Request $request,$year,$month,$day)
   {
    if(Auth::user()->isSuper() || Auth::user()->hasPermission("view-history"))
    {
        $date =date("m",strtotime($month));
        $dd=$year.'-'.$date.'-'.$day;
        //dd($dd);
        $s = $request->search;
      
        $historydatelistsall = Comment::where('updated_at','LIKE','%'.$dd.'%')
                              ->Where('content', 'LIKE', "%{$s}%")
                            //   ->WhereHas('admin', function($q) use ($s){
                            //   return $q->where('name','like','%'. $s . '%');
                            // })

                             ->orderby('id','desc');
        // dd($historydatelistsall->count());
        $historydatelists = Comment::where('updated_at','LIKE','%'.$dd.'%')
                              ->where('content', 'LIKE', "%{$s}%")
                            //  ->WhereHas('admin', function($q) use ($s){
                            //   return $q->where('name','like','%'. $s . '%');
                            // })
                            ->orderby('id','desc')
                            ->paginate(50); 

  return view('admin.historys.datelist',compact('s','historydatelistsall','historydatelists','day','month','year'));
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function destroyyear($year)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-history"))
      {
        $years = Comment::whereYear('updated_at', '=', $year)->get();
        foreach($years as $v=>$val)
        {
            $deleteyear = Comment::whereId($val->id)->firstOrFail();
            $deleteyear->delete();
        }
         Comment::create([
            'content' => Auth::user()->name ." deleted History for  ".$year ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$year,
            'commendable_type' => "comments"
        
        ]);
        return redirect()->back()->with(['status'=>$year.' Has Been Deleted']);
              // $post = Mjblog::whereYear('created_at', '=', $year)
              // ->whereMonth('created_at', '=', $month)
              // ->get();
      }abort(404,"Sorry");
    }
    public function destroymonth($year,$month)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-history"))
      {
        $date =date("m",strtotime($month));
          
        $years = Comment::whereYear('updated_at', '=', $year)
        ->whereMonth('updated_at', '=',$date)
        ->get();
         
        foreach($years as $v=>$val)
        {
            $deleteyear = Comment::whereId($val->id)->firstOrFail();
            $deleteyear->delete();
        }
         Comment::create([
            'content' => Auth::user()->name ." deleted History ".$month." in ".$year ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$year,
            'commendable_type' => "comments"
        
        ]);
        return redirect()->back()->with(['status'=>$month .' in '.$year.' Has Been Deleted']);
       }abort(404,"Sorry");
    }

    public function destroyday($year,$month,$day)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-history"))
      {
        $date =date("m",strtotime($month));
          
        $years = Comment::whereYear('updated_at', '=', $year)
        ->whereMonth('updated_at', '=',$date)
        ->whereDay('updated_at', '=',$day)
        ->get();
        // dd($years);
        foreach($years as $v=>$val)
        {
            $deleteyear = Comment::whereId($val->id)->firstOrFail();
            $deleteyear->delete();
        }
         Comment::create([
            'content' => Auth::user()->name ." deleted History ".$day.' in '.$month." in ".$year ,
            'user_id' => Auth::user()->id,
            'commendable_id' =>$year,
            'commendable_type' => "comments"
        
        ]);
        return redirect()->back()->with(['status'=>$day .' in '.$month .' in '.$year.' Has  Been Deleted']);
       }abort(404,"Sorry");
    }

    public function destroydata($id)
    {
      if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-history"))
      {
        $history =Comment::whereId($id)->firstOrFail();
        $history->delete();

         Comment::create([
            'content' => Auth::user()->name ." deleted History One Record",
            'user_id' => Auth::user()->id,
            'commendable_id' =>$id,
            'commendable_type' => "comments"
        
        ]);
        return redirect()->back()->with(['status'=>' One History Record Has Been Deleted']);
      }abort(404,"Sorry");
    }
}
