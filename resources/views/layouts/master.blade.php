<!DOCTYPE html>
<html lang="en">



  <!-- Mirrored from www.themeon.net/nifty/v2.9.1/dashboard-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 24 Oct 2019 07:34:48 GMT -->

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-user" content="{{ auth()->user() }}">
    <meta name="server-datetime" content="{{ now() }}">

    <title>{{ config('app.name', 'Tabz') }}</title>


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
              <a href="index.html" class="navbar-brand">
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


                <!--Mega dropdown-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                {{-- <li class="mega-dropdown">
                  <a href="#" class="mega-dropdown-toggle">
                    <i class="demo-pli-layout-grid"></i>
                  </a>
                  <div class="dropdown-menu mega-dropdown-menu">
                    <div class="row">
                      <div class="col-sm-4 col-md-3">

                        <!--Mega menu list-->
                        <ul class="list-unstyled">
                          <li class="dropdown-header"><i class="demo-pli-file icon-lg icon-fw"></i>
                            Pages</li>
                          <li><a href="#">Profile</a></li>
                          <li><a href="#">Search Result</a></li>
                          <li><a href="#">FAQ</a></li>
                          <li><a href="#">Sreen Lock</a></li>
                          <li><a href="#">Maintenance</a></li>
                          <li><a href="#">Invoice</a></li>
                          <li><a href="#" class="disabled">Disabled</a></li>
                        </ul>

                      </div>
                      <div class="col-sm-4 col-md-3">

                        <!--Mega menu list-->
                        <ul class="list-unstyled">
                          <li class="dropdown-header"><i class="demo-pli-mail icon-lg icon-fw"></i>
                            Mailbox</li>
                          <li><a href="#"><span class="pull-right label label-danger">Hot</span>Indox</a>
                          </li>
                          <li><a href="#">Read Message</a></li>
                          <li><a href="#">Compose</a></li>
                          <li><a href="#">Template</a></li>
                        </ul>
                        <p class="pad-top text-main text-sm text-uppercase text-bold"><i
                            class="icon-lg demo-pli-calendar-4 icon-fw"></i>News</p>
                        <p class="pad-top mar-top bord-top text-sm">Lorem ipsum dolor sit amet,
                          consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean
                          massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
                      </div>
                      <div class="col-sm-4 col-md-3">
                        <!--Mega menu list-->
                        <ul class="list-unstyled">
                          <li>
                            <a href="#" class="media mar-btm">
                              <span class="badge badge-success pull-right">90%</span>
                              <div class="media-left">
                                <i class="demo-pli-data-settings icon-2x"></i>
                              </div>
                              <div class="media-body">
                                <p class="text-semibold text-main mar-no">Data Backup</p>
                                <small class="text-muted">This is the item
                                  description</small>
                              </div>
                            </a>
                          </li>
                          <li>
                            <a href="#" class="media mar-btm">
                              <div class="media-left">
                                <i class="demo-pli-support icon-2x"></i>
                              </div>
                              <div class="media-body">
                                <p class="text-semibold text-main mar-no">Support</p>
                                <small class="text-muted">This is the item
                                  description</small>
                              </div>
                            </a>
                          </li>
                          <li>
                            <a href="#" class="media mar-btm">
                              <div class="media-left">
                                <i class="demo-pli-computer-secure icon-2x"></i>
                              </div>
                              <div class="media-body">
                                <p class="text-semibold text-main mar-no">Security</p>
                                <small class="text-muted">This is the item
                                  description</small>
                              </div>
                            </a>
                          </li>
                          <li>
                            <a href="#" class="media mar-btm">
                              <div class="media-left">
                                <i class="demo-pli-map-2 icon-2x"></i>
                              </div>
                              <div class="media-body">
                                <p class="text-semibold text-main mar-no">Location</p>
                                <small class="text-muted">This is the item
                                  description</small>
                              </div>
                            </a>
                          </li>
                        </ul>
                      </div>
                      <div class="col-sm-12 col-md-3">
                        <p class="dropdown-header"><i class="demo-pli-file-jpg icon-lg icon-fw"></i>
                          Gallery</p>
                        <div class="row img-gallery">
                          <div class="col-xs-4">
                            <img class="img-responsive" src="img/thumbs/img-1.jpg" alt="thumbs">
                          </div>
                          <div class="col-xs-4">
                            <img class="img-responsive" src="img/thumbs/img-3.jpg" alt="thumbs">
                          </div>
                          <div class="col-xs-4">
                            <img class="img-responsive" src="img/thumbs/img-2.jpg" alt="thumbs">
                          </div>
                          <div class="col-xs-4">
                            <img class="img-responsive" src="img/thumbs/img-4.jpg" alt="thumbs">
                          </div>
                          <div class="col-xs-4">
                            <img class="img-responsive" src="img/thumbs/img-6.jpg" alt="thumbs">
                          </div>
                          <div class="col-xs-4">
                            <img class="img-responsive" src="img/thumbs/img-5.jpg" alt="thumbs">
                          </div>
                        </div>
                        <a href="#" class="btn btn-block btn-primary">Browse Gallery</a>
                      </div>
                    </div>
                  </div>
                </li> --}}
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End mega dropdown-->



                <!--Notification dropdown-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                {{-- <li class="dropdown">
                  <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                    <i class="demo-pli-bell"></i>
                    <span class="badge badge-header badge-danger"></span>
                  </a>


                  <!--Notification dropdown menu-->
                  <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                    <div class="nano scrollable">
                      <div class="nano-content">
                        <ul class="head-list">
                          <li>
                            <a href="#" class="media add-tooltip" data-title="Used space : 95%" data-container="body"
                              data-placement="bottom">
                              <div class="media-left">
                                <i class="demo-pli-data-settings icon-2x text-main"></i>
                              </div>
                              <div class="media-body">
                                <p class="text-nowrap text-main text-semibold">HDD is full
                                </p>
                                <div class="progress progress-sm mar-no">
                                  <div style="width: 95%;" class="progress-bar progress-bar-danger">
                                    <span class="sr-only">95% Complete</span>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </li>
                          <li>
                            <a class="media" href="#">
                              <div class="media-left">
                                <i class="demo-pli-file-edit icon-2x"></i>
                              </div>
                              <div class="media-body">
                                <p class="mar-no text-nowrap text-main text-semibold">Write
                                  a
                                  news article</p>
                                <small>Last Update 8 hours ago</small>
                              </div>
                            </a>
                          </li>
                          <li>
                            <a class="media" href="#">
                              <span class="label label-info pull-right">New</span>
                              <div class="media-left">
                                <i class="demo-pli-speech-bubble-7 icon-2x"></i>
                              </div>
                              <div class="media-body">
                                <p class="mar-no text-nowrap text-main text-semibold">
                                  Comment
                                  Sorting</p>
                                <small>Last Update 8 hours ago</small>
                              </div>
                            </a>
                          </li>
                          <li>
                            <a class="media" href="#">
                              <div class="media-left">
                                <i class="demo-pli-add-user-star icon-2x"></i>
                              </div>
                              <div class="media-body">
                                <p class="mar-no text-nowrap text-main text-semibold">New
                                  User
                                  Registered</p>
                                <small>4 minutes ago</small>
                              </div>
                            </a>
                          </li>
                          <li>
                            <a class="media" href="#">
                              <div class="media-left">
                                <img class="img-circle img-sm" alt="Profile Picture" src="img/profile-photos/9.png">
                              </div>
                              <div class="media-body">
                                <p class="mar-no text-nowrap text-main text-semibold">Lucy
                                  sent
                                  you a message</p>
                                <small>30 minutes ago</small>
                              </div>
                            </a>
                          </li>
                          <li>
                            <a class="media" href="#">
                              <div class="media-left">
                                <img class="img-circle img-sm" alt="Profile Picture" src="img/profile-photos/3.png">
                              </div>
                              <div class="media-body">
                                <p class="mar-no text-nowrap text-main text-semibold">
                                  Jackson
                                  sent you a message</p>
                                <small>40 minutes ago</small>
                              </div>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>

                    <!--Dropdown footer-->
                    <div class="pad-all bord-top">
                      <a href="#" class="btn-link text-main box-block">
                        <i class="pci-chevron chevron-right pull-right"></i>Show All Notifications
                      </a>
                    </div>
                  </div>
                </li> --}}
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End notifications dropdown-->



                <!--User dropdown-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li id="dropdown-user" class="dropdown">
                  <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                    <span class="ic-user pull-right">
                      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                      <!--You can use an image instead of an icon.-->
                      <!--<img class="img-circle img-user media-object" src="img/profile-photos/1.png" alt="Profile Picture">-->
                      <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                      <i class="demo-pli-male"></i>
                    </span>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--You can also display a user name in the navbar.-->
                    <!--<div class="username hidden-xs">Aaron Chavez</div>-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                  </a>


                  <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                    <ul class="head-list">
                      {{-- <li>
                        <a href="#"><i class="demo-pli-male icon-lg icon-fw"></i> Profile</a>
                      </li>
                      <li>
                        <a href="#"><span class="badge badge-danger pull-right">9</span><i
                            class="demo-pli-mail icon-lg icon-fw"></i> Messages</a>
                      </li>
                      <li>
                        <a href="#"><span class="label label-success pull-right">New</span><i
                            class="demo-pli-gear icon-lg icon-fw"></i> Settings</a>
                      </li>
                      <li>
                        <a href="#"><i class="demo-pli-computer-secure icon-lg icon-fw"></i> Lock
                          screen</a>
                      </li> --}}
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
                </li>
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
            <breadcrumb></breadcrumb>
            <keep-alive>
              <router-view></router-view>
            </keep-alive>
          </div>
          <!--===================================================-->
          <!--END CONTENT CONTAINER-->

          <!--MAIN NAVIGATION-->
          <!--===================================================-->
          <nav id="mainnav-container">
            <div id="mainnav">


              <!--OPTIONAL : ADD YOUR LOGO TO THE NAVIGATION-->
              <!--It will only appear on small screen devices.-->
              <!--================================
                        <div class="mainnav-brand">
                            <a href="index.html" class="brand">
                                <img src="img/logo.png" alt="Nifty Logo" class="brand-icon">
                                <span class="brand-text">Nifty</span>
                            </a>
                            <a href="#" class="mainnav-toggle"><i class="pci-cross pci-circle icon-lg"></i></a>
                        </div>
                        -->



              <!--Menu-->
              <!--================================-->
              <div id="mainnav-menu-wrap">
                <div class="nano">
                  <div class="nano-content">

                    <!--Profile Widget-->
                    <!--================================-->
                    <div id="mainnav-profile" class="mainnav-profile">
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
                        {{-- <a href="#" class="list-group-item">
                          <i class="demo-pli-male icon-lg icon-fw"></i> View Profile
                        </a>
                        <a href="#" class="list-group-item">
                          <i class="demo-pli-gear icon-lg icon-fw"></i> Settings
                        </a>
                        <a href="#" class="list-group-item">
                          <i class="demo-pli-information icon-lg icon-fw"></i> Help
                        </a> --}}

                        <a href="javascript:;" @click="togglechangePassMdl(true)" class="list-group-item">
                          <i class="demo-pli-gear icon-lg icon-fw"></i> Change Password
                        </a>
                        <a href="#" class="list-group-item">
                          <i class="demo-pli-unlock icon-lg icon-fw"></i> Logout
                        </a>
                      </div>
                    </div>


                    <!--Shortcut buttons-->
                    <!--================================-->
                    <div id="mainnav-shortcut" class="hidden">
                      <ul class="list-unstyled shortcut-wrap">
                        <li class="col-xs-3" data-content="My Profile">
                          <a class="shortcut-grid" href="#">
                            <div class="icon-wrap icon-wrap-sm icon-circle bg-mint">
                              <i class="demo-pli-male"></i>
                            </div>
                          </a>
                        </li>
                        <li class="col-xs-3" data-content="Messages">
                          <a class="shortcut-grid" href="#">
                            <div class="icon-wrap icon-wrap-sm icon-circle bg-warning">
                              <i class="demo-pli-speech-bubble-3"></i>
                            </div>
                          </a>
                        </li>
                        <li class="col-xs-3" data-content="Activity">
                          <a class="shortcut-grid" href="#">
                            <div class="icon-wrap icon-wrap-sm icon-circle bg-success">
                              <i class="demo-pli-thunder"></i>
                            </div>
                          </a>
                        </li>
                        <li class="col-xs-3" data-content="Lock Screen">
                          <a class="shortcut-grid" href="#">
                            <div class="icon-wrap icon-wrap-sm icon-circle bg-purple">
                              <i class="demo-pli-lock-2"></i>
                            </div>
                          </a>
                        </li>
                      </ul>
                    </div>
                    <!--================================-->
                    <!--End shortcut buttons-->


                    <ul id="mainnav-menu" class="list-group">

                      <!--Category name-->
                      <li class="list-header">Navigation</li>

                      <!--Menu list item-->
                      {{-- <li class="active-sub">
                        <a href="#">
                          <i class="demo-pli-home"></i>
                          <span class="menu-title">Dashboard</span>
                          <i class="arrow"></i>
                        </a>

                        <!--Submenu-->
                        <ul class="collapse in">
                          <li><a href="index.html">Dashboard 1</a></li>
                          <li class="active-link"><a href="dashboard-2.html">Dashboard 2</a></li>
                          <li><a href="dashboard-3.html">Dashboard 3</a></li>

                        </ul>
                      </li> --}}

                      <router-link tag="li" class="nav-item" to="dashboard"><a href="#">
                          <i class="fa fa-home"></i>
                          <span class="menu-title">Dashboard</span>
                        </a></router-link>

                      @if(Auth::user()->usertype_id == 1)
                      <!--Menu list item-->
                      <li>
                        <a href="#">
                          <i class="demo-pli-data-settings icon-lg"></i>
                          <span class="menu-title">Setup</span>
                          <i class="arrow"></i>
                        </a>

                        <!--Submenu-->
                        <ul class="collapse">
                          <router-link tag="li" to="/location">
                            <a href="javascript:;">Location (App Users)</a>
                          </router-link>
                          {{-- <router-link tag="li" to="/backend_setup">
                            <a href="javascript:;">Item Setup by Backend</a>
                          </router-link> --}}
                          <router-link tag="li" to="/users">
                            <a href="javascript:;">Web Users</a>
                          </router-link>
                          <router-link tag="li" to="/vendor_masterfile">
                            <a href="javascript:;">Vendor Masterfile</a>
                          </router-link>
                          <router-link tag="li" to="/category">
                            <a href="javascript:;">Item Department</a>
                          </router-link>
                        </ul>
                      </li>

                      <!--Menu list item-->
                      <li>
                        <a href="#">
                          <i class="demo-pli-upload-to-cloud icon-lg" style="font-size: 17px"></i>
                          <span class="menu-title ">Uploading Valuation Report (NAV)</span>
                          <i class="arrow"></i>
                        </a>

                        <ul class="collapse">
                          <li>
                            <a href="/nav_upload">Posted</a>
                          </li>
                          <li>
                            <a href="/pos_unposted">Unposted</a>
                          </li>
                        </ul>
                      </li>
                      @endif

                      <!--Menu list item-->
                      <router-link tag="li" class="nav-item" to="/backend_setup">
                        <a href="javascript:;">
                          <i class="demo-pli-gear"></i>
                          <span class="menu-title">Not Found Items Setup</span>
                        </a>
                      </router-link>
                      <!--Menu list item-->
                      <router-link tag="li" class="nav-item" to="/questionable_items"
                        style="color: #25476a; font-weight: 700;">
                        <a href=" javascript:;">
                          <i class="demo-pli-gear"></i>
                          <span class="menu-title" style="color: #25476a; font-weight: 700;">Questionable Items
                            Setup</span>
                        </a>
                      </router-link>



                      <!--Menu list item-->
                      <li>
                        <a href="#">
                          <i class="demo-pli-file-html"></i>
                          <span class="menu-title">Reports</span>
                          <i class="arrow"></i>
                        </a>

                        <!--Submenu-->
                        <ul class="collapse">
                          {{-- <router-link tag="li" to="/inventory_valuation_variance">
                            <a href="javascript:;">Inventory Valuation w/ Variances</a>
                          </router-link> --}}
                          <router-link tag="li" to="/physical_count">
                            <a href="javascript:;">Actual Count (APP)</a>
                          </router-link>
                          <router-link tag="li" to="/advance_count">
                            <a href="javascript:;">Advance Count (APP)</a>
                          </router-link>
                          {{-- <router-link tag="li" to="/damage_count">
                            <a href="javascript:;">Actual Count (APP) for <span class="text-bold">Damages</span></a>
                          </router-link> --}}
                          {{-- <router-link tag="li" to="/not_in_count">
                            <a href="javascript:;">Negative Variance Report</a>
                          </router-link> --}}
                          <router-link tag="li" to="/consolidate_adv_actual_count">
                            <a href="javascript:;">Consolidate Advance and Actual Count</a>
                          </router-link>
                          @if(Auth::user()->usertype_id == 1)
                          <router-link tag="li" to="/inventory_valuation_variance">
                            <a href="javascript:;">Inventory Valuation w/ Variances</a>
                          </router-link>
                          @endif
                          <router-link tag="li" to="/variance_report">
                            <a href="javascript:;">Consolidated Variance Report</a>
                          </router-link>
                          {{-- <router-link tag="li" to="/variance_report_cost">
                            <a href="javascript:;">Consolidated Variance Report /w Cost</a>
                          </router-link> --}}
                          {{-- <router-link tag="li" to="/consolidate_report">
                            <a href="javascript:;">Consolidated Reports</a>
                          </router-link>
                          <router-link tag="li" to="/consolidate_report_cost">
                            <a href="javascript:;">Consolidated Reports w/ Cost</a>
                          </router-link> --}}
                        </ul>
                      </li>


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



        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">

          <!-- Visible when footer positions are fixed -->
          <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
          <div class="show-fixed pad-rgt pull-right">
            You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending action.</a>
          </div>



          <!-- Visible when footer positions are static -->
          <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
          {{-- <div class="hide-fixed pull-right pad-rgt">
            14GB of <strong>512GB</strong> Free.
          </div> --}}



          <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
          <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
          <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

          <p class="pad-lft">&#0169; 2021 Inventory Count Consolidation System</p>



        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->


        <!-- SCROLL PAGE BUTTON -->
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
    <!--===================================================-->
    <!-- END OF CONTAINER -->





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

  <!-- Mirrored from www.themeon.net/nifty/v2.9.1/dashboard-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 24 Oct 2019 07:34:50 GMT -->

</html>