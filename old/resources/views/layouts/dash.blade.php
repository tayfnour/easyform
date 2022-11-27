<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NewDash MHT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />




    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dash.css')}}">
    @livewireStyles
</head>
<body>
    
<div class="page-wrapper chiller-theme toggled">
    <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
      <i class="fas fa-bars"></i>
    </a>
    <nav id="sidebar" class="sidebar-wrapper">
      <div class="sidebar-content">
        <div class="sidebar-brand">
          <a href="#">pro sidebar</a>
          <div id="close-sidebar">
            <i class="fas fa-times"></i>
          </div>
        </div>
        <div class="sidebar-header">
          <div class="user-pic">
            <img class="img-responsive img-rounded" src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg" alt="User picture">
          </div>
          <div class="user-info">
            <span class="user-name">Jhon
              <strong>Smith</strong>
            </span>
            <span class="user-role">Administrator</span>
            <span class="user-status">
              <i class="fa fa-circle"></i>
              <span>Online</span>
            </span>
          </div>
        </div>
        <!-- sidebar-header  -->
        <div class="sidebar-search">
          <div>
            <div class="input-group">
              <input type="text" class="form-control search-menu" placeholder="Search...">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
        <!-- sidebar-search  -->
        <div class="sidebar-menu">
          <ul>
            <li class="header-menu">
              <span>General</span>
            </li>
            <li class="sidebar-dropdown">
              <a href="/ar">
                <i class="fa fa-tachometer-alt"></i>
                <span>New Post</span>
                <span class="badge badge-pill badge-warning">New</span>
              </a>             
            </li>
            <li class="sidebar-dropdown">
              <a href="/db">
                <i class="fa fa-shopping-cart"></i>
                <span>Db Manager</span>
                <span class="badge badge-pill badge-danger">3</span>
              </a>              
            </li>
            <li class="sidebar-dropdown">
                <a href="/d">
                  <i class="fa fa-shopping-cart"></i>
                  <span>Product Tree</span>
                  <span class="badge badge-pill badge-danger">3</span>
                </a>              
            </li>
          
            <li class="sidebar-dropdown">
              <a href="#">
                <i class="fa fa-globe"></i>
                <span>Maps</span>
              </a>
              <div class="sidebar-submenu">
                <ul>
                  <li>
                    <a href="#">Google maps</a>
                  </li>
                  <li>
                    <a href="#">Open street map</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="header-menu">
              <span>Extra</span>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-book"></i>
                <span>Documentation</span>
                <span class="badge badge-pill badge-primary">Beta</span>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-calendar"></i>
                <span>Calendar</span>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-folder"></i>
                <span>Examples</span>
              </a>
            </li>
          </ul>
        </div>
        <!-- sidebar-menu  -->
      </div>
      <!-- sidebar-content  -->
      <div class="sidebar-footer">
        <a href="#">
          <i class="fa fa-bell"></i>
          <span class="badge badge-pill badge-warning notification">3</span>
        </a>
        <a href="#">
          <i class="fa fa-envelope"></i>
          <span class="badge badge-pill badge-success notification">7</span>
        </a>
        <a href="#">
          <i class="fa fa-cog"></i>
          <span class="badge-sonar"></span>
        </a>
        <a href="#">
          <i class="fa fa-power-off"></i>
        </a>
      </div>
    </nav>
    <!-- sidebar-wrapper  -->
    <main class="page-content">
     

        <div class="container-fluid">
           
              <span style="font-size: 20px; color: Dodgerblue;">
                <i class="fas fa-database"></i>
               Database Managment</span>

               {{$slot}}
        </div>
  
    </main>
    <!-- page-content" -->
  </div>
  <!-- page-wrapper -->


  @livewireScripts

  <script src="{{ asset('assets/js/jquery.min.js')}}"  crossorigin="anonymous"></script>
  <script src="{{ asset('assets/js/dash.js')}}"  crossorigin="anonymous"></script>
  
</body>
</html>