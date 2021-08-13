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
					<i class="metismenu pe-7s-id">
					</i>
				</div>
				<div>JOB TITLE LISTS
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("create-jobtitle"))
				<button type="button" class="btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".jobtitleregister"><i class="metismenu pe-7s-plus"></i> ADD NEW</button>
            @endif


				<div class="modal fade jobtitleregister" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">JOB TITLE ENTRY</h5>

								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="{{route('jobtitle.store.submit')}}" >
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

				<form class="search-bar" style="float: right" action="{{route('jobtitle.search')}}" method="get">
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
		@if($jobitlelistsall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in Job Title List</label>

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
										 
										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-jobtitle") ||  \Auth::user()->hasPermission("delete-jobtitle"))
									   ACTION
									 @endif</th>

									</tr>
								</thead>
								<tbody>
									@foreach($jobitlelists as $key=>$jobitlelist)
									<tr>
										<td>{{$jobitlelists->firstItem() +$key}}</td>
										<td>{{$jobitlelist->name}}</td>
										<td>											

											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-jobtitle"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".editjobtitle{{$jobitlelist->id}}"><i class="pe-7s-tools btn-icon-wrapper"> </i></button>
                                            @endif
											<!-- ------------------Edit Agent modal Start------------ -->

											<div class="modal fade editjobtitle{{$jobitlelist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">JOB TITLE UPDATE FOR {{$jobitlelist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('jobtitle.edit.submit',['id'=>$jobitlelist->id])}}" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-user-female"></i> NAME *</label>
												<input placeholder="Name" type="text" class="form-control" name="name" value="{{$jobitlelist->name}}">

												@if ($errors->has('name'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('name') }}
												</div>

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
											
											<!-- ------------------Edit Agent End------------ -->
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-jobtitle"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deletejobtitle{{$jobitlelist->id}}"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>

											<!-- ------------------Delete Agent modal Start------------ -->

											<div class="modal fade deletejobtitle{{$jobitlelist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR {{$jobitlelist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('jobtitle.delete',['id'=>$jobitlelist->id])}}" >
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
						<p style="float: right;">TOTAL {{$jobitlelistsall->count()}}</p>
						{{ $jobitlelists->appends(Request::only('search'))->links() }}
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
