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
@media (min-width: 576px) {
 
  img.image{
      width: 150px;
      height: 150px
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
@media (min-width: 576px) {
  .btnstyle
	{
		margin-top:45%;
		
	}
}
@media (max-width: 576px) {
  .btnstyle
	{
		margin-top:1%;
		
	}
}
</style>
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
                                    <div>ITEM BUY UPDATE FOR 
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

					        @foreach($errors->all() as $error)
					         <div class="alert alert-danger fade show" role="alert" >{{$error}} <br>
					         </div>		        
					             
					          
					        @endforeach
					       </div>

                        	<div class="col-md-12">
                        		<a href="{{route('itemsbuy.list')}}">
                        	    <button type="button" class="btn mr-2 mb-2 btn-primary"><i class="metismenu pe-7s-angle-left-circle"></i> BACK TO LIST</button>
                        	    </a>
                        	<!-- </div> -->
                        	<!-- <div class="col-md-6"> -->                       		
						    
                        	</div>
                           </div><br><br>
                           	 <div class="row">

                        	 <div class="col-md-12">
                                 <div class="main-card mb-3 card">
                                    <div class="card-body" style="padding: 0px">
                                    	 <div class="row">
								                     <div class="col-md-3 pr-md-1">
								                       <div class="form-group">
								                        <label><i class="fa fa-wifi"></i> FTTH</label>
														
														<select type="select"name="category_id" id="category_id" class="form-control js-example-tags custom-select" >
														<option>Click here for FTTH</option>							
														@foreach($categories as  $category)
														  <option  value="{{$category->id}}">{{$category->title}}</option>@endforeach						
														</select>
								                       </div>
								                     </div>
								                     <div class="col-md-2 px-md-1">
								                       <div class="form-group">
								                         <label><i class="metismenu pe-7s-note2"></i> ITEM NAME</label>
														 <select name="names" class="form-control" id="names">
								                          </select>
								                       </div>
								                     </div>

								                    
								                     <div class="col-md-1 pr-md-1">
								                       <div class="form-group">
								                        <label><i class="metismenu pe-7s-settings"></i> QTY</label>
														
														<input type="number" class="form-control " min="0"  name="qty" value="0" id="qty" > 
								                       </div>
								                     </div>

								                     <div class="col-md-2 pr-md-1">
								                       <div class="form-group">
								                        <label><i class="metismenu pe-7s-cash"></i> AMOUNT</label>
														 <input type="number" class="form-control " min="0"  name="amount" value="0" id="amount"> 
								                       </div>
								                     </div>

								                      <div class="col-md-1 px-md-1">
								                       <div class="form-group">
								                         <label>IF MAC</label>
                                                        <input type="checkbox" id="yourBox" class="form-control "  />
                                                       
												
								                       </div>
								                     </div>

								                      <div class="col-md-2 px-md-1">
								                       <div class="form-group">
								                         <label><i class="metismenu pe-7s-id"></i> MAC</label>
                                                       
                                                        <input type="text" id="yourText" disabled name="mac" class="form-control "  />
                                                        
								                       </div>
								                     </div>
								                       <div class="col-md-1 pr-md-1">
					 <div class="form-group">
									 	
				<button  class="get-cart btn btn-primary btnstyle"  onclick="myFunction()" style="color:white"><i class="metismenu pe-7s-plus"></i></button>
					 <script>
					 	function myFunction(){   

                        var category_id = document.getElementById("category_id").value;
                        var names = document.getElementById("names").value;
                        var amount = document.getElementById("amount").value;
                        var mac = document.getElementById("yourText").value;
                        var qty = document.getElementById("qty").value;
                        if (mac=='') 
                        {
                         mac="null";
                        }
                         //alert(mac);
                         var url1='{{route("additemsbuyupdate",[":itemname_id",":category_id",":qty",":amount",":mac"])}}';
                      
 	                    url1 = url1.replace(':itemname_id', names);
 	                    url1 = url1.replace(':category_id', category_id);
 	                    url1 = url1.replace(':qty', qty);
 	                    url1 = url1.replace(':amount', amount);
 	                    url1 = url1.replace(':mac', mac);
                      
 	                    $.ajax({ 		                  
                            type: 'GET', 
                            url: url1,                             
							success: function(data) {  

							// window.location.reload();
                             location.reload();
                            },
                            error:function(){
                           
                           alert('ItemName is already selected');
         //                    swal({
								 // title: 'Warning!',
								 // text: "Cannot Save to Selected Item",
								 // type: 'error',
								 // // showCancelButton: true,
								 // confirmButtonColor: '#3085d6',
								 // // cancelButtonColor: '#d33',
								 // confirmButtonText: 'OK'
								 // })
                               }
                              }); 

                        }
                     </script>

                       


					 </div>
				    </div>

			</div>

										      
                                    </div>
                                </div>
                            </div>
                        	
                        </div> 

                        <!------------------------>


                       
                        <div class="row">

                        	 <div class="col-md-12">
                                 <div class="main-card mb-3 card">
                                    <div class="card-body" style="padding: 0px">
                                    	 <div class="row">
                                    	 	<div class="col-md-12">
			<!-- <div class="col-md-10 offset-md-1">	 -->
				 @if(session('itemsbuy'))
				<div class="table-responsive">
     		        <table class="table align-items-center table-flush table-border" >
						<thead>
							<tr>
								<td colspan="6"><h4 align="center">View Selected ItemsBuy</h4></td>
							</tr>
							<tr>
								<th>FTTH</th>
								<th>ITEM NAME</th>
								<th>CODE</th>
								<th>QTY</th>
								<th>AMOUNT</th>
								
								<th>REMOVE</th>
							</tr>
						</thead>
						<tbody>
							@foreach(session('itemsbuy') as $k=>$val)
							@if(is_array($val))

							<tr>
								
								<td>{{$val['categoryname']}}</td>
								<td>{{$val['itemname']}}</td>
								<td>{{$val['mac']}}</td>								
								<td>{{$val['qty']}}</td>
								<td>{{$val['amount']}}</td>
								
								<td>
									<a id="{{$val['id']}}" class="remove-cart{{$val['id']}} btn" data-button-action="add-to-cart"   onclick="myFunctionRemove{{$val['id']}}()" style="padding: 0px 0px;color:white">
										<i class="metismenu pe-7s-close-circle" aria-hidden="true" style="color: red"></i> 	                                      
                                      </a>
                                      <script>
 	   
 	                                   function myFunctionRemove{{$val['id']}}() {
 	                                   	 var id = document.getElementsByClassName("remove-cart<?php echo $val['id']; ?>")[0].id;
                                        
 	                                    var url='{{route("remove_itemsbuyupdate",[":id"])}}';
 	                                    url = url.replace(':id', id);
 	                                    
 		                                $.ajax({
 		                                 type:'get',
                                         url: url,
								         //data:{'id': id},
								         success: function(data) {
                                         location.reload();
                                         },
                                         error:function(){ 
                                        alert('bb');
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
							@endforeach
						</tbody>
					</table>
				</div><!-------End table-responsive------------>
				 @endif
				 <!-------------------------------------------------------------->
                 @if(session('itemsbuydb'))  
                 <div class="table-responsive">
     		        <table class="table align-items-center table-flush table-border"  >
						<thead>
							<tr style="background: #000;color: #fff;">
								<td colspan="6"><h4 align="center">View Selected Stored ItemsBuy</h4></td>
							</tr>
							<tr style="background: #000;color: #fff;">
								<th>FTTH</th>
								<th>Item Name</th>
								<th>MAC</th>
								<th>Qty</th>
								<th>Amount</th>
								<th>Remove</th>
							</tr>
						</thead>
						<tbody>
								@foreach(session('itemsbuydb') as $k=>$val)
								@if(is_array($val))
								
								<tr style="background: #000;color: #fff;">
									<td>{{$val['categoryname']}}</td>
									<td>{{$val['itemname']}}</td>
									<td>{{$val['mac']}}</td>								
									<td>{{$val['qty']}}</td>
									<td>{{$val['amount']}}</td>
									<td>
									<!-- 	<a  href="{{route('remove_olditemsbuy',['itemname_id'=>$val['id'],'voucher_code'=>$voucher->voucher_code])}}" style="padding: 0px 0px;color:white">
										<i class="metismenu pe-7s-close-circle" aria-hidden="true" style="color: red"></i>  -->

										@if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("delete-itembuy"))
											<button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger" data-toggle="modal" data-target=".deleteitem{{$val['id']}}"><i class="metismenu pe-7s-close-circle" aria-hidden="true" style="color: red"></i></button>

											<!-- ------------------Delete ITEM modal Start------------ -->

											<div class="modal fade deleteitem{{$val['id']}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">TO DELETE FOR ITEM NAME {{$val['itemname']}}</h5>

															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<form method="get" action="{{route('remove_olditemsbuy',['itemname_id'=>$val['id'],'voucher_code'=>$voucher->voucher_code])}}" >
															<div class="modal-body">

																<input type="hidden" name="_token" value="{{csrf_token()}}">

																<div class="row">
																	<div class="col-md-12 pr-md-1">
																		<div class="form-group" align="center">
																			<label style="color: #000;" ><i class="metismenu pe-7s-close-circle"></i> Can not be Undo!!! Are you sure to delete?</label>
																			

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
											<!-- ------------------Delete ITEM modal End------------ -->	                                      
                                     
									</td>
								</tr>
								
								@endif
								@endforeach
							</tbody>
					</table>
				</div><!-------End table-responsive------------>
                 @endif    
                <!-------------------------------------------------------------->
                @if(session('itemsbuydb') || session('itemsbuy'))
				 <form action="{{route('itemsbuy.update',['voucher_code'=>$voucher->voucher_code])}}" method="POST" style="margin-top:2%" enctype="multipart/form-data">
				  <input type="hidden" name="_token" value="{{csrf_token()}}">
				   <div class="row">
					<div class="col-md-4 pr-md-1">
					 <div class="form-group">
					   <label>VOUCHER CODE *</label>				       
				       <input type="text" class="form-control" placeholder="VOUCHER CODE"  name="voucher_code" value="{{$voucher->voucher_code}}">
					 </div>
					</div>
					<div class="col-md-4 pr-md-1">
					 <div class="form-group">
					   <label>SUPPLIER</label>				       
				        
				       	<select type="select"name="supplier_id" id="supplier_id" class="form-control js-example-tags1 custom-select" >
						<option value="{{$voucher->supplier_id}}" selected="">{{$voucher->supplier->name}}</option>
						@foreach($suppliers as $supplier)
							@if($voucher->supplier_id != $supplier->id)
                             <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endif
						 @endforeach
					   </select>
					 </div>
					</div>
					<div class="col-md-4 pr-md-1">
					 <div class="form-group">
					 	<label>STORE</label>
				        <select class="form-control" name="store_id">
						<option value="{{$voucher->store_id}}" selected="">{{$voucher->store->name}}</option>
						@foreach($stores as $store)
						 @if($voucher->store_id != $store->id)
                             <option value="{{$store->id}}">{{$store->name}}</option>
                            @endif
						 @endforeach
					   </select>
					 </div>
					</div>
				   </div>
				    <div class="row">
					
					<div class="col-md-8 pr-md-1">
					 <div class="form-group">
					 	<label>PICTURE</label>
				        <input type="file" name="voucher_file" class="form-control">
				     </div>
					</div>
					<div class="col-md-4 pr-md-1">
					 <div class="form-group">
					 	@if($voucher->voucher_file)
					                           <button type="button" class="btn" data-toggle="modal" data-target=".myimage{{$voucher->id}}"> <img class="image" src="{{asset('images/ftth/voucher/'.$voucher->voucher_file)}}" alt=""></button>
					                           
       
					                       @else
					                            <img src="{{asset('images/voucher.jpg')}}" class="img-responsive image">
					                          
					                       @endif 
					                       <!-- ------------------Picture modal Start------------ -->

										<div class="modal fade myimage{{$voucher->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">														

														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
														
													</div>
													<div class="modal-body">
														 <img src="{{asset('images/ftth/voucher/'.$voucher->voucher_file)}}" class="image2">
													</div>												

												</div>
											</div>
										</div>
										<!-- ------------------Picture modal End------------ -->
				     </div>
					</div>
					
				   
				   </div>

				    <div class="row">
					<div class="col-md-12 pr-md-1">
					 <div class="form-group" align="center">
					  <button type="submit" class="btn btn-primary">UPDATE</button>
					 </div>
					</div>
					
				   </div>
				 </form>
				 @endif
			</div>
								         </div>
								     </div>
                                </div>
                            </div>
                        	
                        </div> 
                       
                        <!------------------------>

        
@endsection
@section('script')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
 <script type="text/javascript">
 $(document).ready(function(){ 
    
    $("#alert").fadeOut(3000);
 
});
 $(".js-example-tags").select2({
  tags: true
});
 $(".js-example-tags1").select2({
  tags: true
});
$(document).ready(function() {

    $('select[name="category_id"]').on('change', function(){
        var category_id = $(this).val();
        var APP_URL = {!! json_encode(url('/')) !!};       
         
        if(category_id) {

            $.ajax({
            	url: APP_URL+'/admin/ItemsBuyList/itemnames/get/'+category_id,
                type:"GET",
                dataType:"json",
                beforeSend: function(){

                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {
                     
                    $('select[name="names"]').empty();

                    $.each(data, function(key, value){
                     $('select[name="names"]').append('<option value="'+ key +'">' + value + '</option>');

                    });
                },
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="names"]').empty();
        }

    });

});

document.getElementById('yourBox').onchange = function() {
    document.getElementById('yourText').disabled = !this.checked;
};


</script>
 
 @endsection
