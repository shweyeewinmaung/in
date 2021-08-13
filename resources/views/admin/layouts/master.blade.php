<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TrueNet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
<link rel="icon" href="{{asset('/images/favicon.ico')}}" type="image/x-icon">
<link href="{{asset('/css/main.css')}}" rel="stylesheet">
@yield('stylesheet')
<style type="text/css">
a:hover
{
    color:#666;
    text-decoration: none;
}
a
{
    color:#000;
}
    .app-theme-white .app-footer .app-footer__inner, .app-theme-white .app-header
    {
        background: #2b3c51;
    /*background: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%) !important;*/
}
.vertical-nav-menu ul > li > a.mm-active
{
    background:rgba(255, 255, 255, 0.1);
    color:#fff;

}
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place front is invalid - may break your css so removed */
    padding-top: 50px; /* Location of the box - don't know what this does?  If it is to move your modal down by 100px, then just change top below to 100px and remove this*/
    left: 0;
    right:0; /* Full width (left and right 0) */
    top: 0;
    bottom: 0; /* Full height top and bottom 0 */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    z-index: 9999; /* Sit on top - higher than any other z-index in your site*/
}
.modal-backdrop {
    position: relative;
}
.table thead th
{
    text-align: center;
    /*border-bottom: 2px solid #081624;*/
}
.table thead
{
    background: #ccc;
    color:#2b3c51;
}
.table tbody td
{

    text-align: center;
    /*border-bottom: 2px solid #081624;*/
}
.table tbody tr
{
    border-bottom: 2px solid#ccc;
}
.modal-title
{
    text-transform: uppercase;
}
[class^="pe-7s-"], [class*=" pe-7s-"]
{
    font-weight: bold;
}

