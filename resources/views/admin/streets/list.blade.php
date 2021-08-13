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
				<div>ITEM STREET LISTS
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("create-itemstreet"))
			 <a href="{{route('street.create')}}">
				<button type="button" class="btn mr-2 mb-2 btn-primary"><i class="metismenu pe-7s-plus"></i> ADD NEW</button>
			</a>
            @endif

				<!-- </div> -->
				<!-- <div class="col-md-6"> -->

				<form class="search-bar" style="float: right" action="{{route('streetslist.search')}}" method="get">
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
		@if($streetnameslistsall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in Street List</label>

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
										<th>LATITUDE</th>
										<th>LONGITUDE</th>
										<th>STREET</th>
										<th>TOWNSHIP</th>
										<th>CITY</th>
										 

										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemstreet") ||  \Auth::user()->hasPermission("view-itemstreet"))
									   VIEW
									 @endif</th>

									</tr>
								</thead>
								<tbody>
									@foreach($streetnameslists as $key=>$streetnameslist)
									<tr>
										<td>{{$streetnameslists->firstItem() +$key}}</td>
										<td>	<a href="{{route('streetview.show',['name'=>$streetnameslist->name])}}" style="color: #000">{{$streetnameslist->name}}</a></td>
										<td>{{$streetnameslist->lat}}</td>
										<td>{{$streetnameslist->lng}}</td>
										<td>{{$streetnameslist->street}}</td>
										<td>{{$streetnameslist->township}}</td>
										<td>{{$streetnameslist->city}}</td>
										 
										<td>
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemstreet"))
											<a href="{{route('streetview.show',['name'=>$streetnameslist->name])}}">
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-primary" ><i class="pe-7s-look btn-icon-wrapper" title="Street View"> </i></button>
											</a>
                                            @endif
                                            @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemstreet"))
											<a href="{{route('streetsview.alldetail',['name'=>$streetnameslist->name])}}">
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-info" ><i class="pe-7s-look btn-icon-wrapper" title="Street View Detail"> </i></button>
											</a>
                                            @endif
											
										</td>
									</tr>
									@endforeach
								</tbody>

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL {{$streetnameslistsall->count()}}</p>
						{{ $streetnameslists->appends(Request::only('search'))->links() }}
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
