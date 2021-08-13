@extends('admin.layouts.master')
@section('stylesheet')
<style type="text/css">
#loadingDiv{
  position:absolute;
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
                                    <div>ITEM BUY ENTRY
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
                         var url1='{{route("additemsbuy",[":itemname_id",":category_id",":qty",":amount",":mac"])}}';
                      
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
                           
                             //alert('Insert not greater than avalible Item');
                            swal({
								 title: 'Warning!',
								 text: "Cannot Save to Selected Item",
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

			</div>

										      
                                    </div>
                                </div>
                            </div>
                        	
                        </div> 

                        <!------------------------>
                        @if(session('itemsbuycreate'))
                        <div class="row">

                        	 <div class="col-md-12">
                                 <div class="main-card mb-3 card">
                                    <div class="card-body" style="padding: 0px">
                                    	 <div class="row">
                                    	 	<div class="col-md-12">
			<!-- <div class="col-md-10 offset-md-1">	 -->
				<div class="table-responsive">
     		        <table class="table align-items-center table-flush table-border" >
						<thead>
							<tr>
								<td colspan="6"><h4 align="center">View Selected ItemsBuy</h4></td>
							</tr>
							<tr>
								<th>FTTH</th>
								<th>ITEM NAME</th>
								<th>MAC</th>
								<th>QTY</th>
								<th>AMOUNT</th>
								
								<th>REMOVE</th>
							</tr>
						</thead>
						<tbody>
							@foreach(session('itemsbuycreate') as $k=>$val)
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
                                        
 	                                    var url='{{route("remove_itemsbuy",[":id"])}}';
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
				 <form action="{{route('itemsbuycheckout')}}" method="POST" style="margin-top:2%" enctype="multipart/form-data" id="formnameupload">
				  <input type="hidden" name="_token" value="{{csrf_token()}}">
				   <div class="row">
					<div class="col-md-4 pr-md-1">
					 <div class="form-group">
					   <label>VOUCHER CODE *</label>				       
				       <input type="text" class="form-control" placeholder="VOUCHER CODE"  name="voucher_code">
					 </div>
					</div>
					<div class="col-md-4 pr-md-1">
					 <div class="form-group">
					   <label>SUPPLIER</label>				       
				        
				       	<select type="select"name="supplier_id" id="supplier_id" class="form-control js-example-tags1 custom-select" >
						<option value="">Click here for Supplier</option>		 
						@foreach($suppliers as $supplier)
						 <option value="{{$supplier->id}}">{{$supplier->name}}</option>
						 @endforeach
					   </select>
					 </div>
					</div>
					<div class="col-md-4 pr-md-1">
					 <div class="form-group">
					 	<label>STORE</label>
				        <select class="form-control" name="store_id">
						@foreach($stores as $store)
						 <option value="{{$store->id}}">{{$store->name}}</option>
						 @endforeach
					   </select>
					 </div>
					</div>
				   </div>
				    <div class="row">
					
					<div class="col-md-12 pr-md-1">
					 <div class="form-group">
					 	<label>PICTURE</label>
				        <input type="file" name="voucher_file" class="form-control">
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
				 </form>
			</div>
								         </div>
								     </div>
                                </div>
                            </div>
                        	
                        </div> 
                        @endif
                        <!------------------------>
          <div id="loadingDiv"></div> 
                         
      
@endsection
@section('script')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
 <script type="text/javascript">
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
