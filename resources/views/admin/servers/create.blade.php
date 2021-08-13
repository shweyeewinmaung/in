@extends('admin.layouts.master')
@section('stylesheet')
 
<style type="text/css">
#loadingDiv{
  position:fixed;
  top:0px;
  right:0px;
  width:100%;
  height:100%;  
  background-image:url("{{ asset('/images/loading.gif') }}");
  /*background-image: url("{{ asset('assets/img/background.png') }}") */
  background-repeat:no-repeat;
  background-position:center;
  z-index:10000000;
   display: none;
  filter: alpha(opacity=40); /* For IE8 and earlier */
}
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
/*[class^="pe-7s-"], [class*=" pe-7s-"]
{
	color:#000;
}*/
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
.app-main 
{
	display: block;
}

</style>
 <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"> -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
 
	<div class="app-page-title">
		<div class="page-title-wrapper">
			<div class="page-title-heading">
				<div class="page-title-icon">
					<i class="metismenu pe-7s-server">
					</i>
				</div>
				<div>ITEM SERVER ENTRY
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

			</div>

		</div>

		<div class="row">

			<div class="col-md-12">

				<form class="search-bar" action="{{route('servers.search')}}" method="get">
					{{csrf_field()}}
					<div class="input-group">
						
						<select type="select"name="search" id="search" class="form-control js-example-tags custom-select" >
						<option value="">Click here for Store</option>		 
						@foreach($stores as $store1)
                          <option value="{{$store1->name}}"
						  @if($s == !null && $s == $store1->name)? selected ="selected" @endif
						>{{$store1->name}}</option>
							  @endforeach
					   </select><br>
					   	<a href="javascript:void();" type="submit"><button class="btn btn-light" type="submit"  style="float: right;" ><i class="metismenu pe-7s-search">
					</i></button></a>
