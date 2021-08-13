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
					<i class="metismenu pe-7s-map-marker">
					</i>
				</div>
				<div>STORE LISTS
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("create-store"))
				<button type="button" class="btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".storeregister"><i class="metismenu pe-7s-plus"></i> ADD NEW</button>



				<div class="modal fade storeregister" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">STORE ENTRY</h5>

								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="{{route('store.entry.submit')}}" >
								<div class="modal-body">

									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-map-marker"></i> NAME *</label>
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

				@endif

				<!-- </div> -->
				<!-- <div class="col-md-6"> -->

				<form class="search-bar" style="float: right" action="{{route('store.search')}}" method="get">
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
		@if($storelistsall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in Store List</label>

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
										<th>ADDRESS</th>

										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-store") ||  \Auth::user()->hasPermission("delete-store"))
									   ACTION
									 @endif</th>

									</tr>
								</thead>
								<tbody>
									@foreach($storelists as $key=>$storelist)
									<tr>
										<td>{{$storelists->firstItem() +$key}}</td>
										<td>{{$storelist->name}}</td>
										<td>{{$storelist->address}}</td>
										<td>
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-store"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".editstore{{$storelist->id}}"><i class="pe-7s-tools btn-icon-wrapper"> </i></button>

											<!-- ------------------Edit Agent modal Start------------ -->

											<div class="modal fade editstore{{$storelist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">STORE UPDATE FOR {{$storelist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('store.edit.submit',['id'=>$storelist->id])}}" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
																	<div class="col-md-12 pr-md-1">
																		<div class="form-group">
																			<label><i class=" metismenu pe-7s-map-marker"></i> NAME *</label>
																			<input placeholder="Name" type="text" class="form-control" name="name" value="{{$storelist->name}}">

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
																			 <label class="form-group"><i class="metismenu pe-7s-map-marker"></i> ADDRESS</label>
													                          <textarea class="form-control" name="address">{{$storelist->address}}</textarea>
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
											@endif
											<!-- ------------------Edit Agent End------------ -->
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-store"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deletestore{{$storelist->id}}"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>

											<!-- ------------------Delete Agent modal Start------------ -->

											<div class="modal fade deletestore{{$storelist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR {{$storelist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('store.delete',['id'=>$storelist->id])}}" >
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
											@endif
											<!-- ------------------Delete Agent modal End------------ -->
										</td>
									</tr>
									@endforeach
								</tbody>

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL {{$storelistsall->count()}}</p>
						{{ $storelists->appends(Request::only('search'))->links() }}
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
