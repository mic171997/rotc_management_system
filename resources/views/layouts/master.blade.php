<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-user" content="{{ auth()->user() }}">
    <meta name="server-datetime" content="{{ now() }}">

    <title>{{ config('app.name', 'ROTC MANAGEMENT SYSTEM') }}</title>


    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <!--Font Awesome [ OPTIONAL ]-->
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="css/nifty.min.css" rel="stylesheet">


    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="css/demo/nifty-demo-icons.min.css" rel="stylesheet">

    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="plugins/pace/pace.min.css" rel="stylesheet">
    <script src="plugins/pace/pace.min.js"></script>


    <!--Demo [ DEMONSTRATION ]-->
    <link href="css/demo/nifty-demo.min.css" rel="stylesheet">

    <!--Dropzone [ OPTIONAL ]-->
    <link href="plugins/dropzone/dropzone.min.css" rel="stylesheet">

    <!--Spinkit [ OPTIONAL ]-->
    <link href="plugins/spinkit/css/spinkit.min.css" rel="stylesheet">

    <!--Custom scheme [ OPTIONAL ]-->
    {{--
    <link href="css/themes/type-a/theme-navy.min.css" rel="stylesheet"> --}}

    <!--Select2 [ OPTIONAL ]-->
    <link href="plugins/select2/css/select2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  </head>

  <!--TIPS-->
  <!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->

  <body>
    <div id="app">
      <div id="container" class="effect aside-float aside-bright mainnav-sm">

        <!--NAVBAR-->
        <!--===================================================-->
        <header id="navbar">
          <div id="navbar-container" class="boxed">

            <!--Brand logo & name-->
            <!--================================-->
            <div class="navbar-header">
              <a href="#" class="navbar-brand">
                <img src="img/logo.png" alt="Nifty Logo" class="brand-icon">
                <div class="brand-title">
                  <span class="brand-text" style="font-size: 14px;">
                    {{config('app.name')}}
                  </span>
                </div>
              </a>
            </div>
            <!--================================-->
            <!--End brand logo & name-->


            <!--Navbar Dropdown-->
            <!--================================-->
            <div class="navbar-content">
              <ul class="nav navbar-top-links">

                <!--Navigation toogle button-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li class="tgl-menu-btn">
                  <a class="mainnav-toggle" href="#">
                    <i class="demo-pli-list-view"></i>
                  </a>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End Navigation toogle button-->



                <!--Search-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li>
                  <div class="custom-search-form">
                    <label class="btn btn-trans" for="search-input" data-toggle="collapse" data-target="#nav-searchbox">
                      <i class="demo-pli-magnifi-glass"></i>
                    </label>
                    <form>
                      <div class="search-container collapse" id="nav-searchbox">
                        <input id="search-input" type="text" class="form-control" placeholder="Type for search...">
                      </div>
                    </form>
                  </div>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End Search-->

              </ul>
              <ul class="nav navbar-top-links">

                <!--User dropdown-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                          {{ __('Logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                        </form>
                      </li>
                {{-- <li id="dropdown-user" class="dropdown">
                  <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                    <span class="ic-user pull-right">
                     
                      <i class="demo-pli-male"></i>
                    </span>
                 
                  </a>


                  <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                    <ul class="head-list">
                      <li>
                        <a href="javascript:;" @click="togglechangePassMdl(true)">
                          <i class="demo-pli-gear icon-lg icon-fw"></i> Change Password
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();"><i class="demo-pli-unlock icon-lg icon-fw"></i>
                          {{ __('Logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                        </form>
                      </li>
                    </ul>
                  </div>
                </li> --}}
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End user dropdown-->


                {{-- <li>
                  <a href="#" class="aside-toggle">
                    <i class="demo-pli-dot-vertical"></i>
                  </a>
                </li> --}}
              </ul>
            </div>
            <!--================================-->
            <!--End Navbar Dropdown-->

          </div>
        </header>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">

          <!--CONTENT CONTAINER-->
          <!--===================================================-->
          <div id="content-container">
           {{-- <span style="margin-left: 10px"> <breadcrumb></breadcrumb> </span> --}}
           <div style="padding-left: 40px">
            <breadcrumb></breadcrumb>
          </div>
            {{-- <keep-alive> --}}
              <router-view></router-view>
            {{-- </keep-alive> --}}
          </div>
          <!--===================================================-->
          <!--END CONTENT CONTAINER-->

          <!--MAIN NAVIGATION-->
          <!--===================================================-->
          <nav id="mainnav-container">
            <div id="mainnav">

              <!--OPTIONAL : ADD YOUR LOGO TO THE NAVIGATION-->

              <!--Menu-->
              <!--================================-->
              <div id="mainnav-menu-wrap">
                <div class="nano">
                  <div class="nano-content">

                    <!--Profile Widget-->
                    <!--================================-->
                    {{-- <div id="mainnav-profile" class="mainnav-profile">
                      <div class="profile-wrap text-center">
                        <div class="pad-btm">
                          <img class="img-circle img-md" src="img/profile-photos/1.png" alt="Profile Picture"
                            style="margin-left: 30%">
                        </div>
                        <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                          <span class="pull-right dropdown-toggle">
                            <i class="dropdown-caret"></i>
                          </span>
                          @auth
                          <p class="mnp-name">{{ Auth::user()->name }}</p>
                          @endauth
                          <span class="mnp-desc"> {{Auth::user()->position }}</span>
                        </a>
                      </div>
                      <div id="profile-nav" class="collapse list-group bg-trans">
                        <a href="javascript:;" @click="togglechangePassMdl(true)" class="list-group-item">
                          <i class="demo-pli-gear icon-lg icon-fw"></i> Change Password
                        </a>
                        <a href="#" class="list-group-item">
                          <i class="demo-pli-unlock icon-lg icon-fw"></i> Logout
                        </a>
                      </div>
                    </div> --}}

                    <ul id="mainnav-menu" class="list-group">
                        <br>
                      <!--Category name-->
                      <li class="list-header">Navigation</li>

                      <router-link tag="li" class="nav-item" to="dashboard"><a href="#">
                          <i class="fa fa-home"></i>
                          <span class="menu-title">Dashboard</span>
                        </a></router-link>

                        <router-link tag="li" class="nav-item" to="cadets"><a href="#">
                          <i class="fa fa-plus-square"></i>
                          <span class="menu-title">Add Cadet</span>
                        </a></router-link>
                         <router-link tag="li" class="nav-item" to="schedules"><a href="#">
                          <i class="fa fa-bars"></i>
                          <span class="menu-title">Add Schedules</span>
                        </a></router-link>
                        <router-link tag="li" class="nav-item" to="file_absents"><a href="#">
                          <i class="fa fa-pencil-square-o"></i>
                          <span class="menu-title">File Absent</span>
                        </a></router-link>
                          <router-link tag="li" class="nav-item" to="absents_request"><a href="#">
                          <i class="fa fa-envelope-o"></i>
                          <span class="menu-title">Absent Request</span>
                        </a></router-link>
                         {{-- <li class="nav-item">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                          <i class="demo-pli-unlock icon-lg icon-fw"></i> {{ __('Logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                        </form>
                      </li> --}}
                    </ul>
                    </li>
                  </div>
                </div>
              </div>
              <!--================================-->
              <!--End menu-->

            </div>
          </nav>
          <!--===================================================-->
          <!--END MAIN NAVIGATION-->

        </div>

        <footer id="footer">
          <p class="pad-lft">&#0169; 2025 ROTC MANAGEMENT SYSTEM</p>

        </footer>
        
        <!--===================================================-->
        <button class="scroll-top btn">
          <i class="pci-chevron chevron-up"></i>
        </button>
        <!--===================================================-->
      </div>

      <Modal class="modal fade" id="passwordMdl" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" @toggle="togglechangePassMdl">
      </Modal>

    </div>
    
    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src="js/jquery.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="js/bootstrap.min.js"></script>


    <!--NiftyJS [ RECOMMENDED ]-->
    <script src="js/nifty.min.js"></script>




    <!--=================================================-->

    <!--Demo script [ DEMONSTRATION ]-->
    {{-- <script src="js/demo/nifty-demo.min.js"></script> --}}

    <!--Dropzone [ OPTIONAL ]-->
    <script src="plugins/dropzone-5.7.0/dist/min/dropzone.min.js"></script>

    <!--Form File Upload [ SAMPLE ]-->
    {{-- <script src="js/demo/form-file-upload.js"></script> --}}

    <!--Select2 [ OPTIONAL ]-->
    <script src="plugins/select2/js/select2.min.js"></script>




    <!--Flot Chart [ OPTIONAL ]-->
    <script src="plugins/flot-charts/jquery.flot.min.js"></script>
    <script src="plugins/flot-charts/jquery.flot.categories.min.js"></script>
    <script src="plugins/flot-charts/jquery.flot.orderBars.min.js"></script>
    <script src="plugins/flot-charts/jquery.flot.tooltip.min.js"></script>
    <script src="plugins/flot-charts/jquery.flot.resize.min.js"></script>


    <!--Specify page [ SAMPLE ]-->
    <script src="js/demo/dashboard-2.js"></script>


    <script src="{{ mix('js/app.js') }}"></script>

  </body>

</html>