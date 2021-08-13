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
					<i class="metismenu pe-7s-timer">
					</i>
				</div>
				<div>HISTORY LISTS
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

		

		</div>
		@if($historylistsall->count() <= 0) 
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in History List</label>

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
										 
										<th>YEAR</th>

										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-history") ||  \Auth::user()->hasPermission("delete-history"))
										   ACTION
										 @endif</th>

									</tr>
								</thead>
								<tbody>
									@foreach($historylistsall as $k=>$history)
									<tr>										 
										<td><a href="{{route('history.monthlist',['year'=>$k])}}">{{$k}}</a></td>
										<td>
											@if(\Auth::user()->isSuper() ||   \Auth::user()->hasPermission("view-history"))
											<a href="{{route('history.monthlist',['year'=>$k])}}">
										    <button class="mr-2 btn-icon btn-icon-only btn btn-outline-info" ><i class="pe-7s-look btn-icon-wrapper"> </i></button>
										    </a>
										     @endif
											@if(\Auth::user()->isSuper() ||   \Auth::user()->hasPermission("delete-history"))
										     <button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deleteyear{{$k}}" title="Delete"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>

											<!-- ------------------Delete Year modal Start------------ -->

											<div class="modal fade deleteyear{{$k}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR {{$k}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('history.deleteyear',['year'=>$k])}}" >
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
											 
											<!-- ------------------Delete Year modal End------------ -->
										   @endif
										</td>
									</tr>
									@endforeach
								</tbody>
								 

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
							<p style="float: right;">TOTAL {{$historylistsall->count()}}</p>
						 
						 
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
