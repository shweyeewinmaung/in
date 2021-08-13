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
				<div>ADMIN LISTS
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("create-admin"))
			<a href="{{route('admin.create')}}">
				<button type="button" class="btn mr-2 mb-2 btn-primary"><i class="metismenu pe-7s-plus"></i> ADD NEW</button>
			</a>
			@endif

			<!-- </div> -->
			<!-- <div class="col-md-6"> -->

			<form class="search-bar" style="float: right" action="{{route('admin.search')}}" method="get">
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
	@if($adminlistsall->count() <= 0)
	<div class="row">
		<div class="col-md-12 pr-md-1">
			<div class="form-group">
				<label>There is no data in Admin List</label>

			</div>
		</div>

	</div>
	@else

	<div class="row">

		<div class="col-md-12">
			<div class="main-card mb-3 card">
				<div class="card-body" style="padding: 0px">
					<!-- <h5 class="card-title" style="text-align: center;">Admin List</h5> -->
					<div class="table-responsive">
						<table class="mb-0 table">
							<thead>
								<tr>
									<th>NO</th>
									
									<th>NAME</th>
									<th>EMAIL</th>
									<th>ROLE</th>
									<th>AGENT</th>
									<th> @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-admin") ||  \Auth::user()->hasPermission("delete-admin"))
									   ACTION
									 @endif</th>

								</tr>
							</thead>
							<tbody>
							@foreach($adminlists as $key=>$admin)
							   
								
								<tr>
									<td>{{$adminlists->firstItem() +$key}}</td>
								
									<td>{{$admin->name}}</td>
									<td>{{$admin->email}}</td>
									<td>{{$admin->role['name']}}</td>
									<td>{{$admin->agent['name']}}</td>
									<td>

										<button class="mr-2 btn-icon btn-icon-only btn btn-outline-primary" data-toggle="modal" data-target=".show{{$admin->id}}"><i class="pe-7s-look btn-icon-wrapper"> </i></button>
										<!-- ------------------View modal Start------------ -->
										<div class="modal fade show{{$admin->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLongTitle">ADMIN VIEW FOR {{$admin->name}}</h5>

														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>

													<div class="modal-body">
														<div class="card-body">
															<div class="row">
																<div class="col-md-6 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu-icon pe-7s-user"></i> NAME *</h5>
																		<label style="float: none;"> {{$admin->name}}</label>
																	</div>
																</div>
																<div class="col-md-6 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu-icon pe-7s-mail"></i> EMAIL *</h5>
																		<label style="float: none;"> {{$admin->email}}</label>
																	</div>
																</div>
																
															</div>

															<div class="row">
																<div class="col-md-6 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu pe-7s-id"></i> ROLE</h5>
																		<label style="float: none;"> {{$admin->role['name']}}</label>
																	</div>
																</div>
																<div class="col-md-6 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu pe-7s-users"></i> AGENT</h5>
																		<label style="float: none;"> {{$admin->agent['name']}}</label>
																	</div>
																</div>
																<!-- <div class="col-md-8 pr-md-1">
																	<div class="form-group">
																		<h5><i class="metismenu pe-7s-photo"></i> AVATOR</h5>
																		

																	</div>
																</div>
																<div class="col-md-4 pr-md-1">

																</div> -->
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
										@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-admin"))
										<a href="{{route('admin.edit',['id'=>$admin->id])}}">
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" ><i class="pe-7s-tools btn-icon-wrapper"> </i></button>
										</a>
										@endif
										@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-admin"))
										<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deleteadmin{{$admin->id}}"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
										@endif
										<!-- ------------------Delete Admin modal Start------------ -->

										<div class="modal fade deleteadmin{{$admin->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLongTitle">ADMIN DELETE FOR {{$admin->name}}</h5>

														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<form method="post" action="{{route('admin.delete',['id'=>$admin->id])}}" >
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
										<!-- ------------------Delete Admin modal End------------ -->

									</td>

								</tr>
								
								@endforeach
							</tbody>
						</table>
					</div>
				</div><!--------End card-body ---->
				<div class="card-body" >
					<p style="float: right;">TOTAL {{$adminlistsall->count()}}</p>
					{{ $adminlists->appends(Request::only('search'))->links() }}
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
