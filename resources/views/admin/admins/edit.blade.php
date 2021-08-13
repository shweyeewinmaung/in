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
                                        <i class="metismenu pe-7s-user-female">
                                        </i>
                                    </div>
                                    <div>ADMIN UPDATE
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
                        	 @if(session('errorstatuses'))
                        	 
					          <div class="alert alert-danger fade show" role="alert" >{{session('errorstatuses')}}
					           
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
                        		<a href="{{route('admin.list')}}">
                        	    <button type="button" class="btn mr-2 mb-2 btn-primary"><i class="metismenu pe-7s-angle-left-circle"></i> BACK TO LIST</button>
                        	    </a>
                        	<!-- </div> -->
                        	<!-- <div class="col-md-6"> -->                       		
						    
                        	</div>
                           </div>
                           	 <div class="row">

                        	 <div class="col-md-12">
                                 <div class="main-card mb-3 card">
                                    <div class="card-body" style="padding: 0px">
                                    	 <form method="post" action="{{route('admin.update',['id'=>$admin->id])}}" >
								           
								               
								                  <input type="hidden" name="_token" value="{{csrf_token()}}">
								                    <div class="row">
								                     <div class="col-md-6 pr-md-1">
								                       <div class="form-group">
								                         <label><i class="metismenu-icon pe-7s-user"></i> NAME *</label>
								                         <input placeholder="Name" type="text" class="form-control" name="name" value="{{$admin->name}}">
								                         
								                          @if ($errors->has('name'))
								                          <div class="alert alert-danger fade show" role="alert" >
								                          	{{ $errors->first('name') }}
								                          </div>
								                           
								                          @endif
								                       </div>
								                     </div>
								                     <div class="col-md-6 px-md-1">
								                       <div class="form-group">
								                         <label><i class="metismenu-icon pe-7s-mail"></i> EMAIL *</label>
								                         <input type="email" class="form-control" placeholder="Email" name="email" value="{{$admin->email}}">
								                          @if ($errors->has('email'))
								                           <div class="alert alert-danger fade show" role="alert" >
								                          	{{ $errors->first('email') }}
								                          </div>                           
								                          @endif
								                       </div>
								                     </div>
								                  </div>

								                   <div class="row">
										            <div class="col-md-6 pr-md-1">
										              <div class="form-group">
										                <label><i class="metismenu pe-7s-unlock"></i> PASSWORD *</label>
										                <input type="password" class="form-control" placeholder="Password" name="password">
										              </div>
										            </div>
										            <div class="col-md-6 px-md-1">
										              <div class="form-group">
										                <label><i class="metismenu pe-7s-id"></i> ROLE</label>
										               <!--  <input type="text" class="form-control" placeholder="Position"  > -->
										               <select type="select" id="exampleCustomSelect" name="role" class="custom-select">
										               	   <option selected value="{{$admin->role_id}}" >{{$admin->role['name']}}</option>
										                   @foreach($roles as $role)
										                   @if($role->id != $admin->role_id)
										                   <option value="{{$role->id}}">{{$role->name}}</option>  
										                   @endif 
										                   @endforeach
								                       </select>
										             
										               
										              </div>
										            </div>
										           </div>
										           <div class="row">
										            <div class="col-md-12 pr-md-1">
										              <div class="form-group">
										                <label><i class="metismenu pe-7s-users"></i> AGENT</label>
										                 <select type="select" id="exampleCustomSelect" name="agent" class="custom-select">
										               	  <option selected value="{{$admin->agent_id}}" >{{$admin->agent['name']}}</option>
										                   @foreach($agents as $agent)
										                   @if($agent->id != $admin->agent_id)
										                   <option value="{{$agent->id}}">{{$agent->name}}</option>   
										                   @endif
										                   @endforeach
								                       </select>
										               
										              </div>
										            </div>
										          <!--   <div class="col-md-6 px-md-1">
										              <div class="form-group">
										                <label><i class="metismenu pe-7s-photo"></i> AVATOR</label>
										             										              
										               
										              </div>
										            </div> -->
										           </div>

												  <div class="row">
										            <div class="col-md-12 pr-md-1">
										              <div class="form-group" style="text-align: center;">
										              	 <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
								                <button type="submit" class="btn btn-primary">UPDATE</button>
										              </div>
										            </div>
										          </div>
										     </form>
                                    </div>
                                </div>
                            </div>
                        	
                        </div> 

        
@endsection
@section('script')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script type="text/javascript">
 $(document).ready(function(){ 
   $("#alert").fadeOut(3000);
 
});
 
 </script>
 @endsection
