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
        <div>ROLE UPDATE
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
      <a href="{{route('role.list')}}">
        <button type="button" class="btn mr-2 mb-2 btn-primary"><i class="metismenu pe-7s-angle-left-circle"></i> BACK TO ROLE LIST</button>
      </a>
      <br><br>
      <form class="form-valide" action="{{ route('role.update',$role->id) }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <label for="name"><i class="metismenu pe-7s-id">
          </i> ROLE NAME *</label>
          <input type="text" class="form-control" name="name" value="{{$role->name}}">
        </div>

        <div class="form-group">
          <label for="name"><i class="metismenu pe-7s-id">
          </i> PERMISSIONS</label><br>
          
        </div>
        <div class="row">
          <div class="col-md-12 pr-md-1">
           <div class="form-group">          
           <input type="checkbox" id="select-all">
            <label for="checkbox">Select All</label>
          </div>
        </div>
      </div>

        @foreach($permissions as $key => $per)
        <div class="row">
          <div class="col-md-12 pr-md-1">
          <div class="form-group">
            <label>{{ ucfirst($key) }}</label><br>
          </div>
        </div>
        </div>

        <div class="row">
          <div class="col-md-12 pr-md-1">
          <div class="form-group" style="padding-left: 2%">
            @foreach($per as $p)
            @if(isset($role->permissions[$p]))
             <label class="checkbox-inline" style="font-weight: 500">
               <input type="checkbox" checked="checked" name="permissions[{{ $p }}]" value="true"> {{ $p }}
             </label>
              @else
             <label class="checkbox-inline">
               <input type="checkbox" name="permissions[{{ $p }}]" value="true"> {{ $p }}
             </label>
            @endif
            @endforeach
          </div>
        </div>
        </div>

        @endforeach
        <div class="row">
          <div class="col-md-12 pr-md-1">
           <div class="form-group" style="text-align: center;">
            <button type="submit" class="btn btn-primary">UPDATE</button>
           </div>
          </div>
        </div>
      </form>

    </div>
  </div>
  <div class="row">

    <div class="col-md-12">
      <div class="main-card mb-3 card">
        <div class="card-body" style="padding: 0px">

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
$(document).ready(function() {
  $('#select-all').click(function() {
    $('input[type="checkbox"]').prop('checked', this.checked);
  })
});
</script>
@endsection
