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


.search-bar input{
	width: 200px!important;
}
.page-item.active .page-link {
	
	color: #ccc;
    background-color: #2f5a6f;
	border-color: #fff
}
.page-item.disabled .page-link {
    color: #000;  
    background-color: #496a77;
   
}

.mr-3, .mx-3 {
    margin-right: 0rem !important;
}
.font-icon-detail {
   /* text-align: center;*/
    padding: 10px 10px 20px 10px;
    border: none;
    border-radius: 3px;
   /* padding-left: 5px;
    padding-right: 5px;*/
    background: #525f71;
    margin-bottom: 20px;
    /*margin: 15px 0;
    min-height: 168px;*/
}
.font-icon-detail p {
    color: #e1dada !important;
    text-align: center;
    font-weight: 500;
    /*margin-top: 10px;*/
   /* padding: 0 10px;*/
    font-size: 16px;
}

.textlabel{
	float: left;
}
@media (min-width: 576px) {
  
    img.mr-3{
    	width: 135px;
    	height: 140px;
    	margin-left: 20%;
    }

}
 .middlebtn
    {
    	margin-left:15%;
    }
  .alerttext{
    color:red;
  }
  .image1
  {
  	width: 200px;
  	height: 200px;
  }

</style>

@endsection
@section('content')
 
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="metismenu fa fa-wifi">
					</i>
				</div>
				<div>FTTH LISTS
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
			   @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("create-category"))
				<button type="button" class="btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".agentregister"><i class="metismenu pe-7s-plus"></i> ADD NEW</button>
				@endif
                <!---------Start Modal Entry------------>
				<div class="modal fade agentregister" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">FTTH ENTRY</h5>

								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="{{route('category.submit')}}" enctype="multipart/form-data"  >
								<div class="modal-body">

									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-users"></i> NAME *</label>
												<input placeholder="Name" type="text" class="form-control" name="title">

												@if ($errors->has('title'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('title') }}
												</div>

												@endif
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-photo"></i> PICTURE *</label>
												 <input type="file" name="file" class="form-control">
						                          @if ($errors->has('file'))
						                           <label class="alerttext">{{ $errors->first('file') }}</label>
						                          @endif
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												 <input type="checkbox" name="mac" class="form-group">
                         						<span class="form-group">If there is MAC NUMBER</span>
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												 <input type="checkbox" name="serial" class="form-group">
                         						<span class="form-group">If there is SERIAL NUMBER</span>
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
                <!---------End Modal Entry------------>

				

				<!-- </div> -->
				<!-- <div class="col-md-6"> -->

				<form class="search-bar" style="float: right" action="{{route('category.search')}}" method="get">
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

		</div><br>
	   @if($categorylistsall->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data in Agent List</label>

				</div>
			</div>

		</div><!---------End row---------->
       @else

		 <div class="row">
		 	 @foreach($categorylists as $key=>$category)     
         
          	<div class="font-icon-list col-lg-3 col-md-4 col-sm-5 col-xs-6 col-xs-6 ">
                    <div class="font-icon-detail " style="">
            <!-- small card -->
          <!--   <div class="small-box bg-info"> -->
            	  <img class="align-self-start mr-3" src="{{asset('images/ftth/'.$category->file)}}" alt="{{$category->title}}">
                      <p>{{$category->title}}</p>
                  
                    <!------Edit Modal Start--------->
                    @if(Auth::user()->isSuper() || Auth::user()->hasPermission("edit-category"))
                    <button type="button" class="btn mr-2 mb-2 btn-success" data-toggle="modal" data-target=".edit{{$category->id}}" style="float: left" title="Edit"><i class="metismenu pe-7s-check"></i></button>
                    @endif
                     <!---------Start Modal Edit------------>
					<div class="modal fade edit{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLongTitle">FTTH UPDATE FOR {{$category->title}}</h5>

									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form method="post" action="{{route('category.edit.submit',['id'=>$category->id])}}" enctype="multipart/form-data"  >
									<div class="modal-body">

										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<div class="row">
											<div class="col-md-12 pr-md-1">
												<div class="form-group">
													<label><i class="metismenu pe-7s-users"></i> NAME *</label>
													<input placeholder="Name" type="text" class="form-control" name="title" value="{{$category->title}}">

													@if ($errors->has('title'))
													<div class="alert alert-danger fade show" role="alert" >
														{{ $errors->first('title') }}
													</div>

													@endif
												</div>
											</div>

										</div>

										<div class="row">
											<div class="col-md-6 pr-md-1">
												<div class="form-group">
													<label><i class="metismenu pe-7s-photo"></i> PICTURE *</label>
													 <input type="file" name="file" class="form-control">
							                          @if ($errors->has('file'))
							                           <label class="alerttext">{{ $errors->first('file') }}</label>
							                          @endif
							                          <br>

							                           @if($category->mac == '1')
								                         <input type="checkbox" name="mac" class="form-group" checked="">
								                         @else
								                         <input type="checkbox" name="mac" class="form-group">
								                       @endif						                     
	                         						<span class="form-group">If there is MAC NUMBER</span>
	                         						<br>
	                         						 @if($category->serial == '1')
								                         <input type="checkbox" name="serial" class="form-group" checked="">
								                         @else
								                         <input type="checkbox" name="serial" class="form-group">
								                       @endif
	                         						 
	                         						<span class="form-group">If there is SERIAL NUMBER</span>
												</div>
											</div>

											<div class="col-md-6 pr-md-1">
												<div class="form-group">
													<img class="image1" src="{{asset('/images/ftth/'.$category->file)}}" alt="">
							                          @if ($errors->has('file'))
							                      <label class="alerttext">{{ $errors->first('file') }}</label>
							                    @endif
												</div>
											</div>

										</div>


									</div><!----end modal-body--->

									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
										<button type="submit" class="btn btn-primary">UPDATE</button>
									</div>
								</form>

							</div>
						</div>
					</div>
	                <!---------End Modal Edit------------>
                    <!------Edit Modal End--------->
                  
                      <a href="{{route('itemname.list', $category->title)}}"><button type="submit" class="btn btn-light middlebtn" style="" title="View"><i class="fa fa-eye"></i></button></a>
                      <!------View Modal Start--------->
                      <!------View Modal End--------->
                      
                       <!------Start Delete--------->
                      @if(Auth::user()->isSuper() || Auth::user()->hasPermission("delete-category"))
                      <button type="submit" class="btn btn-danger" style="float: right"  title="Delete" data-toggle="modal" data-target=".deletecategory{{$category->id}}"><i class="metismenu pe-7s-close-circle"></i></button>
                      @endif
                      <!------Start Modal Delete--------->
                      <div class="modal fade deletecategory{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLongTitle">FTTH DELETE FOR {{$category->title}}</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form method="post" action="{{route('category.delete',['id'=>$category->id])}}" >
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
                    <!------End Modal Delete--------->
                    <!------Start Delete--------->                   
             
            </div>
          </div> <!-- ./col -->
          @endforeach
         
        </div><!-- /.row -->

         <div class="card-body" >
			<p style="float: right;">TOTAL {{$categorylistsall->count()}}</p>
				{{ $categorylists->appends(Request::only('search'))->links() }}
		  </div>
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