<!-- 
						<div class="input-group-append" >
							<span class="input-group-text">
								<i class="pe-7s-search"></i>
								<a href="javascript:void();" type="submit">
									<i class="pe-7s-search"></i>
								</a>
							</span>
						</div> -->
					</div>
					<!--  <input type="text" class="form-control" placeholder="Enter keywords" name="search" value=""> -->

				</form>
			</div>
	    </div><br><br>
	    @if($categories == null)
	   <div class="row" style="height: 300px">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					

				</div>
			</div>

		</div>
		@elseif($categories->count() <= 0)
		<div class="row">
			<div class="col-md-12 pr-md-1">
				<div class="form-group">
					<label>There is no data Server in List</label>

				</div>
			</div>

		</div>
		@else

		<div class="row">

			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-body" style="padding-top:3%;background:#dbdddf">
					  <div class="form-group">
                       @foreach($categories as $category)
						<ul><h3>{{$category->title}}</h3>
							
								<?php
								$items1=DB::table('items')

								->join('categories','categories.id','=','items.category_id')
								->join('stores','stores.id','=','items.store_id')
								
							    ->groupBy('items.itemname_id')
								->where('category_id',$category->id)
								->where('stores.id',$store->id)
								->get();
												
								?>
								
								@foreach($items1 as $item1)
                               
								<?php $itemname1=DB::table('itemnames')->where('id',$item1->itemname_id)->first(); ?>
								<!-- <li></li> -->
								<?php

								$item_idscounts=DB::table('items')
								->join('categories','categories.id','=','items.category_id')
								->select('items.*')
								->where('items.category_id',$category->id)
								->where('itemname_id',$item1->itemname_id)
								->where('store_id',$store->id)
								->where('qty','=','1')
								->where('used_qty','=','0')
								->where('damage_qty','=','0')
								->where('transfer_qty','=','0')->get();
										
								$item_idscount=$item_idscounts->count();
								
								?>
								<div class="row" style="margin-left: 0px;">
					              <div class="col-md-2 pr-md-1">
					              	<div class="form-group">
					              		<li>{{$itemname1->name}}   
					              			<input type="hidden" class="form-control" placeholder="Itemname ID"    name="itemname_id" value="{{$itemname1->id}}"></li>
					              	</div>
					              </div>
					              <div class="col-md-3 pr-md-1" >
					              	<div class="form-group">
					              		<label>Avalible Items = {{$item_idscount}}</label>
					              		
					              		
					              	</div>
					              </div>
					              
					              
					              	@if(($item1->serial == 1 && $item1->mac == 1) && $item_idscount > '0')
					              	<div class="col-md-3 pr-md-1" >
					              	  <div class="form-group">	
					              	   @if(session('itemsserials')[0]['itemname_id'] == $item1->itemname_id)	
					              	  <input type="checkbox" id="choose_serial{{$item1->itemname_id}}" checked="checked" /> SERIAL & MAC
					              	   @else	
					              	  <input type="checkbox" id="choose_serial{{$item1->itemname_id}}" /> SERIAL & MAC
                                       @endif
                                          <button type="button" class="btn mr-2 mb-2 btn-info" data-toggle="modal" data-target=".serialregister{{$itemname1->id}}" title="CHOOSE SERIAL NUMBER" id="serial{{$item1->itemname_id}}" disabled=""><i class="metismenu pe-7s-anchor"></i></button>
                                          <!------------------------------------------->
                                         <div class="modal fade serialregister{{$itemname1->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-xl" >
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLongTitle">CHOOSE SERIAL NUMBER & MAC</h5>

													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form method="get" action="{{route('servers.saveserial',['itemname_id'=>$itemname1->id,'store_id'=>$store->id])}}" >
													<div class="modal-body">
          
														<input type="hidden" name="_token" value="{{csrf_token()}}">
														
														<div class="row">
															@if(session('itemsserials')) 
																 @if(session('itemsserials')[0]['itemname_id'] == $item1->itemname_id) 
																   @foreach($item_idscounts as $item_idscountss)
																    @foreach(session('itemsserials')[0]['same_id'] as $itemsserial1)
																   @if($itemsserial1 == $item_idscountss->id)
																     <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="serial{{$item_idscountss->id}}" value="{{$itemsserial1}}" name="serial[]" checked="" /><p style="background: #809393; padding:0px 5px;">SN-{{$item_idscountss->serial_number}}<br>MAC-{{$item_idscountss->mac}}</p>
																	   
																	 </div>
																   </div>
																   @endif
																   @endforeach
																   @foreach(session('itemsserials')[0]['diff_id'] as $itemsserial1)
																   @if($itemsserial1 == $item_idscountss->id)
																     <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="serial{{$item_idscountss->id}}" value="{{$itemsserial1}}" name="serial[]"  /><p style="background: #809393; padding:0px 5px;">SN-{{$item_idscountss->serial_number}}<br>MAC-{{$item_idscountss->mac}}</p>
																	 </div>
																   </div>
																   @endif
																   @endforeach 
																 @endforeach
                                                            @else
                                                            @if($item_idscounts->count() >0)
	                                                           @foreach($item_idscounts as $item_idscountss)
																  <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="serial{{$item_idscountss->id}}" value="{{$item_idscountss->id}}" name="serial[]"  />
																	    <p style="background: #809393; padding:0px 5px;">SN-{{$item_idscountss->serial_number}}<br>MAC-{{$item_idscountss->mac}}</p>
																	 </div>
																   </div>
																 @endforeach
                                                          @endif
                                                        @endif
                                                        @else
                                                          @if($item_idscounts->count() >0)
	                                                           @foreach($item_idscounts as $item_idscountss)
																  <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="serial{{$item_idscountss->id}}" value="{{$item_idscountss->id}}" name="serial[]"  /><p style="background: #809393; padding:0px 5px;">SN-{{$item_idscountss->serial_number}}<br>MAC-{{$item_idscountss->mac}}</p>
																	 </div>
																   </div>
																 @endforeach
                                                          @endif
                                                        @endif  
														
                                                          
														</div>

													</div><!----end modal-body--->

													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
														<button type="submit" class="btn btn-primary">CHOOSE SERIAL & MAC</button>
													</div>
											
                                              </form>
											</div>
										</div>
				                        </div>
                                        <!------------------------------------------->
                                       
					              	  </div>
					              	</div>
					              	@endif
					              	@if(($item1->serial == 1 && $item1->mac == 0) && $item_idscount > '0')
					              	<div class="col-md-2 pr-md-1" >
					              	  <div class="form-group">	
					              	   @if(session('itemsserials')[0]['itemname_id'] == $item1->itemname_id)	
					              	  <input type="checkbox" id="choose_serial{{$item1->itemname_id}}" checked="checked" /> SERIAL 
					              	   @else	
					              	  <input type="checkbox" id="choose_serial{{$item1->itemname_id}}" /> SERIAL 
                                       @endif
                                          <button type="button" class="btn mr-2 mb-2 btn-info" data-toggle="modal" data-target=".serialregister{{$itemname1->id}}" title="CHOOSE SERIAL NUMBER" id="serial{{$item1->itemname_id}}" disabled=""><i class="metismenu pe-7s-anchor"></i></button>
                                           <!------------------------------------------->
                                         <div class="modal fade serialregister{{$itemname1->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-xl" >
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLongTitle">CHOOSE SERIAL NUMBER</h5>

													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form method="get" action="{{route('servers.saveserial',['itemname_id'=>$itemname1->id,'store_id'=>$store->id])}}" >
													<div class="modal-body">
          
														<input type="hidden" name="_token" value="{{csrf_token()}}">
														
														<div class="row">
															@if(session('itemsserials')) 
																 @if(session('itemsserials')[0]['itemname_id'] == $item1->itemname_id) 
																   @foreach($item_idscounts as $item_idscountss)
																    @foreach(session('itemsserials')[0]['same_id'] as $itemsserial1)
																   @if($itemsserial1 == $item_idscountss->id)
																     <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="serial{{$item_idscountss->id}}" value="{{$itemsserial1}}" name="serial[]" checked="" /><p style="background: #809393; padding:0px 5px;">SN-{{$item_idscountss->serial_number}}</p>
																	   
																	 </div>
																   </div>
																   @endif
																   @endforeach
																   @foreach(session('itemsserials')[0]['diff_id'] as $itemsserial1)
																   @if($itemsserial1 == $item_idscountss->id)
																     <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="serial{{$item_idscountss->id}}" value="{{$itemsserial1}}" name="serial[]"  /><p style="background: #809393; padding:0px 5px;">SN-{{$item_idscountss->serial_number}} </p>
																	 </div>
																   </div>
																   @endif
																   @endforeach 
																 @endforeach
                                                            @else
                                                            @if($item_idscounts->count() >0)
	                                                           @foreach($item_idscounts as $item_idscountss)
																  <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="serial{{$item_idscountss->id}}" value="{{$item_idscountss->id}}" name="serial[]"  />
																	    <p style="background: #809393; padding:0px 5px;">SN-{{$item_idscountss->serial_number}} </p>
																	 </div>
																   </div>
																 @endforeach
                                                          @endif
                                                        @endif
                                                        @else
                                                          @if($item_idscounts->count() >0)
	                                                           @foreach($item_idscounts as $item_idscountss)
																  <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="serial{{$item_idscountss->id}}" value="{{$item_idscountss->id}}" name="serial[]"  /><p style="background: #809393; padding:0px 5px;">SN-{{$item_idscountss->serial_number}} </p>
																	 </div>
																   </div>
																 @endforeach
                                                          @endif
                                                        @endif  
														
                                                          
														</div>

													</div><!----end modal-body--->

													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
														<button type="submit" class="btn btn-primary">CHOOSE SERIAL</button>
													</div>
											
                                              </form>
											</div>
										</div>
				                        </div>
                                        <!------------------------------------------->
                                      </div>
                                  </div>
					              	@endif
					              	@if(($item1->serial == 0 && $item1->mac == 1) && $item_idscount > '0')
					              	 
                                    <div class="col-md-2 pr-md-1" >
					              	  <div class="form-group">
					              	  	@if(session('itemsmacs')[0]['itemname_id'] == $item1->itemname_id)	
					              	  	<input type="checkbox" id="choose_mac{{$item1->itemname_id}}" checked="checked" /> MAC
					              	  	 @else	
					              	  	 <input type="checkbox" id="choose_mac{{$item1->itemname_id}}" /> MAC
					              	  	 @endif
					              		<button type="button" class="btn mr-2 mb-2 btn-info" data-toggle="modal" data-target=".macregister{{$itemname1->id}}" title="CHOOSE MAC" id="mac{{$item1->itemname_id}}" disabled=""><i class="metismenu pe-7s-anchor"></i></button>
					              		 <!------------------------------------------->
                                         <div class="modal fade macregister{{$itemname1->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-xl" >
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLongTitle">CHOOSE MAC</h5>

													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form method="get" action="{{route('servers.savemac',['itemname_id'=>$itemname1->id,'store_id'=>$store->id])}}" >
													<div class="modal-body">
          
														<input type="hidden" name="_token" value="{{csrf_token()}}">
														
														<div class="row">
															@if(session('itemsmacs')) 
																 @if(session('itemsmacs')[0]['itemname_id'] == $item1->itemname_id) 
																   @foreach($item_idscounts as $item_idscountmac)

																    @foreach(session('itemsmacs')[0]['same_id'] as $itemsmac1)
																   @if($itemsmac1 == $item_idscountmac->id)
																     <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="mac{{$item_idscountmac->id}}" value="{{$itemsmac1}}" name="mac[]" checked="" />
																	    <p style="background: #809393; padding:0px 5px;">MAC-{{$item_idscountmac->mac}} </p>
																	  
																	 </div>
																   </div>
																   @endif
																   @endforeach
																   @foreach(session('itemsmacs')[0]['diff_id'] as $itemsmac1)
																   @if($itemsmac1 == $item_idscountmac->id)
																     <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="mac{{$item_idscountmac->id}}" value="{{$itemsmac1}}" name="mac[]"  /><p style="background: #809393; padding:0px 5px;">MAC-{{$item_idscountmac->mac}} </p>
																	   
																	 </div>
																   </div>
																   @endif
																   @endforeach 
																 @endforeach
                                                            @else
                                                            @if($item_idscounts->count() >0)
	                                                           @foreach($item_idscounts as $item_idscountmac)
																  <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="mac{{$item_idscountmac->id}}" value="{{$item_idscountmac->id}}" name="mac[]"  /><p style="background: #809393; padding:0px 5px;">MAC-{{$item_idscountmac->mac}} </p>
																	 </div>
																   </div>
																 @endforeach
                                                          @endif
                                                        @endif
                                                        @else
                                                          @if($item_idscounts->count() >0)
	                                                           @foreach($item_idscounts as $item_idscountmac)
																  <div class="col-md-2 pr-md-1">
																	<div class="form-group">
																	  
																	    <input type="checkbox" id="mac{{$item_idscountmac->id}}" value="{{$item_idscountmac->id}}" name="mac[]"  /><p style="background: #809393; padding:0px 5px;">MAC-{{$item_idscountmac->mac}} </p>
																	 </div>
																   </div>
																 @endforeach
                                                          @endif
                                                        @endif  
														
                                                          
														</div>

													</div><!----end modal-body--->

													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
														<button type="submit" class="btn btn-primary">CHOOSE MAC</button>
													</div>
											
                                              </form>
											</div>
										</div>
				                        </div>
                                        <!------------------------------------------->
					              	  </div>
					              	</div>
					              	@endif
					              <!-- 	<div class="col-md-1 pr-md-4" >
					              	  <div class="form-group">
					              		<label>No</label>
					              	  </div>
					                </div> -->
					              		
					              	 
					              <script type="text/javascript">
					              	document.getElementById('choose_mac{{$item1->itemname_id}}').onchange = function() {
                                    document.getElementById('mac{{$item1->itemname_id}}').disabled = !this.checked;
                                    // document.getElementById('choose_serial{{$item1->itemname_id}}').disabled = this.checked;                                   
                                   };
                                   </script>
                                   <script type="text/javascript">
                                   document.getElementById('choose_serial{{$item1->itemname_id}}').onchange = function() {
                                   document.getElementById('serial{{$item1->itemname_id}}').disabled = !this.checked;
    
                                   // document.getElementById('choose_mac{{$item1->itemname_id}}').disabled = this.checked;
                                      
                                  };
                                 </script>
					            @if(($item1->mac == 1 && $item1->serial ==1) && $item_idscount >= 0)
							        	
								@elseif(($item1->mac == 1 || $item1->serial == 1) && $item_idscount >= 0)
								@elseif($item_idscount == 0) 
								  	 
								@else
						            <div class="col-md-2 pr-md-1">
								      <div class="form-group">
								        <input type="number" id="count{{$item1->itemname_id}}" class="form-control" min="0"  name="count{{$item1->itemname_id}}" value="0">
								      </div>
			     		        	</div>
			     		        	 <div class="col-md-2 pr-md-1">
								      <div class="form-group">
								       <button id="{{$item1->itemname_id}}" class="get-cartcount{{$item1->itemname_id}} btn btn-light" data-button-action="add-to-cart"  onclick="myFunctionServerC{{$item1->itemname_id}}()" style="background:#abaeb3;" title="Add to List">
					              			<p id="{{$store->id}}" class="store{{$item1->itemname_id}}" style="display: none"></p>
					              			<i class="metismenu pe-7s-plus"></i>
					              		</button>
                                       <script>
 	                                 function myFunctionServerC{{$item1->itemname_id}}(){
                                       var id = document.getElementsByClassName("get-cartcount<?php echo $item1->itemname_id; ?>")[0].id;
                                       var store_id = document.getElementsByClassName("store<?php echo $item1->itemname_id; ?>")[0].id;
 	                                   var count = document.getElementById("count<?php echo $item1->itemname_id; ?>").value;

 	                                    var url1='{{route("additemsservercount",[":id",":store_id",":count"])}}';
 	                                    url1 = url1.replace(':id', id);
 	                                    url1 = url1.replace(':store_id', store_id);
 	                                    url1 = url1.replace(':count', count);
                                        
 	                                    $.ajax({
 		                                 type:'get',
                                         url: url1,
								         //data:{'id': id},
								         success: function(data) {    

                                         window.location.reload();
                                         //location.reload();

                                         },
                                         error:function(){ 
                                         alert('Insert not greater than avalible Item');
                                         swal({
									         title: 'Warning!',
									         text: "Insert not greater than avalible Item",
									         type: 'error',
									         // showCancelButton: true,
									         confirmButtonColor: '#3085d6',
									         // cancelButtonColor: '#d33',
									         confirmButtonText: 'OK'
									       })
                                          }
                                         }); 
 		                                }

                                      </script>
								      </div>
			     		        	</div>
			     		        	
								@endif
 
							    @if($item_idscount == 0)

							    @else   	
								@if(( $item1->serial == 1 && $item1->mac == 1 ) ||( $item1->serial == 1 && $item1->mac == 0 ) && $item_idscount > 0)
								  <div class="col-md-1 pr-md-1">
					               <div class="form-group"> 
					               
					              		<button id="{{$item1->itemname_id}}" class="get-cartserial{{$item1->itemname_id}} btn btn-light" data-button-action="add-to-cart"  onclick="myFunctionServerSandM{{$item1->itemname_id}}()" style="background:#abaeb3;" title="Add to List">
					              			<p id="{{$store->id}}" class="store{{$item1->itemname_id}}" style="display: none"></p>
					              			<i class="metismenu pe-7s-plus"></i>
					              		</button>
                                     
                                  </div>
                                 </div>
                                   <script>
 	   
 	                                   function myFunctionServerSandM{{$item1->itemname_id}}() {
                                        
                                       var id = document.getElementsByClassName("get-cartserial<?php echo $item1->itemname_id; ?>")[0].id;
                                      
 	                                   var store_id = document.getElementsByClassName("store<?php echo $item1->itemname_id; ?>")[0].id;
                                      
 	                                   var url1='{{route("servers.additemserverserial",[":id" ,":store_id"])}}';
 	                                   url1 = url1.replace(':id', id);
 	                                   url1 = url1.replace(':store_id', store_id);
 	              
                                         $.ajax({
 		                                 type:'get',
                                         url: url1,
								          success: function(data) {    

                                         window.location.reload();
                                         //location.reload();

                                         },
                                         error:function(){ 
                                         //alert('Please Check for Serial Number');
                                         swal({
									         title: 'Warning!',
									         text: "Insert not greater than avalible Item",
									         type: 'error',
									         // showCancelButton: true,
									         confirmButtonColor: '#3085d6',
									         // cancelButtonColor: '#d33',
									         confirmButtonText: 'OK'
									       })
                                          }
                                         }); 
 		                                }

                                      </script>
								@endif
								@endif
								@if($item_idscount == 0)
								 
							    @else  
								@if(( $item1->serial == 0 && $item1->mac == 1 ) && $item_idscount > 0)
								  <div class="col-md-1 pr-md-1">
					               <div class="form-group"> 
					                   
					              		<button id="{{$item1->itemname_id}}" class="get-cartmac{{$item1->itemname_id}} btn btn-light" data-button-action="add-to-cart"  onclick="myFunctionServerM{{$item1->itemname_id}}()" style="background:#abaeb3;" title="Add to List">
					              			<p id="{{$store->id}}" class="store{{$item1->itemname_id}}" style="display: none"></p>                                          
	 	
                                       <i class="metismenu pe-7s-plus"></i>
                                       
                                      </button>
                                     
                                  </div>
                                 </div>
                                   <script>
 	   
 	                                   function myFunctionServerM{{$item1->itemname_id}}() {
                                        
                                       var id = document.getElementsByClassName("get-cartmac<?php echo $item1->itemname_id; ?>")[0].id;
                                      
 	                                   var store_id = document.getElementsByClassName("store<?php echo $item1->itemname_id; ?>")[0].id;
                                      
 	                                   var url1='{{route("servers.additemservermac",[":id" ,":store_id"])}}';
 	                                   url1 = url1.replace(':id', id);
 	                                   url1 = url1.replace(':store_id', store_id);
 	              
                                        //alert(url1);
 		                                $.ajax({
 		                                 type:'get',
                                         url: url1,
								         //data:{'id': id},
								         success: function(data) {    

                                         window.location.reload();
                                         //location.reload();

                                         },
                                         error:function(){ 
                                         //alert('Please Check for MAC');
                                         swal({
									         title: 'Warning!',
									         text: "Insert not greater than avalible Item",
									         type: 'error',
									         // showCancelButton: true,
									         confirmButtonColor: '#3085d6',
									         // cancelButtonColor: '#d33',
									         confirmButtonText: 'OK'
									       })
                                          }
                                         }); 
 		                                }

                                      </script>
								@endif
     		        			@endif
                                  <!--   </div>
     		        			  </div> -->
     		        			</div><!----row end-->
                              @endforeach
                          </ul>
						<hr >
						@endforeach
					  </div>
						
					</div><!--------End card-body ---->

					
				</div>
			</div>

		</div><!-------------- End row ---->

		@endif 

		@if(session('itemsservers'))
		<div class="row">
			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-body" style="padding-top:3%;background:#000">
					  <div class="form-group">
					  	<div class="table-responsive">
							<table class="mb-0 table" style="color:white">
								<thead style="background: #000;">
								<tr>
								  <td colspan="6" style="border-top: 1px solid #000; color:white"><h4 align="center">View Selected Items For Server</h4></td>
							    </tr>
							    <tr>
							    	<td style="color:white;text-align: center;">FTTH</td>
							    	<td style="color:white;text-align: center;">ITEM NAME</td>
							    	<td style="color:white;text-align: center;">COUNT</td>
							    	<td style="color:white;text-align: center;">MAC</td>
							    	<td style="color:white;text-align: center;">SERIAL</td>
							    	<td style="color:white;text-align: center;">REMOVE</td>
							    </tr>
								</thead>
								<tbody>
									@foreach(session('itemsservers') as $k=>$val)
									 @if(is_array($val))
									  @if($store != null)
									   @if($val['store_id']==$store->id)

									  
									   
										<tr>
											<td>{{$val['categoryname']}}</td>
											<td>{{$val['itemname']}}</td>
											<td>{{$val['count']}}</td>
											<td>
												 @if($val['itemsserialbymac'] != "")
												@foreach($val['itemsserialbymac'] as $itemval)
												<?php $item=DB::table('items')->whereId($itemval)->first(); ?>
												{{$item->mac}}<br>
											    @endforeach
											    @endif
											</td>
											<td>
												@if($val['itemsserialbyserial'] != "")
												@foreach($val['itemsserialbyserial'] as $itemval)
												<?php $item=DB::table('items')->whereId($itemval)->first(); ?>
												 {{$item->serial_number}}<br>
											    @endforeach
											    @endif
											   
											</td>
											<td>
												<a id="{{$val['id']}}" class="remove-cart{{$val['id']}} btn" data-button-action="add-to-cart"   onclick="myFunctionServerRemove{{$val['id']}}()" style="padding: 0px 0px;color:white">
										       <i class="metismenu pe-7s-close" aria-hidden="true" style="color: red"></i> 	                                      
                                               </a>
                                               <script>
                                               function myFunctionServerRemove{{$val['id']}}() {
 	                                   	 var id = document.getElementsByClassName("remove-cart<?php echo $val['id']; ?>")[0].id;
                                        
 	                                    var url='{{route("remove_itemserversitems",[":id"])}}';
 	                                    url = url.replace(':id', id);
 	                                    
 		                                $.ajax({
 		                                 type:'get',
                                         url: url,
								         //data:{'id': id},
								         success: function(data) {
                                         location.reload();
                                         },
                                         error:function(){ 
                                        //alert('bb');
                                         swal({
									         title: 'Error!',
									         text: "Cannot be Remove item from List",
									         type: 'error',
									         // showCancelButton: true,
									         confirmButtonColor: '#3085d6',
									         // cancelButtonColor: '#d33',
									         confirmButtonText: 'OK'
									       })
                                          }
                                         }); 
 		                                }

                                      </script>
											</td>
										</tr>
									   @endif
									  @endif
									 @endif
									@endforeach
								</tbody>
							</table>
						</div><!--------- End table-responsive--->						
					  </div><!--------- End form-group--->
					</div><!--------- End card-body--->			
					
				</div><!--------- End main-card mb-3 card--->
			</div><!--------- End col-md-12--->
		</div><!--------- End row--->
		<!----------------------------------------->
		<div class="row">
			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-body">
						@if($store != null)
						<!---------------------------->
						<form method="post" action="{{route('itemsservercheckout',['store_id'=>$store->id])}}" enctype="multipart/form-data" id="formnameupload">
                           <input type="hidden" name="_token" value="{{csrf_token()}}">

							<div class="row">
								 <div class="col-md-6 pr-md-1">
								    <div class="form-group">
								        <label> SERVER NAME *</label>
								        <select type="select"name="servername_id" id="servername_id" class="form-control js-example-tags custom-select" >
										 <option value="">Click here for Server Name</option>
										 @foreach($servernames as  $servername)
										 <option  value="{{$servername->id}}">{{$servername->name}}</option>
										 @endforeach			
										</select>
								    </div>
								 </div>
								 <div class="col-md-6 pr-md-1">
								    <div class="form-group">
								        <label>  STATUS *</label>
								        <select type="select"name="status" class="form-control" >
										 <option value="pending">pending</option>	
										</select>
								    </div>
								 </div>
							</div>
							<div class="row">
								 <div class="col-md-6 pr-md-1">
								    <div class="form-group">
								        <label> STAFF NAME *</label>
								        <select type="select"name="staff_id" id="staff_id" class="form-control js-example-tags1 custom-select" >
										 <option value="">Click here for Staff Name</option>
										 @foreach($staffs as  $staff)
										 <option  value="{{$staff->id}}">{{$staff->name}}</option>
										 @endforeach			
										</select>
										
								    </div>
								 </div>
								 <div class="col-md-6 pr-md-1">
								    <div class="form-group">
								        <label>  POSITION</label>
								       <select name="positions" class="form-control" id="positions">
								        </select>

								    </div>
								 </div>
							</div>
								<div class="row">
								 <div class="col-md-12 pr-md-1">
								    <div class="form-group">
								        <label> SIGNATURE </label>
								         <br/>
								         <input type="text" id="txt"  name="signed" class="form-control" placeholder="Click here for Sign">
									          
									 
										</div>
								    </div>
								 </div>
								  <div class="row">
									<div class="col-md-12 pr-md-1">
									 <div class="form-group" align="center">
									  <button type="submit" class="btn btn-primary">SAVE</button>
									 </div>
									</div>
								</div>
								 
							</div>

						</form>
						<!---------------------------->
						@endif <!--endif $store ----->
					</div><!--------- End card-body--->
				</div><!--------- End main-card mb-3 card--->
			</div><!--------- End col-md-12--->
           <div id="loadingDiv"></div>
		</div><!--------- End row--->

         
		@endif
		  
 
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>     

	$(document).ready(function(){
		$("#alert").fadeOut(3000);

	});
	$('#formnameupload').submit(function() {
    $('#loadingDiv').show();
   
});
    $(".js-example-tags").select2({
	  tags: true
	});
	$(".js-example-tags1").select2({
	  tags: true
	});   
     
     $('select[name="staff_id"]').on('change', function(){
        var staff_id = $(this).val();
        var APP_URL = {!! json_encode(url('/')) !!};       
         
        if(staff_id) {

            $.ajax({
            	url: APP_URL+'/admin/ItemsServerEntry/getstaffdata/get/'+staff_id,
                type:"GET",
                dataType:"json",
                beforeSend: function(){

                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                     
                    $('select[name="positions"]').empty();

                    $.each(data, function(key, value){
                     $('select[name="positions"]').append('<option value="'+ key +'">' + value + '</option>');

                    });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="positions"]').empty();
        }

    });

 
   
</script>
<script>
  $(document).ready(function () {
            var sign = $('#txt').SignaturePad({
                allowToSign: true,
                img64: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
                 border: '1px solid #c7c8c9',
                width: '100%',
                height: '200px',
                // callback: function (data, action) {
                //     console.log(data);
                // }
            });

          

        })
</script>
       </div>

   </div>

</div>
<script>
//sketch lib
(function () {
    var __slice = [].slice;

    (function ($) {
        var Sketch;
        $.fn.sketch = function () {
            var args, key, sketch;
            key = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            if (this.length > 1) {
                $.error('Sketch.js can only be called on one element at a time.');
            }
            sketch = this.data('sketch');
            if (typeof key === 'string' && sketch) {
                if (sketch[key]) {
                    if (typeof sketch[key] === 'function') {
                        return sketch[key].apply(sketch, args);
                    } else if (args.length === 0) {
                        return sketch[key];
                    } else if (args.length === 1) {
                        return sketch[key] = args[0];
                    }
                } else {
                    return $.error('Sketch.js did not recognize the given command.');
                }
            } else if (sketch) {
                return sketch;
            } else {
                this.data('sketch', new Sketch(this.get(0), key));
                return this;
            }
        };
        Sketch = (function () {

            function Sketch(el, opts) {
                this.el = el;
                this.canvas = $(el);
                this.context = el.getContext('2d');
                this.options = $.extend({
                    toolLinks: true,
                    defaultTool: 'marker',
                    defaultColor: '#000000',
                    defaultSize: 2
                }, opts);
                this.painting = false;
                this.color = this.options.defaultColor;
                this.size = this.options.defaultSize;
                this.tool = this.options.defaultTool;
                this.actions = [];
                this.action = [];
                this.canvas.bind('click mousedown mouseup mousemove mouseleave mouseout touchstart touchmove touchend touchcancel', this.onEvent);
                if (this.options.toolLinks) {
                    $('body').delegate("a[href=\"#" + (this.canvas.attr('id')) + "\"]", 'click', function (e) {
                        var $canvas, $this, key, sketch, _i, _len, _ref;
                        $this = $(this);
                        $canvas = $($this.attr('href'));
                        sketch = $canvas.data('sketch');
                        _ref = ['color', 'size', 'tool'];
                        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                            key = _ref[_i];
                            if ($this.attr("data-" + key)) {
                                sketch.set(key, $(this).attr("data-" + key));
                            }
                        }
                        if ($(this).attr('data-download')) {
                            sketch.download($(this).attr('data-download'));
                        }
                        return false;
                    });
                }
            }

            Sketch.prototype.download = function (format) {
                var mime;
                format || (format = "png");
                if (format === "jpg") {
                    format = "jpeg";
                }
                mime = "image/" + format;
                return window.open(this.el.toDataURL(mime));
            };

            Sketch.prototype.set = function (key, value) {
                this[key] = value;
                return this.canvas.trigger("sketch.change" + key, value);
            };

            Sketch.prototype.startPainting = function () {
                this.painting = true;
                return this.action = {
                    tool: this.tool,
                    color: this.color,
                    size: parseFloat(this.size),
                    events: []
                };
            };


            Sketch.prototype.stopPainting = function () {
                if (this.action) {
                    this.actions.push(this.action);
                }
                this.painting = false;
                this.action = null;
                return this.redraw();
            };

            Sketch.prototype.onEvent = function (e) {
                if (e.originalEvent && e.originalEvent.targetTouches) {
                    e.pageX = e.originalEvent.targetTouches[0].pageX;
                    e.pageY = e.originalEvent.targetTouches[0].pageY;
                }
                $.sketch.tools[$(this).data('sketch').tool].onEvent.call($(this).data('sketch'), e);
                e.preventDefault();
                return false;
            };

            Sketch.prototype.redraw = function () {
                var sketch;
                //this.el.width = this.canvas.width();
                this.context = this.el.getContext('2d');
                sketch = this;
                $.each(this.actions, function () {
                    if (this.tool) {
                        return $.sketch.tools[this.tool].draw.call(sketch, this);
                    }
                });
                if (this.painting && this.action) {
                    return $.sketch.tools[this.action.tool].draw.call(sketch, this.action);
                }
            };

            return Sketch;

        })();
        $.sketch = {
            tools: {}
        };
        $.sketch.tools.marker = {
            onEvent: function (e) {
                switch (e.type) {
                    case 'mousedown':
                    case 'touchstart':
                        if (this.painting) {
                            this.stopPainting();
                        }
                        this.startPainting();
                        break;
                    case 'mouseup':
                        //return this.context.globalCompositeOperation = oldcomposite;
                    case 'mouseout':
                    case 'mouseleave':
                    case 'touchend':
                        //this.stopPainting();
                    case 'touchcancel':
                        this.stopPainting();
                }
                if (this.painting) {
                    this.action.events.push({
                        x: e.pageX - this.canvas.offset().left,
                        y: e.pageY - this.canvas.offset().top,
                        event: e.type
                    });
                    return this.redraw();
                }
            },
            draw: function (action) {
                var event, previous, _i, _len, _ref;
                this.context.lineJoin = "round";
                this.context.lineCap = "round";
                this.context.beginPath();
                this.context.moveTo(action.events[0].x, action.events[0].y);
                _ref = action.events;
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    event = _ref[_i];
                    this.context.lineTo(event.x, event.y);
                    previous = event;
                }
                this.context.strokeStyle = action.color;
                this.context.lineWidth = action.size;
                return this.context.stroke();
            }
        };
        return $.sketch.tools.eraser = {
            onEvent: function (e) {
                return $.sketch.tools.marker.onEvent.call(this, e);
            },
            draw: function (action) {
                var oldcomposite;
                oldcomposite = this.context.globalCompositeOperation;
                this.context.globalCompositeOperation = "destination-out";
                action.color = "rgba(0,0,0,1)";
                $.sketch.tools.marker.draw.call(this, action);
                return this.context.globalCompositeOperation = oldcomposite;
            }
        };
    })(jQuery);

}).call(this);


