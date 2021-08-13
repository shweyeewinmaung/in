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
				<div>{{$name}} SERVER  
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemserver"))
			<a href="{{route('servers.list')}}">
				<button type="button" class="btn mr-2 mb-2 btn-primary" ><i class="metismenu pe-7s-angle-left-circle"></i> BACK TO LIST</button>
			</a>
            @endif

				<!-- </div> -->
				<!-- <div class="col-md-6"> -->

				<form class="search-bar" style="float: right" action="{{route('serverssearchview.show',['name'=>$name])}}" method="get">
					{{csrf_field()}}
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Enter keywords" name="searchdetail" value="{{$searchdetail}}" style="border:1px solid#2b3c51;">

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
	   	@if($serverbyserver_namesall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in Server List</label>

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
										<th>PICTURE</th>
										<th>CODE</th>
										<th>STAFF</th>
										<th>STATUS</th>
										<th>CONFIRM</th>
										<th>CONFIRM BY</th>

										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemserver") ||  \Auth::user()->hasPermission("view-itemserver"))
									   ACTION
									 @endif</th>

									</tr>
								</thead>
								<tbody>
									@foreach($serverbyserver_names as $key=>$serverbyserver_name)
									<tr>
										<td>{{$serverbyserver_names->firstItem() +$key}}</td>
										<td>
                                            @if($serverbyserver_name->sign_file)
					                           <button type="button" class="btn" data-toggle="modal" data-target=".myimage{{$serverbyserver_name->id}}"> <img class="image" src="{{asset('images/ftth/servers/'.$serverbyserver_name->sign_file)}}" alt=""></button>
					                           
       
					                       @else
					                            <img src="{{asset('images/voucher.jpg')}}" class="img-responsive image">
					                          
					                       @endif 
					                       <!-- ------------------Picture modal Start------------ -->

										<div class="modal fade myimage{{$serverbyserver_name->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">														

														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
														
													</div>
													<div class="modal-body">
														 <img src="{{asset('images/ftth/servers/'.$serverbyserver_name->sign_file)}}" class="image2">
													</div>												

												</div>
											</div>
										</div>
										<!-- ------------------Picture modal End------------ -->
										</td>
										<td>{{$serverbyserver_name->server_number}} </td>
										<td>{{$serverbyserver_name->staff->name}} ({{$serverbyserver_name->staff->jobtitle['name']}})</td>
										<td>
											    @if($serverbyserver_name->status == 'pending')
					                   			{{$serverbyserver_name->status}}
					                   			 @else
					                   			   Confirm
					                   			@endif 
										</td>
										<td>
											
 
											@if($serverbyserver_name->status == 'pending')
											 @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("confirm-itemserver"))
				                   			 <a href="{{route('serverdetail.confirm',['id'=>$serverbyserver_name->id,'name'=>$name])}}" type="submit"><button class="btn btn-light" type="submit" style="background:#2b3c51;color:#fff;">Confirm</button></a>
				                   			 @endif
				                   			 @else
				                   			<i class="fa fa-check" aria-hidden="true"></i>@endif 
				                   			@if($serverbyserver_name->status == 'confirm')
											 @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("redoconfirm-itemserver"))
				                   			 <a href="{{route('serverdetail.redoconfirm',['id'=>$serverbyserver_name->id,'name'=>$name])}}" type="submit"><button class="btn btn-light" type="submit" style="background:#2b3c51;color:#fff;">Redo</button></a>
				                   			 @endif
				                   			 @endif 
				                   			
										</td>
										<td>
											 @if($serverbyserver_name->status == 'pending')
                   							 -
				                   			 @else
				                   			{{$serverbyserver_name->user->name}}
				                   			@endif
										</td>
										<td style="text-align: left">
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemserver"))
											<a href="{{route('serversviewdetail.show',['name'=>$name,'server_number'=>$serverbyserver_name->server_number])}}">
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-primary" ><i class="pe-7s-look btn-icon-wrapper" title="View"> </i></button>
											</a>
                                            @endif
                                            @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itemserver"))
                                             @if($serverbyserver_name->status == 'pending')
                                            <a href="{{route('server.edit',['server_number'=>$serverbyserver_name->server_number])}}">
                                            <button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" title="Edit"><i class="pe-7s-tools btn-icon-wrapper" > </i></button>
                                            </a>
                                            @endif
                                            @endif
                                            @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-itemserver"))
                                             @if($serverbyserver_name->status == 'pending')
                                            
                                            <button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deleteserver{{$serverbyserver_name->id}}"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                            <!-- ------------------Delete Server modal Start------------ -->

											<div class="modal fade deleteserver{{$serverbyserver_name->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR {{$serverbyserver_name->server_number}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('server.delete',['id'=>$serverbyserver_name->id])}}" >
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
											 
											<!-- ------------------Delete Server modal End------------ -->
                                            
                                            @endif
                                            @endif
										</td>
									</tr>
									@endforeach
								</tbody>							 

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL {{$serverbyserver_namesall->count()}}</p>
						{{ $serverbyserver_names->appends(Request::only('searchdetail'))->links() }}
						 
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
