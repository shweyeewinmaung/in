@extends('admin.layouts.master')
@section('stylesheet')
<style type="text/css">
.app-page-title
{
	margin-bottom: 0px!important;
}
.app-page-title .page-title-icon
{
	margin-right:  0px!important;
	/*margin-top: -50px!important;*/
}
.card-header, .card-title
{
	background: #343c3c;
	padding: 15px;
	color: white;
}
label{
	color:#000;
}
@media (min-width: 576px) {
 
  img.image{
      width: 150px;
      height: 150px
    }
     img.image1{
      width: 150px;
      height: 150px
    }
    img.image2{
       width: 600px;
      height: 400px
    }
   
}
@media (max-width: 575px) {
  
   img.image{
      width: 40px;
      height: 40px
    }
     img.image1{
      width: 50px;
      height: 50px
    }
    img.image2{
       width: 250px;
      height: 200px
    }
}


</style>

@endsection
@section('content')
 
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="metismenu pe-7s-map-marker">
					</i>
				</div>
				<div>{{$name}} STREET ALL ITEMS
					<div class="page-title-subheading">
					</div>
				</div>
			</div>

		</div>
	</div>  <!-------- End app-page-title --->
	<div class="row">

		<div class="col-md-12">
			@if(session('status'))
			<div class="alert alert-success fade show" role="alert" id="alert">{{session('status')}}
			</div>

			@endif
		</div>

		<div class="col-md-12">
			@if(session('errorstatus'))
			<div class="alert alert-danger fade show" role="alert" >{{session('errorstatus')}}
			</div>

			@endif
		</div>

		<div class="col-md-12">

			@foreach($errors->all() as $error)
			<div class="alert alert-danger fade show" role="alert" >{{$error}} <br>
			</div>
			@endforeach

		</div>

		<div class="col-md-12">
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemstreet"))
			   <a href="{{route('streets.list')}}">
				<button type="button" class="btn mr-2 mb-2 btn-primary"  ><i class="metismenu pe-7s-angle-left-circle"></i> BACK TO LIST</button>
			   </a>
            @endif
			</div>

		</div>
		<div class="row">

			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-body" style="padding: 0px">
						<div class="table-responsive">
							<table class="mb-0 table" >
								<thead style="background: #fff;">
								</thead>
							</table>
									 
						</div>
					</div><!--------End card-body ---->
					
				</div>
			</div>

		</div><!-------------- End row ----> 
         @if(count($itemsarrays) <= 0 || count($itemsarrays) == null)
	 
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no Item in {{$name}} </label>

				</div>
			</div>

		</div>
		 
       @else
		<div class="row">

			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-body">
						
	@foreach($itemsarrays as $k=>$itemsarray)
							 @if(is_array($itemsarray))
							 <div class="table-responsive">
								<table class="mb-0 table" style="background: #2b3c51;color: #fff;">
									<thead style="background: #2b3c51;color: #fff;">
										<tr>
											<td>
												<ul><li><h4>{{$itemsarray['categoryname']}}</h4></li></ul>
											</td>
										</tr>
									</thead>
								</table>
							  </div>
                            <div class="table-responsive">
								<table class="table table-bordered" style="background: #2b3c51;color: #fff;">
									@if($itemsarray['itemswithsandm'] != "")
									<thead style="background: #2b3c51;color: #fff;">
										<tr>
											 <th>NO</th>
											 <th>NAME</th>
						                     <th>MAC</th>
						                     <th>SERIAL</th>
						                     <th>MODEL</th>
						                     <th>DAMAGE</th>
						                     <th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itemstreet") ||  \Auth::user()->hasPermission("delete-itemstreet"))
									   ACTION
									 @endif</th>						                     
						                </tr>									 
									</thead>
									<tbody>
										<?php $damagecount=0;  ?>
										   @foreach($itemsarray['itemswithsandm'] as $v=>$itemsm)
											<tr>
												<td>{{$v+1}}</td>
												<td>{{$itemsarray['itemname']}}</td>
												 
												<td>{{$itemsm->mac}}</td>
												<td>{{$itemsm->serial_number}}</td>
												<td>{{$itemsm->model}}</td>
												<td>
													@if($itemsm->damage_qty == 0)
													-
                                                    @else
													<i class="fa fa-check" aria-hidden="true"></i>
													<p>{{$itemsm->damage_reason}}</p>
													@endif
												</td>
												<td>
													@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itemstreet"))
											         <button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".edititem{{$itemsm->item_id}}" title="Edit Item"><i class="pe-7s-tools btn-icon-wrapper"></i></button>
											        @endif
											        <!-- ------------------Edit Item modal Start------------ -->

											<div class="modal fade edititem{{$itemsm->item_id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">ITEM UPDATE</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
									                  <form method="post" action="{{route('ItemByItemStreetUpdate',['id'=>$itemsm->item_id])}}" enctype="multipart/form-data" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
																<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> FTTH - {{$itemsarray['categoryname']}}</label> 
																			 <label class="textlabel"> ITEM NAME - {{$itemsarray['itemname']}}</label>
								                                  
								                                  
																		</div>
																	</div>
																	 
																</div>
																	 
																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			<label>MODEL</label>
																			<input placeholder="MODEL" type="text" class="form-control" name="model" value="{{$itemsm->model}}">

																			@if ($errors->has('model'))
																			<div class="alert alert-danger fade show" role="alert" >
																				{{ $errors->first('model') }}
																			</div>

																			@endif
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			<label>MAC</label>
																			<input placeholder="MAC" type="text" class="form-control" name="mac" value="{{$itemsm->mac}}">

																			@if ($errors->has('mac'))
																			<div class="alert alert-danger fade show" role="alert" >
																				{{ $errors->first('mac') }}
																			</div>

																			@endif
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			<label>SERIAL NUMBER</label>
																			<input placeholder="SERIAL NUMBER" type="text" class="form-control" name="serial_number" value="{{$itemsm->serial_number}}">

																			@if ($errors->has('serial_number'))
																			<div class="alert alert-danger fade show" role="alert" >
																				{{ $errors->first('serial_number') }}
																			</div>

																			@endif
																		</div>
																	</div>

																</div>


																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE </label>
																			  <input type="number" name="damage_qty" value="{{$itemsm->damage_qty}}" class="form-control" min="0" max="1" >
								                                 
																		</div>
																	</div>
																	<div class="col-md-8 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE REASON </label>
																			  <textarea class="form-control" rows="3" name="damage_reason">{{$itemsm->damage_reason}}</textarea>
								                                 
																		</div>
																	</div>
																	 

																</div>


															</div><!--------End modal-body--->

															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
																<button type="submit" class="btn btn-primary">UPDATE</button>
															</div>
														</form>

													</div>
												</div>
											</div>
											
											<!-- ------------------Edit Item End------------ -->
											 
                                             
											 </td>

										</tr>

										   <?php $damagecount+= $itemsm->damage_qty; ?>
										   @endforeach
										   <tr>
										   	<td colspan="5">  </td>
										   	<td> TOTAL DAMAGE - {{$damagecount}} </td>
											<td> TOTAL {{$itemsarray['count']}} ITEMS  </td>

										</tr>
									</tbody>
                                   @endif
                                   @if($itemsarray['itemsbycount'] != "")
                                   <thead style="background: #2b3c51;color: #fff;">
										<tr>											 
											 <th>NAME</th>
											 <th>COUNT</th>
											 <th>DAMAGE</th>
											 <th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itemstreet") ||  \Auth::user()->hasPermission("delete-itemstreet"))
									   ACTION
									 @endif</th>				                     
						                </tr>									 
									</thead>
									<tbody>
										<tr>
											<td>{{$itemsarray['itemname']}}</td>
											<td>{{$itemsarray['count']}}</td>
											<td> {{$itemsarray['itemsbycountwithcount']}} </td>
											<td>
												@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itemstreet"))
											         <button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".edititemwithcount{{$itemsarray['id']}}" title="Edit Item"><i class="pe-7s-tools btn-icon-wrapper"></i></button>
											        @endif
											        <!-- ------------------Edit Item modal Start------------ -->

											<div class="modal fade edititemwithcount{{$itemsarray['id']}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">ITEM UPDATE</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
									                  <form method="post" action="{{route('itembyitemstreetcountupdatealldetail',['itemname_id'=>$itemsarray['id']])}}" enctype="multipart/form-data" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
																<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> FTTH - {{$itemsarray['categoryname']}}</label> 
																			 <label class="textlabel"> ITEM NAME - {{$itemsarray['itemname']}}</label>
								                                  
								                                  
																		</div>
																	</div>
																	 
																</div>
																 


																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE </label>
																			  <input type="number" name="countdamage_qty"  class="form-control" min="0" value="0">
								                                 
																		</div>
																	</div>
																	<div class="col-md-8 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE REASON </label>
																			  <textarea class="form-control" rows="3" name="damage_reason"> </textarea>
								                                 
																		</div>
																	</div>
																	 

																</div>


															</div><!--------End modal-body--->

															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
																<button type="submit" class="btn btn-primary">UPDATE</button>
															</div>
														</form>

													</div>
												</div>
											</div>
											
											<!-- ------------------Edit Item End------------ -->
											 @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itemstreet"))
											 <button class="mr-2 btn-icon btn-icon-only btn btn-outline-warning" data-toggle="modal" data-target=".editredoitemwithcount{{$itemsarray['id']}}" title="Redo Item"><i class="pe-7s-tools btn-icon-wrapper"></i></button>
											 @endif
											  <!-- ------------------REDO Item modal Start------------ -->

											<div class="modal fade editredoitemwithcount{{$itemsarray['id']}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">ITEM REDO</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
									                  <form method="post" action="{{route('ItemByItemStreetRedoCountUpdateAllDetail',['itemname_id'=>$itemsarray['id']])}}" enctype="multipart/form-data" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
																<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> FTTH - {{$itemsarray['categoryname']}}</label> 
																			 <label class="textlabel"> ITEM NAME - {{$itemsarray['itemname']}}</label>
								                                  
								                                  
																		</div>
																	</div>
																	 
																</div>
																 


																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE </label>
																			  <input type="number" name="countredodamage_qty"  class="form-control" min="0" value="0">
								                                 
																		</div>
																	</div>
																	<div class="col-md-8 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE REASON </label>
																			  <textarea class="form-control" rows="3" name="redodamage_reason"> </textarea>
								                                 
																		</div>
																	</div>
																	 

																</div>


															</div><!--------End modal-body--->

															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
																<button type="submit" class="btn btn-primary">REDO</button>
															</div>
														</form>

													</div>
												</div>
											</div>
											
											<!-- ------------------Edit REDO End------------ -->
											</td>
										</tr>
										 
									</tbody>
                                   @endif
								</table>
							 	
							 @endif
							 </div>
							@endforeach
                            
					
					</div>
				</div>
			</div>
			

		</div><!-------------- End row ---->
      @endif
		 <!--  </div> -->
	 
	@endsection
	@section('script')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#alert").fadeOut(3000);

	});

	</script>
	@endsection
