@extends('admin.layouts.master')
@section('stylesheet')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
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
.select2 .select2-container .select2-container--default .select2-container--focus
{
	width: 100px;
}
.select2-container--default .select2-selection--single
{
	border-radius: 0px!important;
	height: calc(2.25rem + 2px)!important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered
{
	line-height: 38px!important;
	
}
}


</style>


@endsection
@section('content')
 
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="metismenu pe-7s-add-user">
					</i>
				</div>
				<div>STAFF LISTS
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("create-staff"))
				<button type="button" class="btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".staffregister"><i class="metismenu pe-7s-plus"></i> ADD NEW</button>

            @endif

				<div class="modal fade staffregister" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">STAFF ENTRY</h5>

								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="{{route('staff.store.submit')}}" >
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
												<label><i class="metismenu pe-7s-id"></i> JOB TITLE</label>
												<select type="select"name="jobtitle_id" id="jobtitle_id" class="form-control" >
												<option value="">Click here for Job Title</option>		 
												@foreach($jobtitles as $jobtitle)
												 <option value="{{$jobtitle->id}}">{{$jobtitle->name}}</option>
												 @endforeach
											   </select>
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

				<form class="search-bar" style="float: right" action="{{route('staff.search')}}" method="get">
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
		@if($stafflistsall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in Staff List</label>

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
										<th>TITLE</th>
										<th>EMAIL</th>
										<th>PHONE</th>
										<th>ADDRESS</th>

										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-staff") ||  \Auth::user()->hasPermission("delete-staff"))
									   ACTION
									 @endif</th>

									</tr>
								</thead>
								<tbody>
					            @foreach($stafflists as $key=>$stafflist)
									<tr>
										<td>{{$stafflists->firstItem() +$key}}</td>
										<td>{{$stafflist->name}}</td>
										<td>{{$stafflist->jobtitle['name']}}</td>
										<td>{{$stafflist->email}}</td>
										<td>{{$stafflist->phone}}</td>
										<td>{{$stafflist->address}}</td>
										<td>
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-staff"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".editstaff{{$stafflist->id}}"><i class="pe-7s-tools btn-icon-wrapper"> </i></button>
                                            @endif
                                            <!-- ------------------Edit Staff modal Start------------ -->

											<div class="modal fade editstaff{{$stafflist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">STAFF UPDATE FOR {{$stafflist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('staff.edit.submit',['id'=>$stafflist->id])}}" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-user-female"></i> NAME *</label>
												<input placeholder="Name" type="text" class="form-control" name="name" value="{{$stafflist->name}}">

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
												<label><i class="metismenu pe-7s-id"></i> JOB TITLE</label>
												<select type="select"name="jobtitle_id" id="jobtitle_id" class="form-control" >
											    @if($stafflist->jobtitle_id == "")
												<option value="">Click here for Job Title</option>
												@else
												<option value="{{$stafflist->jobtitle_id}}">{{$stafflist->jobtitle['name']}}</option>	
												@endif	 
												@foreach($jobtitles as $jobtitle)
												@if($stafflist->jobtitle_id != $jobtitle->id)
												 <option value="{{$jobtitle->id}}">{{$jobtitle->name}}</option>
												 @endif
												 @endforeach
											   </select>
											</div>
										 </div>
									   </div>                                        


									    <div class="row">
										 <div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-phone"></i> PHONE</label>
												<input placeholder="Phone" type="number" class="form-control" name="phone" value="{{$stafflist->phone}}">

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
												<input placeholder="Email" type="email" class="form-control" name="email" value="{{$stafflist->email}}">

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
						                          <textarea class="form-control" name="address">{{$stafflist->address}}</textarea>
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
											
											<!-- ------------------Edit Staff End------------ -->
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-staff"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deletestaff{{$stafflist->id}}"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                            @endif
											<!-- ------------------Delete Staff modal Start------------ -->

											<div class="modal fade deletestaff{{$stafflist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR {{$stafflist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('staff.delete',['id'=>$stafflist->id])}}" >
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
										
											<!-- ------------------Delete Staff modal End------------ -->
										</td>
									</tr>
									@endforeach
								</tbody>

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL {{$stafflistsall->count()}}</p>
						{{ $stafflists->appends(Request::only('search'))->links() }}
					</div>
				</div>
			</div>

		</div><!-------------- End row ---->

		@endif
	 
	@endsection
	@section('script')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<script type="text/javascript">
	 
	$(document).ready(function(){
		$("#alert").fadeOut(3000);

	});
    
	</script>
	@endsection
