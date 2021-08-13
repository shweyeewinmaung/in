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
				<div>ITEM LISTS FOR ITEM NAME {{$getitemname->name}} ({{$title->title}}) WITHOUT MAC & SERIAL
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
			 
			<a href="{{route('itemname.list',['title'=>$title->title])}}">
				<button type="button" class="btn mr-2 mb-2 btn-primary"><i class="metismenu pe-7s-back"></i> BACK TO ITEM NAME LIST</button>
			</a>
           

				<!-- </div> -->
				<!-- <div class="col-md-6"> -->

			
			</div>

		</div>
		 @if($items->count() <= 0 || $items == null)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no item in {{$store[0]['name']}} Store.</label>

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
										<td colspan="7"><h5 style="text-align: center;">  {{$store[0]['name']}} STORE</h5></td>
									</tr>
								</thead>
								<thead>
									<tr>
										<th>FTTH</th>
										<th>ITEM NAME</th>				                    
					                    <th>USED QTY</th>
					                    <th>TRANSFER QTY</th>
					                    <th>DAMAGE QTY</th>
					                    <th>AVALIBLE</th>
					                    <th>TOTAL</th>	
					                   
									</tr>
								</thead>
								<tbody>
									 @foreach($items as $key=>$item)
									 <tr> 
									 	<td>{{$title->title}}</td>
									 	<td>{{$getitemname->name}}</td>
									 	<td>{{ $usedqtyitemscount}}</td>
									 	<td>{{ $transferqtyitemscount}}</td>
									 	<td>{{ $damageqtyitemscount}}</td>
									 	<td>{{ $avalibleqtyitemscount}}</td>
									 	<td>{{ $totalqtyitemscount}}</td>
									
							    	 		 
									 </tr>
									 @endforeach
								</tbody>
							  

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL  </p>
						 
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
