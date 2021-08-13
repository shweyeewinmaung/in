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



</style>

@endsection
@section('content')
 
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="metismenu pe-7s-user-female">
					</i>
				</div>
				<div>SUPPLIER LISTS
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("create-supplier"))
				<button type="button" class="btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".supplierregister"><i class="metismenu pe-7s-plus"></i> ADD NEW</button>
            @endif


				<div class="modal fade supplierregister" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">SUPPLIER ENTRY</h5>

								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="{{route('supplier.store.submit')}}" >
								<div class="modal-body">

									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-user-female"></i> NAME *</label>
												<input placeholder="Name" type="text" class="form-control" name="name">

												@if ($errors->has('name'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('name') }}
												</div>

												@endif
											</div>
										</div>
                                        </div>
                                        <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-id"></i> COMPANY NAME</label>
												<input placeholder="Company Name" type="text" class="form-control" name="company_name">

												@if ($errors->has('company_name'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('company_name') }}
												</div>

												@endif
											</div>
										 </div>
									   </div>

									    <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-id"></i> SUPPLIER CODE *</label>
												<input placeholder="Supplier Code" type="text" class="form-control" name="supplier_code">

												@if ($errors->has('supplier_code'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('supplier_code') }}
												</div>

												@endif
											</div>
										 </div>
									   </div>

									    <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-phone"></i> PHONE</label>
												<input placeholder="Phone" type="number" class="form-control" name="phone">

												@if ($errors->has('phone'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('phone') }}
												</div>

												@endif
											</div>
										 </div>
									   </div>

									    <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu fa fa-envelope"></i> EMAIL</label>
												<input placeholder="Email" type="text" class="form-control" name="email">

												@if ($errors->has('email'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('email') }}
												</div>

												@endif
											</div>
										 </div>
									   </div>

									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												 <label class="form-group"><i class="metismenu pe-7s-map-marker"></i> ADDRESS</label>
						                          <textarea class="form-control" name="address"></textarea>
						                           @if ($errors->has('address'))
						                            <label class="alerttext">{{ $errors->first('address') }}</label>
						                          @endif
											</div>
										</div>

									</div>

								</div><!----end modal-body--->

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
									<button type="submit" class="btn btn-primary">SAVE</button>
								</div>
							</form>

						</div>
					</div>
				</div>

				

				<!-- </div> -->
				<!-- <div class="col-md-6"> -->

				<form class="search-bar" style="float: right" action="{{route('supplier.search')}}" method="get">
					{{csrf_field()}}
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Enter keywords" name="search" value="{{$s}}" style="border:1px solid#2b3c51;">

						<div class="input-group-append" style="border:1px solid#2b3c51;display: none;" >
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
		@if($supplierlistsall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in Supplier List</label>

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
										<th>NO</th>
										<th>NAME</th>
										<th>CODE</th>

										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-supplier") || 
										\Auth::user()->hasPermission("edit-supplier") ||  \Auth::user()->hasPermission("delete-supplier"))
									   ACTION
									 @endif</th>

									</tr>
								</thead>
								<tbody>
									@foreach($supplierlists as $key=>$supplierlist)
									<tr>
										<td>{{$supplierlists->firstItem() +$key}}</td>
										<td>{{$supplierlist->name}}</td>
										<td>{{$supplierlist->supplier_code}}</td>
										<td>
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-supplier"))
                                             <button class="mr-2 btn-icon btn-icon-only btn btn-outline-primary" data-toggle="modal" data-target=".showsupplier{{$supplierlist->id}}"><i class="pe-7s-look btn-icon-wrapper"> </i></button>
											@endif

											<!-- ------------------View modal Start------------ -->
										<div class="modal fade showsupplier{{$supplierlist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLongTitle">SUPPLIER VIEW FOR {{$supplierlist->name}}</h5>

														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>

													<div class="modal-body">
														<div class="card-body">
															<div class="row">
																<div class="col-md-4 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu-icon pe-7s-user"></i> NAME * </h5>
																		<label style="float: none;"> {{$supplierlist->name}}</label>
																	</div>
																</div>
																<div class="col-md-4 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu-icon pe-7s-id"></i> COMPANY NAME</h5>
																		<label style="float: none;"> {{$supplierlist->company_name}}</label>
																	</div>
																</div>
																<div class="col-md-4 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu-icon pe-7s-id"></i> CODE *</h5>
																		<label style="float: none;"> {{$supplierlist->supplier_code}}</label>
																	</div>
																</div>
																
															</div>

															<div class="row">
																<div class="col-md-4 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu-icon pe-7s-mail"></i> EMAIL</h5>
																		<label style="float: none;"> {{$supplierlist->email}}</label>
																	</div>
																</div>

																<div class="col-md-4 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu pe-7s-phone"></i> PHONE</h5>
																		<label style="float: none;"> {{$supplierlist->phone}}</label>
																	</div>
																</div>
																<div class="col-md-4 pr-md-1">
																	<div class="form-group">
																		
																	</div>
																</div>
															
															</div>
																<div class="row">
																<div class="col-md-12 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu-icon pe-7s-map-marker"></i>ADDRESS </h5>
																		<label style="float: none;"> {{$supplierlist->address}}</label>
																	</div>
																</div>
																
															</div>



														</div><!-----card body end----->
													</div><!----End Modal-body --->

													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>

													</div>


												</div>
											</div>
										</div>
										<!-- ------------------View modal End------------ -->

											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-supplier"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".editsupplier{{$supplierlist->id}}"><i class="pe-7s-tools btn-icon-wrapper"> </i></button>
                                            @endif
											<!-- ------------------Edit Supplier modal Start------------ -->

											<div class="modal fade editsupplier{{$supplierlist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">SUPPLIER UPDATE FOR {{$supplierlist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('supplier.edit.submit',['id'=>$supplierlist->id])}}" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-user-female"></i> NAME *</label>
												<input placeholder="Name" type="text" class="form-control" name="name" value="{{$supplierlist->name}}">

												@if ($errors->has('name'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('name') }}
												</div>

												@endif
											</div>
										</div>
                                        </div>
                                        <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-id"></i>COMPANY NAME</label>
												<input placeholder="Company Name" type="text" class="form-control" name="company_name" value="{{$supplierlist->company_name}}">

												@if ($errors->has('company_name'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('company_name') }}
												</div>

												@endif
											</div>
										 </div>
									   </div>

									    <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-id"></i> SUPPLIER CODE *</label>
												<input placeholder="Supplier Code" type="text" class="form-control" name="supplier_code" value="{{$supplierlist->supplier_code}}">

												@if ($errors->has('supplier_code'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('supplier_code') }}
												</div>

												@endif
											</div>
										 </div>
									   </div>

									    <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-phone"></i> PHONE</label>
												<input placeholder="Phone" type="number" class="form-control" name="phone" value="{{$supplierlist->phone}}">

												@if ($errors->has('phone'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('phone') }}
												</div>

												@endif
											</div>
										 </div>
									   </div>

									    <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu fa fa-envelope"></i> EMAIL</label>
												<input placeholder="Email" type="email" class="form-control" name="email" value="{{$supplierlist->email}}">

												@if ($errors->has('email'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('email') }}
												</div>

												@endif
											</div>
										 </div>
									   </div>

									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												 <label class="form-group"><i class="metismenu pe-7s-map-marker"></i> ADDRESS</label>
						                          <textarea class="form-control" name="address">{{$supplierlist->address}}</textarea>
						                           @if ($errors->has('address'))
						                            <label class="alerttext">{{ $errors->first('address') }}</label>
						                          @endif
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
											
											<!-- ------------------Edit Supplier End------------ -->
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-supplier"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deletesupplier{{$supplierlist->id}}"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                            @endif
											<!-- ------------------Delete Supplier modal Start------------ -->

											<div class="modal fade deletesupplier{{$supplierlist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR {{$supplierlist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('supplier.delete',['id'=>$supplierlist->id])}}" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">

																<div class="row">
																	<div class="col-md-12 pr-md-1">
																		<div class="form-group" align="center">
																			<label ><i class="icon-close"></i> Are you sure to delete?</label>

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
										
											<!-- ------------------Delete Supplier modal End------------ -->
										</td>
									</tr>
									@endforeach
								</tbody>

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL {{$supplierlistsall->count()}}</p>
						{{ $supplierlists->appends(Request::only('search'))->links() }}
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
