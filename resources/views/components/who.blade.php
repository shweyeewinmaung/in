@if(Auth::guard('web')->check())
<p>You Are Login As <strong>User</strong></p>
@else
<p>You Are Logout As <strong>User</strong></p>
@endif

@if(Auth::guard('admin')->check())
<p>You Are Login As <strong>Admin</strong></p>
@else
<p>You Are Logout As <strong>Admin</strong></p>
@endif