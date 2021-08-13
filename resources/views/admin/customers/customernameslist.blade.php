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
					<i class="metismenu pe-7s-users">
					</i>
				</div>
				<div>CUSTOMER NAME LISTS
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
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemcustomer"))
			<a href="{{ redirect()->getUrlGenerator()->previous() }}">
				<button type="button" class="btn mr-2 mb-2 btn-primary" ><i class="metismenu pe-7s-angle-left-circle"></i> BACK TO LIST</button>
			</a>
            @endif		

		 
		 
		<form class="search-bar" style="float: right" action="{{route('getsearchlistcustomername.search',['storename'=>$storename])}}" method="get">
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
		@if($customernamelistsall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in Customer Name List</label>

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
										<th>EMAIL</th>
										<th>PHONE</th>
										<th>LATITUDE</th>
										<th>LONGITUDE</th>									 
										<th>TOWNSHIP</th>
										<th>CITY</th>
										<th>ADDRESS</th>

									</tr>
								</thead>
								<tbody>
					            @foreach($customernameslists as $key=>$customernamelist)
									<tr>
										<td>{{$customernameslists->firstItem() +$key}}</td>
										<td>  <a href="{{route('getstoreandcustomername.get',['storename'=>$storename,'customernamecode'=>$customernamelist->code])}}">{{ $customernamelist->name }}</a></td>
										<td><a href="{{route('getstoreandcustomername.get',['storename'=>$storename,'customernamecode'=>$customernamelist->code])}}">{{ $customernamelist->code }}</a></td>
										<td>{{$customernamelist->email}}</td>
										<td>{{$customernamelist->phone}}</td>
										<td>{{$customernamelist->lat}}</td>
										<td>{{$customernamelist->lng}}</td>
										<td>{{$customernamelist->township}}</td>
										<td>{{$customernamelist->city}}</td>
										<td>{{$customernamelist->address}}</td>
									
									</tr>
									@endforeach
								</tbody>

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL {{$customernamelistsall->count()}}</p>
						{{ $customernameslists->appends(Request::only('search'))->links() }}
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
