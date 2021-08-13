<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>TrueNet</title>
  <!-- loader-->
  <!-- <link href="{{asset('/public/css/pace.min.css')}}" rel="stylesheet"/>
  <script src="{{asset('/public/js/pace.min.js')}}"></script> -->
  <!--favicon-->
  <link rel="icon" href="{{asset('/images/favicon.ico')}}" type="image/x-icon">
  
  <!-- Bootstrap core CSS-->
  <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="{{asset('/css/animate.css')}}" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="{{asset('/css/icons.css')}}" rel="stylesheet" type="text/css"/>
  <!-- Custom Style-->
  <link href="{{asset('/css/app-style.css')}}" rel="stylesheet"/>
  
</head>

<body class="bg-theme bg-theme7">

<!-- start loader -->
   <div id="pageloader-overlay" class="visible incoming"><div class="loader-wrapper-outer"><div class="loader-wrapper-inner" ><div class="loader"></div></div></div></div>
   <!-- end loader -->

<!-- Start wrapper-->
 <div id="wrapper">

 <div class="loader-wrapper"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
    <div class="card card-authentication1 mx-auto my-5">
        <div class="card-body">
         <div class="card-content p-2">
            <div class="text-center">

                <img src="{{asset('/images/log.png')}}" alt="logo icon">

            </div>
            <br>
          <!-- <div class="card-title text-uppercase text-center py-3">Sign In</div> -->
            <form method="POST" action="{{route('admin.login.submit')  }}" aria-label="{{ __('Login') }}">
                 @csrf
              <div class="form-group">
              <label for="exampleInputUsername" class="sr-only">Email</label>
               <div class="position-relative has-icon-right">
                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} input-shadow" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter Email">
                  <div class="form-control-position">
                     <!--  <i class="metismenu pe-7s-user"></i> -->
                  </div>
                  @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
               </div>
              </div>
              <div class="form-group">
              <label for="exampleInputPassword" class="sr-only">Password</label>
               <div class="position-relative has-icon-right">
                  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} input-shadow" placeholder="Enter Password" name="password" required>
                  <div class="form-control-position">
                     <!--  <i class="icon-lock"></i> -->
                  </div>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
               </div>
              </div>
              <br>
            <!-- <div class="form-row">
             <div class="form-group col-6">
               <div class="icheck-material-white">
                <input type="checkbox" name="remember" id="remember" checked="{{ old('remember') ? 'checked' : '' }}">
                <label for="user-checkbox">Remember me</label>
              </div>
             </div> -->
             <!-- <div class="form-group col-6 text-right">
              <a href="reset-password.html">Reset Password</a>
             </div> -->
            </div>

            
          
             <button type="submit" class="btn btn-light btn-block">Sign In</button>
             <!--  <div class="text-center mt-3">Sign In With</div> -->
              
            <!--  <div class="form-row mt-4">
              <div class="form-group mb-0 col-6">
               <button type="button" class="btn btn-light btn-block"><i class="fa fa-facebook-square"></i> Facebook</button>
             </div>
             <div class="form-group mb-0 col-6 text-right">
              <button type="button" class="btn btn-light btn-block"><i class="fa fa-twitter-square"></i> Twitter</button>
             </div>
            </div> -->
             
             </form>
             <br><br>
           </div>
          </div>
         <!--  <div class="card-footer text-center py-3">
            <p class="text-warning mb-0">Do not have an account? <a href="register.html"> Sign Up here</a></p>
          </div>
         </div> -->
    
     <!--Start Back To Top Button-->
   <!--  <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a> -->
    <!--End Back To Top Button-->
    
    <!--start color switcher-->
 <!--   <div class="right-sidebar">
    <div class="switcher-icon">
      <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
    </div>
    <div class="right-sidebar-content">

      <p class="mb-0">Gaussion Texture</p>
      <hr>
      
      <ul class="switcher">
        <li id="theme1"></li>
        <li id="theme2"></li>
        <li id="theme3"></li>
        <li id="theme4"></li>
        <li id="theme5"></li>
        <li id="theme6"></li>
      </ul>

      <p class="mb-0">Gradient Background</p>
      <hr>
      
      <ul class="switcher">
        <li id="theme7"></li>
        <li id="theme8"></li>
        <li id="theme9"></li>
        <li id="theme10"></li>
        <li id="theme11"></li>
        <li id="theme12"></li>
        <li id="theme13"></li>
        <li id="theme14"></li>
        <li id="theme15"></li>
      </ul>
      
     </div>
   </div> -->
  <!--end color switcher-->
    
    </div><!--wrapper-->
    
  <!-- Bootstrap core JavaScript-->
  <script src="{{asset('/js/jquery.min.js')}}"></script>
  <script src="{{asset('/js/popper.min.js')}}"></script>
  <script src="{{asset('/js/bootstrap.min.js')}}"></script>
    
  <!-- sidebar-menu js -->
  <script src="{{asset('/js/sidebar-menu.js')}}"></script>
  
  <!-- Custom scripts -->
  <script src="{{asset('/js/app-script.js')}}"></script>
  
</body>
</html>