(function ($) {
    $.fn.SignaturePad = function (options) {

        //update the settings
        var settings = $.extend({
            allowToSign: true,
            img64: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
            border: '1px solid #c7c8c9',
            // width: '300px',
            // height: '150px',
            callback: function () {
                return true;
            }
        }, options);

        //control should be a textbox
        //loop all the controls
        var id = 0;

        //add a very big pad
        var big_pad = $('#signPadBig');
        var back_drop = $('#signPadBigBackDrop');
        var canvas = undefined;
        if (big_pad.length == 0) {

            back_drop = $('<div class="modal">')
            back_drop.css('position', 'fixed');
            back_drop.css('top', '0');
            back_drop.css('right', '0');
            back_drop.css('bottom', '0');
            back_drop.css('left', '0');
            back_drop.css('z-index', '9999 !important');
            back_drop.css('background-color', '#000');
            back_drop.css('display', 'none');
            back_drop.css('filter', 'alpha(opacity=50)');
            back_drop.css('opacity', '0.7');
            $('body').append(back_drop);

            big_pad = $('<div  class="modal">');
            big_pad.css('display', 'none');
          //  big_pad.css('position', 'fixed');
            big_pad.css('margin', '0 auto');
            big_pad.css('top', '0');
            big_pad.css('bottom', '0');
            big_pad.css('right', '0');
            big_pad.css('left', '0');
            big_pad.css('z-index', '9999 !important');
            big_pad.css('overflow', 'hidden');
            big_pad.css('outline', '0');
            big_pad.css('-webkit-overflow-scrolling', 'touch');

            big_pad.css('right', '0');
            big_pad.css('border', '1px solid #c8c8c8');
            big_pad.css('padding', '15px');
            big_pad.css('background-color', 'white');
            big_pad.css('margin-top', '15px');
            big_pad.css('width', '60%');
            big_pad.css('height', '80%');
            big_pad.css('border-radius', '10px');
            big_pad.attr('id', 'signPadBig');
            $('body').append(big_pad);

            var update_canvas_size = function () {
                var w = big_pad.width() //* 0.95;
                var h = big_pad.height() - 55;

                canvas.attr('width', w);
                canvas.attr('height', h);
            }


            canvas = $('<canvas>');
            canvas.css('display', 'block');
            canvas.css('margin', '0 auto');
            canvas.css('border', '1px solid #c8c8c8');
            canvas.css('border-radius', '10px');
            //canvas.css('width', '90%');
            //canvas.css('height', '80%');
            big_pad.append(canvas);

            update_canvas_size();
            $(window).on('resize', function () {
                update_canvas_size();
            });

            var clearCanvas = function () {
                canvas.sketch().action = null;
                canvas.sketch().actions = [];       // this line empties the actions. 
                var ctx = canvas[0].getContext("2d");
                ctx.clearRect(0, 0, canvas[0].width, canvas[0].height);
                return true
            }

            var _get_base64_value = function () {
                var text_control = $.data(big_pad[0], 'control');  //settings.control; // $('#' + big_pad.attr('id'));
                return $(text_control).val();
            }

            var copyCanvas = function () {
                //get data from bigger pad
                var sigData = canvas[0].toDataURL("image/png");

                var _img = new Image;
                _img.onload = resizeImage;
                _img.src = sigData;

                var targetWidth = canvas.width();
                var targetHeight = canvas.height();

                function resizeImage() {
                    var imageToDataUri = function (img, width, height) {

                        // create an off-screen canvas
                        var canvas = document.createElement('canvas'),
                            ctx = canvas.getContext('2d');

                        // set its dimension to target size
                        canvas.width = width;
                        canvas.height = height;

                        // draw source image into the off-screen canvas:
                        ctx.drawImage(img, 0, 0, width, height);

                        // encode image to data-uri with base64 version of compressed image
                        return canvas.toDataURL();
                    }

                    var newDataUri = imageToDataUri(this, targetWidth, targetHeight);
                    var control_img = $.data(big_pad[0], 'img');
                    if (control_img)
                        $(control_img).attr("src", newDataUri);

                    var text_control = $.data(big_pad[0], 'control');  //settings.control; // $('#' + big_pad.attr('id'));
                    if (text_control)
                        $(text_control).val(newDataUri);
                }
            }

            var buttons = [
                 {
                     title: 'Close',
                     callback: function () {
                         clearCanvas();
                         big_pad.slideToggle(function () {
                             back_drop.hide('fade');
                         });

                     }
                 },
                 {
                     title: 'Clear',
                     callback: function () {
                         clearCanvas();
                         if (settings.callback)
                             settings.callback(_get_base64_value(), 'clear');
                     }
                 },
                 {
                     title: 'Accept',
                     callback: function () {
                         copyCanvas();
                         clearCanvas();
                         big_pad.slideToggle(function () {
                             back_drop.hide('fade', function () {
                                 if (settings.callback)
                                     settings.callback(_get_base64_value(), 'accept');
                             });
                         });
                     }
                 }].forEach(function (e) {
                     var btn = $('<button>');
                     btn.attr('type', 'button');
                     btn.css('border', '1px solid #c8c8c8');
                     btn.css('background-color', 'white');
                     btn.css('padding', '10px');
                     btn.css('display', 'block');
                     btn.css('margin-top', '15px');
                     btn.css('margin-right', '5px');
                     btn.css('cursor', 'pointer');
                     btn.css('border-radius', '5px');
                     btn.css('float', 'right');
                     btn.css('height', '40px');
                     btn.text(e.title);
                     btn.on('click', function () {
                         e.callback(e.title);
                     })
                     big_pad.append(btn);

                 });

        }
        else {
            canvas = big_pad.find('canvas')[0];
        }

        //init the signpad
        if (canvas) {
            var sign1big = $(canvas).sketch({ defaultColor: "#000", defaultSize: 5 });
        }

        //for each control
        return this.each(function () {

            var control = $(this);
            control.hide();

            //get the control parent
            var wrapper = control.parent();
            var img = $('<img>');

            //style it
            img.css("cursor", "pointer");
            img.css("border", settings.border);
            img.css("height", settings.height);
            img.css("width", settings.width);
            img.css('border-radius', '5px')
            img.attr("src", settings.img64);

            if (typeof (wrapper) == 'object') {
                wrapper.append(img);
            }




            //init the big sign pad
            if (settings.allowToSign == true) {
                //click to the pad bigger
                img.on('click', function () {
                    //show the pad
                    back_drop.show();
                    big_pad.slideToggle();

                    //save control to use later
                    $.data(big_pad[0], 'img', img);
                    $.data(big_pad[0], 'control', control);

                    //settings.control = control;
                    //settings.img = img;
                });
            }
        });
    };


})(jQuery);


</script>
	
	@endsection
