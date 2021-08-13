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
@media (min-width: 576px) {
   
  img.image{
      width: 50px;
      height: 50px
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
					<i class="metismenu pe-7s-server">
					</i>
				</div>
				<div>ITEM LISTS FOR ITEM NAME {{$getitemname->name}} ({{$title->title}})
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
			 
			<a href="{{route('itemname.list',['title'=>$title->title])}}">
				<button type="button" class="btn mr-2 mb-2 btn-primary"><i class="metismenu pe-7s-back"></i> BACK TO ITEM NAME LIST</button>
			</a>
           

				<!-- </div> -->
				<!-- <div class="col-md-6"> -->

				<form class="search-bar" style="float: right" action="{{route('item.search',[$title->title,$key,$storename])}}" method="get">
					{{csrf_field()}}
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Enter keywords" name="search" value="{{$s}}" style="border:1px solid#2b3c51;">

						<div class="input-group-append" style="border:1px solid#2b3c51;display: none;">
							<span class="input-group-text">
								<i class="pe-7s-search"></i>
								<a href="javascript:void();" type="submit" style="display: none">
									<i class="pe-7s-search"></i>
								</a>
							</span>
						</div>
					</div>
					<!--  <input type="text" class="form-control" placeholder="Enter keywords" name="search" value=""> -->

				</form>
			</div>

		</div>
		@if($itemsall->count() <= 0 || $items == null)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no item in {{$store[0]['name']}} Store.</label>

				</div>
			</div>

		</div>
	 
        @else
		<div class="row">

			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-body" style="padding: 0px">
						<div class="table-responsive">
							<table class="mb-0 table">
								<thead>
									<tr>
										<td colspan="11"><h5 style="text-align: center;"> {{$store[0]['name']}}  STORE</h5></td>
									</tr>
								</thead>
								<thead>
									<tr>
										 <th>NO</th>                   	 
					                     <th>PICTURE</th>
					                     <th>VOUCHER</th>
					                     <th>MODEL</th>
					                     <th>MAC</th>
					                     <th>SERIAL</th>
					                     <th>QTY</th>
					                     <th>TOTAL</th>
					                     <th>SUPPLIER</th>
					                     <th>REASON</th>

										<th>
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-viewitem") ||  \Auth::user()->hasPermission("delete-viewitem"))
									         ACTION
									        @endif
									    </th>

									</tr>
								</thead>
							    <tbody>
							    	 @foreach($items as $key=>$item)
							    	 <tr>
							    	 	<td>{{$items->firstItem() +$key}}. </td>
							    	 	<td>
							    	 		@if($item->vouchers->voucher_file)
					                           <button type="button" class="btn" data-toggle="modal" data-target=".myimage{{$item->id}}"> <img class="image" src="{{asset('images/ftth/voucher/'.$item->vouchers->voucher_file)}}" alt=""></button>
					                           
       
					                       @else
					                            <img src="{{asset('images/ftth/voucher/voucher.jpg')}}" class="img-responsive image">
					                          
					                       @endif 
					                       <!-- ------------------Picture modal Start------------ -->

										<div class="modal fade myimage{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">														

														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
														
													</div>
													<div class="modal-body">
														 <img src="{{asset('images/ftth/voucher/'.$item->vouchers->voucher_file)}}" class="image2">
													</div>												

												</div>
											</div>
										</div>
									
										<!-- ------------------Picture modal End------------ -->
							    	 	</td>
							    	 	<td>{{$item->vouchers->voucher_code}}</td>
							    	 	<td>{{$item->model}}</td>
							    	 	<td>{{$item->mac}}</td>
							    	 	<td>{{$item->serial_number}}</td>
							    	 	<td>Qty - {{$item->qty}}<br>
												Used - {{$item->used_qty}}<br>
												Transfer - {{$item->transfer_qty}}<br>
												Damage - {{$item->damage_qty}}<br>
										</td>
										<td>												
												Unit - {{$item->unit_price}}<br>
                                                Amount - {{$item->amount}}<br>
                                                Total - {{$item->total_qty}}<br>
										</td>
										<td>{{$item->vouchers->supplier->name}}</td>
										<td>{{$item->damage_reason}}</td>
										<td>
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-viewitem") )
												<button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".editviewitem{{$item->id}}"><i class="pe-7s-tools btn-icon-wrapper"> </i></button>
											@endif

											<!-- ------------------Edit View Item modal Start------------ -->

											<div class="modal fade editviewitem{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">ITEMS UPDATE</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('ItemsBuyList.viewdetailedit',$item->id)}}" enctype="multipart/form-data"  >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
																	<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> FTTH - {{$title->title}}</label>
								                                  <label class="textlabel"> Item Name - {{$getitemname->name}}</label>
								                                  
																		</div>
																	</div>
																	<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			  
								                                  <label class="textlabel">Code - {{$getitemname->account_code}}</label>
								                                  <label class="textlabel">TOTAL QTY - {{$item->total_qty}}</label>
																		</div>
																	</div>
																	<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> UNIT PRICE - {{$item->unit_price}}</label>
								                                  <label class="textlabel">AMOUNT - {{$item->amount}}</label>
								                                  
																		</div>
																	</div>

																	<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> VOUCHER CODE - {{$item->vouchers->voucher_code}}</label>
								                                
								                                  
																		</div>
																	</div>

																</div>

																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> QTY </label>
																			  <input type="number" name="qty" value="{{$item->qty}}" class="form-control" readonly="" style="background: #d5d5d5;">
								                                 
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> USED QTY </label>
																			  <input type="number" name="used_qty" value="{{$item->used_qty}}" class="form-control" readonly="" style="background: #d5d5d5;">
								                                 
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel">TRANSFER QTY </label>
																			  <input type="number" name="transfer_qty" value="{{$item->transfer_qty}}" class="form-control" readonly="" style="background: #d5d5d5;">
								                                 
																		</div>
																	</div>
																	 

																</div>

																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> MODEL </label>
																			  <input type="text" name="model" value="{{$item->model}}" class="form-control">
								                                 
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> MAC </label>
																			  <input type="text" name="mac" value="{{$item->mac}}" class="form-control">
								                                 
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel">SERIAL NUMBER </label>
																			  <input type="text" name="serial_number" value="{{$item->serial_number}}" class="form-control">
								                                 
																		</div>
																	</div>
																	 

																</div>


																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE </label>
																			  <input type="number" name="damage_qty" value="{{$item->damage_qty}}" class="form-control">
								                                 
																		</div>
																	</div>
																	<div class="col-md-8 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE REASON </label>
																			  <textarea class="form-control" rows="3" name="damage_reason">{{$item->damage_reason}}</textarea>
								                                 
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
											 
											<!-- ------------------Edit View Item  End------------ -->
                                            @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-viewitem"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deleteviewitem{{$item->id}}"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                            @endif

                                            <!-- ------------------Delete View ItemDetail modal Start------------ -->

											<div class="modal fade deleteviewitem{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR ITEM {{$item->category->title}}({{$item->itemname->account_code}})</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{action('ItemController@viewdetailitemdelete',['id'=>$item->id])}}" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">

																<div class="row">
																	<div class="col-md-12 pr-md-1">
																		<div class="form-group" align="center">
																			<label ><i class="icon-close"></i> Are you sure to delete for Item ID ({{$item->id}})?</label>

																		</div>
																	</div>
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
																<button type="submit" class="btn btn-primary">CONFIRM</button>
															</div>
														</form>

													</div>
												</div>
											</div>
											
											<!-- ------------------Delete View ITEM modal End------------ -->
										</td>
							    	 </tr>
							    	 @endforeach
							    </tbody>

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL {{$itemsall->count()}} </p>
						{{ $items->appends(Request::only('search'))->links() }}
						 
					</div>
				</div>
			</div>

		</div><!-------------- End row ---->
       @endif
	 
	@endsection
	@section('script')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#alert").fadeOut(3000);

	});

	</script>
	@endsection
