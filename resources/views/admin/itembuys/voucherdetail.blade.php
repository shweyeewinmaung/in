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
					<i class="metismenu pe-7s-server">
					</i>
				</div>
				<div>ITEMS BUY DETAIL FOR VOUCHER {{$voucher->voucher_code}}
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itembuy"))
			   <a href="{{route('itemsbuy.list')}}">
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
									 <tr>
									 	 <th style="border-bottom: none" rowspan="3">
									 	 	@if($voucher->voucher_file)
					                           <button type="button" class="btn" data-toggle="modal" data-target=".myimage{{$voucher->id}}"> <img class="image" src="{{asset('images/ftth/voucher/'.$voucher->voucher_file)}}" alt="" style="border:1px solid#777; background: #fff"></button>
					                           
       
					                       @else
					                            <img src="{{asset('images/voucher.jpg')}}" class="img-responsive image"  style="border:1px solid#777">
					                          
					                       @endif 
					                       <!-- ------------------Picture modal Start------------ -->

										<div class="modal fade myimage{{$voucher->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">														

														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
														
													</div>
													<div class="modal-body">
														 <img src="{{asset('images/ftth/voucher/'.$voucher->voucher_file)}}" class="image2">
													</div>												

												</div>
											</div>
										</div>
										<!-- ------------------Picture modal End------------ -->
                       
                                         </th>
					                     <th colspan="2" style="border-top: none;border-bottom: none; background: #5a6471;color: #fff;">
					                      	VOUCHER - {{$voucher->voucher_code}}
					                      </th> 
					                      </tr>
					                      <tr>
					                      <th style="border-top: none;border-bottom: none; background: #5a6471;color: #fff;"><label>STORE - {{$voucher->store->name}}</label></th>
					                      <th style="border-top: none;border-bottom: none; background: #5a6471;color: #fff;"><label>SUPPLIER - {{$voucher->supplier->name}}</label></th>
					                     </tr>
					                      <tr>
					                      <th style="border-top: none;border-bottom: none; background: #5a6471;color: #fff;"><label>BY - {{$voucher->user->name}}</label></th> 
					                      <th style="border-top: none;border-bottom: none; background: #5a6471;color: #fff;"><label>CREATED - {{ date('Y-m-d', strtotime($voucher->created_at)) }} ({{$voucher->created_at->format('H:i:s')}})</label><br><label>
					                      UPDATED - {{ date('Y-m-d', strtotime($voucher->updated_at)) }} ({{$voucher->updated_at->format('H:i:s')}})</label></th>              
									 </tr>
								</thead>
							 

							</table>
						</div>
					</div><!--------End card-body ---->
					
				</div>
			</div>

		</div><!-------------- End row ---->
		@if($itemsbyvouchersall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no Item in Voucher {{$voucher->voucher_code}}</label>

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
										  
					                     <th>FTTH</th>                   	 
					                     <th>NAME</th>
					                     <th>CODE</th>
					                     <th>MODEL</th>
					                     <th>MAC</th>
					                     <th>SERIAL</th>
					                     <th>QTY</th>
					                     <th>TOTAL</th>
					                     <th>REASON</th>
					                   

										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itembuy") ||  \Auth::user()->hasPermission("delete-itembuy"))
									   ACTION
									   @endif</th>

									</tr>
									<tbody>
										@foreach($itemsbyvouchers as $key=>$itemsbyvoucher)

										<tr >
											 
											<td>{{$itemsbyvouchers->firstItem() +$key}} . {{$itemsbyvoucher->category->title}}</td>
											<td>{{$itemsbyvoucher->itemname->name}}</td>
											<td>{{$itemsbyvoucher->itemname->account_code}}</td>
											<td>{{$itemsbyvoucher->model}}</td>
											<td>{{$itemsbyvoucher->mac}}</td>
											<td>{{$itemsbyvoucher->serial_number}}</td>
											
											<td>Qty - {{$itemsbyvoucher->qty}}<br>
												Used - {{$itemsbyvoucher->used_qty}}<br>
												Transfer - {{$itemsbyvoucher->transfer_qty}}<br>
												Damage - {{$itemsbyvoucher->damage_qty}}<br>
											</td>
											<td>												
												Unit - {{$itemsbyvoucher->unit_price}}<br>
                                                Amount - {{$itemsbyvoucher->amount}}<br>
                                                Total - {{$itemsbyvoucher->total_qty}}<br>
											</td>
											
											<td>{{$itemsbyvoucher->damage_reason}}</td>
											<td>
												@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itembuy") )
												<button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".edititembuy{{$itemsbyvoucher->id}}" title="Edit"><i class="pe-7s-tools btn-icon-wrapper"> </i></button>
												@endif
												<!-- ------------------Edit Item Buy modal Start------------ -->

											<div class="modal fade edititembuy{{$itemsbyvoucher->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">ITEMS BUY UPDATE</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('ItemsBuyList.viewdetailedit',$itemsbyvoucher->id)}}" enctype="multipart/form-data"  >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
																	<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> FTTH - {{$itemsbyvoucher->category->title}}</label>
								                                  <label class="textlabel"> ITEM NAME - {{$itemsbyvoucher->itemname->name}}</label>
								                                  
																		</div>
																	</div>
																	<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			  
								                                  <label class="textlabel">CODE - {{$itemsbyvoucher->itemname->account_code}}</label>
								                                  <label class="textlabel">TOTAL QTY - {{$itemsbyvoucher->total_qty}}</label>
																		</div>
																	</div>
																	<div class="col-md-3 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> UNIT PRICE - {{$itemsbyvoucher->unit_price}}</label>
								                                  <label class="textlabel">AMOUNT - {{$itemsbyvoucher->amount}}</label>
								                                  
																		</div>
																	</div>

																</div>

																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> QTY </label>
																			  <input type="number" name="qty" value="{{$itemsbyvoucher->qty}}" class="form-control" readonly="" style="background: #d5d5d5;">
								                                 
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> USED QTY </label>
																			  <input type="number" name="used_qty" value="{{$itemsbyvoucher->used_qty}}" class="form-control" readonly="" style="background: #d5d5d5;">
								                                 
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel">TRANSFER QTY </label>
																			  <input type="number" name="transfer_qty" value="{{$itemsbyvoucher->transfer_qty}}" class="form-control" readonly="" style="background: #d5d5d5;">
								                                 
																		</div>
																	</div>
																	 

																</div>

																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> MODEL </label>
																			  <input type="text" name="model" value="{{$itemsbyvoucher->model}}" class="form-control">
								                                 
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> MAC </label>
																			  <input type="text" name="mac" value="{{$itemsbyvoucher->mac}}" class="form-control">
								                                 
																		</div>
																	</div>
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel">SERIAL NUMBER </label>
																			  <input type="text" name="serial_number" value="{{$itemsbyvoucher->serial_number}}" class="form-control">
								                                 
																		</div>
																	</div>
																	 

																</div>


																<div class="row">
																	<div class="col-md-4 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE </label>
																			  <input type="number" name="damage_qty" value="{{$itemsbyvoucher->damage_qty}}" class="form-control">
								                                 
																		</div>
																	</div>
																	<div class="col-md-8 pr-md-1">
																		<div class="form-group">
																			 <label class="textlabel"> DAMAGE REASON </label>
																			  <textarea class="form-control" rows="3" name="damage_reason">{{$itemsbyvoucher->damage_reason}}</textarea>
								                                 
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
											 
											<!-- ------------------Edit Item Buy End------------ -->

											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-itembuy"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deleteitembuy{{$itemsbyvoucher->id}}" title="Delete"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                            @endif
											<!-- ------------------Delete Item Buy Detail modal Start------------ -->

											<div class="modal fade deleteitembuy{{$itemsbyvoucher->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR ITEM {{$itemsbyvoucher->category->title}}({{$itemsbyvoucher->itemname->account_code}})</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{action('ItemController@viewdetaildelete',['id'=>$itemsbyvoucher->id])}}" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">

																<div class="row">
																	<div class="col-md-12 pr-md-1">
																		<div class="form-group" align="center">
																			<label ><i class="icon-close"></i> Are you sure to delete for Item ID ({{$itemsbyvoucher->id}})?</label>

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
											
											<!-- ------------------Delete ITEM Buy modal End------------ -->
											</td>
										</tr>
										@endforeach
									</tbody>
								</thead>
								

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL  {{$itemsbyvouchersall->count()}}</p>
						{{ $itemsbyvouchers->appends(Request::only('search'))->links() }}
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