</style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        @include('admin.layouts.top')      
        <div class="app-main">
                <div class="app-sidebar sidebar-shadow" style="background-image: url({{asset('/images/city1.ebc5562d.jpg')}});">
                    <div class="app-header__logo">
                        <div class="logo-src"></div>
                        <div class="header__pane ml-auto">
                            <div>
                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="app-header__mobile-menu">
                        <div>
                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="app-header__menu">
                        <span>
                            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
                    </div>    <div class="scrollbar-sidebar">
                        <div class="app-sidebar__inner">
                            <ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading" class="mm-active">Dashboard Control</li>

                                <li>
                                    <a href="{{route('admin.dashboard')}}" class="{{ (request()->is('admin')) ? 'mm-active' : '' }}" >
                                        <i class="metismenu-icon pe-7s-rocket"></i>
                                        INVENTORY DASHBOARD
                                       
                                    </a>
                                </li>
                                @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-agent") || \Auth::user()->hasPermission("view-role") || \Auth::user()->hasPermission("view-admin"))
                                <li class="app-sidebar__heading">Admins Control</li>
                                <li>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-user"></i>
                                        ADMINS
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ ((request()->is('admin/adminslist')) ||(request()->is('admin/adminslist/search/post')) ||(request()->is('admin/admincreate')) ||(request()->is('admin/Agentlist')) ||(request()->is('admin/Agentlist/search/post')) || (request()->is('admin/Rolelist')) ||(request()->is('admin/Rolelist/search/post')) ||(request()->is('admin/Rolelist/create'))  ) ? 'mm-show mm-collapse' : '' }}">
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-admin"))
                                        <li>
                                            <a href="{{route('admin.list')}}" class="{{ (request()->is('admin/adminslist') ||(request()->is('admin/adminslist/search/post')) ||(request()->is('admin/admincreate'))) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Admin
                                            </a>
                                        </li>
                                        @endif
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-agent"))
                                         <li>
                                            <a href="{{route('agent.list')}}" class="{{ (request()->is('admin/Agentlist') ||(request()->is('admin/Agentlist/search/post'))) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Agent
                                            </a>
                                        </li>
                                        @endif
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-role"))
                                         <li>
                                            <a href="{{route('role.list')}}" class="{{ (request()->is('admin/Rolelist') ||(request()->is('admin/Rolelist/search/post')) ||(request()->is('admin/Rolelist/create'))) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Role
                                            </a>
                                        </li>
                                        @endif

                                    </ul>
                                </li>
                                @endif
                                @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-category"))
                                  <li class="app-sidebar__heading">LISTS CONTROL</li>
                                    <li>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-menu"></i>
                                        LISTS
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul  class="{{ ((request()->is('admin/FTTH'))  ||(request()->is('admin/FTTHStore/search/post'))  ) ? 'mm-show mm-collapse' : '' }}">
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-category"))
                                        <li>
                                            <a href="{{route('category.list')}}" class="{{((request()->is('admin/FTTH'))   ||(request()->is('admin/FTTHStore/search/post')) ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                FTTH
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                                 @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itembuy")||
                                 \Auth::user()->hasPermission("view-itemserver")||
                                 \Auth::user()->hasPermission("view-itemstreet")
                                 ||\Auth::user()->hasPermission("view-itemtransfer") )
                                  <li class="app-sidebar__heading">ITEMS CONTROL</li>
                                    <li>
                                    <a href="#">
                                         <i class="metismenu-icon pe-7s-server"></i>
                                        ITEMS
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ ((request()->is('admin/ItemsBuyList')) || (request()->is('admin/ItemsBuyList/Entry')) || (request()->is('admin/ItemsCustomerList')) 
                                        || (request()->is('admin/ItemsCustomerList/search/list')) ||(request()->is('admin/ItemsServerList')) || (request()->is('admin/ItemsServerList/search/list')) ||(request()->is('admin/ItemsStreetList')) || (request()->is('admin/ItemsStreetList/search/list')) || (request()->is('admin/ItemsTransferList')) || (request()->is('admin/ItemsTransferList/search/list'))  ) ? 'mm-show mm-collapse' : '' }}">
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itembuy"))
                                        <li>
                                            <a href="{{route('itemsbuy.list')}}"  class="{{((request()->is('admin/ItemsBuyList')) || (request()->is('admin/ItemsBuyList/Entry'))   ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                ITEMS BUY
                                            </a>
                                        </li>
                                        @endif

                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemcustomer"))
                                        <li>
                                            <a href="{{route('customers.list')}}" class="{{((request()->is('admin/ItemsCustomerList')) || (request()->is('admin/ItemsCustomerList/search/list'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                ITEMS CUSTOMER
                                            </a>
                                        </li>
                                        @endif
                                         @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemserver"))
                                        <li>
                                            <a href="{{route('servers.list')}}" class="{{((request()->is('admin/ItemsServerList')) || (request()->is('admin/ItemsServerList/search/list'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                ITEMS SERVER
                                            </a>
                                        </li>
                                        @endif

                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemstreet"))
                                        <li>
                                            <a href="{{route('streets.list')}}" class="{{((request()->is('admin/ItemsStreetList')) || (request()->is('admin/ItemsStreetList/search/list'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                ITEMS STREET
                                            </a>
                                        </li>
                                        @endif
                                         @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-itemtransfer"))
                                        <li>
                                            <a href="{{route('transfers.list')}}" class="{{(  (request()->is('admin/ItemsTransferList')) || (request()->is('admin/ItemsTransferList/search/list')) ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                ITEMS TRANSFER
                                            </a>
                                        </li>
                                        @endif
                                    
                                    </ul>
                                </li>
                                @endif
                                  @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-store")|| 
                                \Auth::user()->hasPermission("view-servername")||
                                \Auth::user()->hasPermission("view-streetname")||
                                \Auth::user()->hasPermission("view-supplier")||
                                \Auth::user()->hasPermission("view-jobtitle")|| 
                                \Auth::user()->hasPermission("view-staff"))
                                  <li class="app-sidebar__heading">ENTRY CONTROL</li>
                                    <li>
                                    <a href="#">
                                         <i class="metismenu-icon fa fa-newspaper"></i>
                                        ENTRY
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ ((request()->is('admin/CustomerNameList')) || (request()->is('admin/CustomerNameList/search/post')) ||(request()->is('admin/storelist')) || (request()->is('admin/storelist/search/post')) || (request()->is('admin/ServerNameList')) || (request()->is('admin/ServerNameList/search/post')) ||(request()->is('admin/StreetNameList')) || (request()->is('admin/StreetNameList/search/post')) ||(request()->is('admin/SupplierList')) || (request()->is('admin/SupplierList/search/post')) || (request()->is('admin/StaffList')) || (request()->is('admin/StaffList/search/post')) || (request()->is('admin/JobtitleList')) || (request()->is('admin/JobtitleList/search/post')) ) ? 'mm-show mm-collapse' : '' }}">
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-customername"))
                                        <li>
                                            <a href="{{route('customername.list')}}" class="{{((request()->is('admin/CustomerNameList')) || (request()->is('admin/CustomerNameList/search/post'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                CUSTOMER NAMES
                                            </a>
                                        </li>
                                        @endif
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-store"))
                                        <li>
                                            <a href="{{route('store.list')}}" class="{{((request()->is('admin/storelist')) || (request()->is('admin/storelist/search/post'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                STORES
                                            </a>
                                        </li>
                                        @endif
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-servername"))
                                        <li>
                                            <a href="{{route('servername.list')}}" class="{{((request()->is('admin/ServerNameList')) || (request()->is('admin/ServerNameList/search/post'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                SERVER NAMES
                                            </a>
                                        </li>
                                        @endif
                                         @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-streetname"))
                                        <li>
                                            <a href="{{route('streetname.list')}}" class="{{((request()->is('admin/StreetNameList')) || (request()->is('admin/StreetNameList/search/post'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                STREET NAMES
                                            </a>
                                        </li>
                                        @endif
                                         @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-supplier"))
                                        <li>
                                            <a href="{{route('supplier.list')}}" class="{{((request()->is('admin/SupplierList')) || (request()->is('admin/SupplierList/search/post'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                SUPPLIERS
                                            </a>
                                        </li>
                                        @endif
                                        @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-staff"))
                                        <li>
                                            <a href="{{route('staff.list')}}"  class="{{((request()->is('admin/StaffList')) || (request()->is('admin/StaffList/search/post'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                STAFFS
                                            </a>
                                        </li>
                                        @endif
                                         @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-jobtitle"))
                                        <li>
                                            <a href="{{route('jobtitle.list')}}" class="{{((request()->is('admin/JobtitleList')) || (request()->is('admin/JobtitleList/search/post'))  ) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                JOB TITLES
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                                 @if(\Auth::user()->isSuper() || \Auth::user()->hasPermission("view-history") )
                                  <li class="app-sidebar__heading">HISTORY CONTROL</li>
                                    <li>
                                    <a href="{{route('history.list')}}" class="{{((request()->is('admin/HistoryList'))   ) ? 'mm-active' : '' }}">
                                         <i class="metismenu-icon pe-7s-timer"></i>
                                        HISTORY                                    
                                    </a>
                                
                                </li>
                                @endif

                               <!-- <li class="app-sidebar__heading">Widgets</li>
                                <li>
                                    <a href="dashboard-boxes.html">
                                        <i class="metismenu-icon pe-7s-display2"></i>
                                        Dashboard Boxes
                                    </a>
                                </li>
                                <li class="app-sidebar__heading">Forms</li>
                                <li>
                                    <a href="forms-controls.html">
                                        <i class="metismenu-icon pe-7s-mouse">
                                        </i>Forms Controls
                                    </a>
                                </li>
                                <li>
                                    <a href="forms-layouts.html">
                                        <i class="metismenu-icon pe-7s-eyedropper">
                                        </i>Forms Layouts
                                    </a>
                                </li>
                                <li>
                                    <a href="forms-validation.html">
                                        <i class="metismenu-icon pe-7s-pendrive">
                                        </i>Forms Validation
                                    </a>
                                </li>
                                <li class="app-sidebar__heading">Charts</li>
                                <li>
                                    <a href="charts-chartjs.html">
                                        <i class="metismenu-icon pe-7s-graph2">
                                        </i>ChartJS
                                    </a>
                                </li>
                                <li class="app-sidebar__heading">PRO Version</li>
                                <li>
                                    <a href="https://dashboardpack.com/theme-details/architectui-dashboard-html-pro/" target="_blank">
                                        <i class="metismenu-icon pe-7s-graph2">
                                        </i>
                                        Upgrade to PRO
                                    </a>
                                </li> -->
                          

                            </ul>
                        </div>
                    </div>
                </div>    
                <div class="app-main__outer">
                    <div class="app-main__inner">
                         @yield('content')       
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="mb-3 card" style="box-shadow: none; display: none">
                                    
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="tabs-eg-77">
                                                
                                                <div class="scroll-area-sm">
                                                    <div class="scrollbar-container">
                                                        <ul class="rm-list-borders rm-list-borders-scroll list-group list-group-flush">
                                                          
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                </div>
                            </div>
                        </div>
                        
                        
                       
                    </div>
                    <div class="app-wrapper-footer">
                         @include('admin.layouts.footer')
                    </div>    
                </div>
                <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
        </div>
    </div>
    @yield('script')
<script type="text/javascript" src="{{asset('/scripts/main.js')}}"></script></body>
</html>
