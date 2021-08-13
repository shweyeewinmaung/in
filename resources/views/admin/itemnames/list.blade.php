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
       width: 800px;
      height: 500px
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
.list-group-item
{
	background: #bcc8d0;
}


</style>

@endsection
@section('content')
 
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="metismenu pe-7s-note2">
					</i>
				</div>
				<div>ITEMNAME LISTS FOR {{$title->title}}
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
			<a href="{{route('category.list')}}">
			  <button type="button" class="btn mr-2 mb-2 btn-primary" ><i class="metismenu pe-7s-back"></i> BACK TO FTTH</button>
			</a>
			@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("create-itemname"))
				<button type="button" class="btn mr-2 mb-2 btn-primary" data-toggle="modal" data-target=".itemnameregister"><i class="metismenu pe-7s-plus"></i> ADD NEW</button>
            @endif


				<div class="modal fade itemnameregister" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">ITEMNAME ENTRY</h5>

								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" action="{{route('itemname.store.submit')}}" enctype="multipart/form-data">
								<div class="modal-body">

									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<input type="hidden" name="category_id" value="{{$title->id}}">
									<div class="row">
										<div class="col-md-4 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-users"></i> NAME *</label>
												<input placeholder="Name" type="text" class="form-control" name="name">

												@if ($errors->has('name'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('name') }}
												</div>

												@endif
											</div>
										</div>

										<div class="col-md-4 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-id"></i> ACCOUNT CODE *</label>
												<input placeholder="ACCOUNT CODE" type="text" class="form-control" name="account_code">

												@if ($errors->has('account_code'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('account_code') }}
												</div>

												@endif
											</div>
										</div>

										<div class="col-md-4 pr-md-1">
											<div class="form-group">
												<label><i class="fa fa-wifi"></i> Category Name</label>
												<input type="text" class="form-control" name="category" value="{{$title->title}}"  disabled="">
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-md-12 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-photo"></i> PICTURE *</label>
												 <input type="file" name="itemname_file" class="form-control">
								                 @if ($errors->has('itemname_file'))
								                           <label class="alerttext">{{ $errors->first('itemname_file') }}</label>
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

				<!-- <form class="search-bar" style="float: right" action=" " method="get">
					{{csrf_field()}}
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Enter keywords" name="search" value=" " style="border:1px solid#2b3c51;">

						<div class="input-group-append" style="border:1px solid#2b3c51;display: none;">
							<span class="input-group-text">
								<i class="pe-7s-search"></i>
								<a href="javascript:void();" type="submit" style="display: none">
									<i class="pe-7s-search"></i>
								</a>
							</span>
						</div>
					</div>
					 

				 </form> -->
			</div>

		</div>
		@if($itemnamelistsall->count() <= 0) 
		 <div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group"><br>
					<label>There is no data in Item Name List</label>

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
										<th>NAME</th>
										<th>CODE</th>
										<th>FTTH</th>

										<th>@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itemname") ||  \Auth::user()->hasPermission("delete-itemname"))
									   ACTION
									 @endif</th>

									</tr>
								</thead>
									<tbody>
									@foreach($itemnamelists as $key=>$itemnamelist)
									<tr>
										<td>{{$itemnamelists->firstItem() +$key}}</td>
										<td>
									@if($itemnamelist->itemname_file)
			                           <button type="button" class="btn" data-toggle="modal" data-target="#myimage{{$itemnamelist->itemname_file}}"> <img class="image" src="{{asset('images/ftth/itemnames/'.$itemnamelist->itemname_file)}}" alt=""></button>
			                       @else
			                           <button type="button" class="btn" data-toggle="modal" data-target="#myimage{{$itemnamelist->itemname_file}}"> <img src="//placehold.it/1000x600" class="img-responsive image"></button>
			                          
			                       @endif                       
                            <!-- <div id="myimage{{$itemnamelist->itemname_file}}" class="modal fade" tabindex="-1" role="dialog">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <img src="{{asset('images/ftth/itemnames/'.$itemnamelist->itemname_file)}}" class="image2">
                                    </div>
                                </div>
                              </div>
                            </div> -->
										</td>
										<td>{{$itemnamelist->name}}</td>
										<td>{{$itemnamelist->account_code}}</td>
										<td>{{$title->title}}</td>
										<td>
											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("edit-itemname"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-success" data-toggle="modal" data-target=".edititemname{{$itemnamelist->id}}"><i class="pe-7s-tools btn-icon-wrapper"> </i></button>

											<!-- ------------------Edit Agent modal Start------------ -->

											<div class="modal fade edititemname{{$itemnamelist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">ITEMNAME UPDATE</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('itemname.edit.submit',['id'=>$itemnamelist->id])}}" enctype="multipart/form-data">
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">
																 <input type="hidden" name="category_id" value="{{$title->id}}">
																<div class="row">
										<div class="col-md-4 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-users"></i> NAME *</label>
												<input placeholder="Name" type="text" class="form-control" name="name" value="{{$itemnamelist->name}}">

												@if ($errors->has('name'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('name') }}
												</div>

												@endif
											</div>
										</div>

										<div class="col-md-4 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-id"></i> ACCOUNT CODE *</label>
												<input placeholder="ACCOUNT CODE" type="text" class="form-control" name="account_code" value="{{$itemnamelist->account_code}}">

												@if ($errors->has('account_code'))
												<div class="alert alert-danger fade show" role="alert" >
													{{ $errors->first('account_code') }}
												</div>

												@endif
											</div>
										</div>

										<div class="col-md-4 pr-md-1">
											<div class="form-group">
												<label><i class="fa fa-wifi"></i> Category Name</label>
												<input type="text" class="form-control" name="category" value="{{$title->title}}"  disabled="">
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-md-8 pr-md-1">
											<div class="form-group">
												<label><i class="metismenu pe-7s-photo"></i> PICTURE *</label>
												 <input type="file" name="itemname_file" class="form-control">
								                 @if ($errors->has('itemname_file'))
								                           <label class="alerttext">{{ $errors->first('itemname_file') }}</label>
								                 @endif
											</div>
										</div>

										<div class="col-md-4 pr-md-1">
											<div class="form-group">
												 <img class="image1" src="{{asset('images/ftth/itemnames/'.$itemnamelist->itemname_file)}}" alt="{{$itemnamelist->name}}">				
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

											@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-agent"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deleteitemname{{$itemnamelist->id}}"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>

											<!-- ------------------Delete Agent modal Start------------ -->

											<div class="modal fade deleteitemname{{$itemnamelist->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR {{$itemnamelist->name}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="post" action="{{route('itemname.delete',['id'=>$itemnamelist->id])}}" >
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
								</thead>
								<tbody>
									 
								</tbody>

							</table>
						</div>
					</div><!--------End card-body ---->
					<div class="card-body" >
						<p style="float: right;">TOTAL {{$itemnamelistsall->count()}} </p>
						 {{ $itemnamelists->appends(Request::only('search'))->links() }}
					</div>
				</div>
			</div>

		</div><!-------------- End row ---->

			<div class="row">

			<div class="col-md-12">
				<div class="main-card mb-3 card" style="background: #495259;">
					<div class="card-body" style="padding: 0px">
						<div class="table-responsive">
							<table class="mb-0 table">
                  
                   <tbody>
                    @foreach($itembycat_groupsitemnames as $key=>$itembycat_groupsitemname)
                    <?php   $itemnamegroups= DB::table('itemnames')->whereId($key)->get(); ?>
                      
                    <tr>
                      @foreach($itemnamegroups as $itemnamegroup)
                        <td style="color:#fff;">{{$itemnamegroup->name}}</td>
                      @endforeach
                       @foreach($itembycat_groupsstores as $k=>$itembycat_groupsstore)
                        <?php   $storegroups= DB::table('stores')->whereId($k)->get();?>
                          @foreach($storegroups as $storegroup)
                              <td>
                              	@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-viewitem"))
                              	@if($title->mac == 1 && $title->serial == 1)
                             	 <a href="{{route('item.show',[$title->title,$key,$storegroup->name])}}"><h6 style="text-align: center;">{{ $storegroup->name }}</h6></a>
                              	@else
                                 <a href="{{route('item.show1',[$title->title,$key,$storegroup->name])}}"><h6 style="text-align: center;">{{ $storegroup->name }}</h6></a>
                              	@endif
                              	
                                @else
                                 <h6 style="text-align: center;">{{ $storegroup->name }}</h6> 
								@endif
                                <?php   
                                $itemsgroupsbystorestotalqty= DB::table('items')
                                ->where('store_id','=',$storegroup->id)
                                ->where('itemname_id','=',$key)
                                ->where('qty','=',1)
                                ->count();
                                $itemsgroupsbystoresdamage_qty= DB::table('items')
                                ->where('store_id','=',$storegroup->id)
                                ->where('itemname_id','=',$key)
                                ->where('damage_qty','=',1)
                                ->count();
                                $itemsgroupsbystoresused_qty= DB::table('items')
                                ->where('store_id','=',$storegroup->id)
                                ->where('itemname_id','=',$key)
                                ->where('used_qty','=',1)
                                ->count();
                                 $itemsgroupsbystorestransfer_qty= DB::table('items')
                                ->where('store_id','=',$storegroup->id)
                                ->where('itemname_id','=',$key)
                                ->where('transfer_qty','=',1)
                                ->count();
                                $itemsgroupsbystoresava_qty=$itemsgroupsbystorestotalqty - ($itemsgroupsbystoresdamage_qty + $itemsgroupsbystoresused_qty + $itemsgroupsbystorestransfer_qty);
                                ?>
                                <div class="card" >
                                    <ul class="list-group list-group-flush">
                                      <li class="list-group-item">Avalible  = {{ $itemsgroupsbystoresava_qty }}</li>
                                      <li class="list-group-item">Used  = {{ $itemsgroupsbystoresused_qty }}</li>
                                      <li class="list-group-item">Transfer  = {{ $itemsgroupsbystorestransfer_qty }}</li>
                                      <li class="list-group-item">Damage  = {{ $itemsgroupsbystoresdamage_qty }}</li>
                                      <li class="list-group-item">Total = {{ $itemsgroupsbystorestotalqty }}</li>
                                    </ul>
                                  </div>
                              
                              </td>

                              @endforeach
                          @endforeach

                         
                         
                    <!--  <td>Account Code</td>                     
                     <td>Serial Number/MAC</td>                     
                     <td>QTY</td>
                     <td>Label Code</td>    -->              
                     </tr>
                   @endforeach
                  
                  
                  </tbody>
             </table>
						</div><!------------end table-responsive---->
					</div>
					 <div class="card-body" >
				      <p style="float: right;">TOTAL {{$itembycat_groupsitemnames->count()}} </p> 
				      {{$namesfetfromitems->links()}} 
				      </div>
				</div>
			</div>
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
