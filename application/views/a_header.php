<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title>Telkomsel | RCM</title>
    <link rel="icon" href="<?php echo base_url()?>assets/images/icon/logo-swagger.png">

    <!-- Fontfaces CSS-->


    <link href="<?php echo base_url()?>assets/css/font-face.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?php echo base_url()?>assets/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?php echo base_url()?>assets/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?php echo base_url()?>assets/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link rel="stylesheet" type="text/css" href="../assets/css/multi-select.css">

    <!-- Main CSS-->
    <link href="<?php echo base_url()?>assets/css/theme.css" rel="stylesheet" media="all">

    <script src="<?php echo base_url()?>assets/vendor/jquery-3.2.1.min.js"></script>

</head>

<body class="">
  <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                       <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li>
							  <a href="<?php echo base_url('activity-log')?>">
								  <i class="fas fa-search"></i>Activity Log
							  </a>
						  </li>
              <?php
              $menu = raid_list();
              if(raid_list()!=[]){
                foreach($menu as $value) {
                  $metadata=[
                    'value'=>$value
                  ];
                  $this->load->view('menu_module/menu',$metadata);
                }
              }else{
                echo "<li>*Menu Not Found*</li>";
              }

              ?>
						  <li>
							  <a href="<?php echo base_url('about')?>">
								  <i class="far fa-question-circle"></i>About</a>
						  </li>
						  <li>
							  <a href="#">
								  <i class="far fa-check-square"></i>Guide</a>
						  </li>
						</ul>
                </div>
            </nav>
        </header>
  <!-- MENU SIDEBAR-->
  <aside class="menu-sidebar d-none d-lg-block">
      <div class="logo" style="background:#333">
          <img src="<?php echo base_url()?>/assets/images/icon/raid-white.png" class="img-fluid" alt="Responsive image" width="20%" style="margin-right:10px">
          <h2 style="color: #87BE3F;font-size:19px">
              REST Client Master
          </h2>
      </div>
      <div class="menu-sidebar__content js-scrollbar1">
          <nav class="navbar-sidebar">
              <ul class="list-unstyled navbar__list">

                  <li>
                      <a href="<?php echo base_url('activity-log')?>">
                          <i class="fas fa-search"></i>Activity Log
                      </a>
                  </li>
                  <hr style="margin-bottom:unset">
                  <small>API Services </small>
                  <?php
                  $menu = raid_list();
                  if(raid_list()!=[]){
                    foreach($menu as $value) {
                      $metadata=[
                        'value'=>$value
                      ];
                      $this->load->view('menu_module/menu',$metadata);
                    }
                  }else{
                    echo "<li>* No Service Available *</li>";
                  }

                  ?>

                  <hr style="margin-bottom:unset">
                  <small>RCM Workbench </small>

                  <li class="has-sub">
                      <a class="js-arrow" href="#">
                          <i class="fas fa-cubes"></i>Component</a>
                      <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="<?php echo base_url('CM_Main/lookup_member')?>">Lookup Member</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('CM_Main/lookup_group')?>">Lookup Group</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('CM_Main/input_group')?>">Input Group</a>
                        </li>
                      </ul>
                  </li>
                  <li class="has-sub">
                      <a class="js-arrow" href="#">
                          <i class="fas fa-box"></i>Payload</a>
                      <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="<?php echo base_url('PM_Main/req_payload')?>">Request Payload</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('PM_Main/menu_member')?>">Menu Member</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('PM_Main/menu_group')?>">Menu Group</a>
                        </li>
                      </ul>
                  </li>
                  <li class="has-sub">
                      <a class="js-arrow" href="#">
                          <i class="fas fa-sync"></i>Request</a>
                      <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li>
                            <a href="<?php echo base_url('RM_Main/fltr')?>">Filter Key</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('RM_Main/stval')?>">Static Value</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('RM_Main/dyval')?>">Dynamic Value</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('RM_Main/data')?>">[-d] Data Parameter</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('RM_Main/data_tmplt')?>">[-d] Data Template</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('RM_Main/add_path')?>">URL Additional Path</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('RM_Main/url')?>">URL</a>
                        </li>
                      </ul>
                  </li>
                  <li>
                      <a href="<?php echo base_url('UM_Main/usr_mgmt')?>">
                          <i class="fas fa-users"></i>User Access Mgmt
                      </a>
                  </li>
				  <hr style="margin-bottom:unset">
				  <li>
                      <a href="<?php echo base_url('about')?>">
                          <i class="far fa-question-circle"></i>About RCM</a>
                  </li>
                  <!--<li class="active">
                      <a href="form.html">
                          <i class="far fa-check-square"></i>Help</a>
                  </li>-->
              </ul>
          </nav>
      </div>
  </aside>
  <!-- END MENU SIDEBAR-->
<!-- PAGE CONTAINER-->
<div class="page-container">
    <!-- HEADER DESKTOP-->
    <header class="header-desktop" style="background:#333">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="header-wrap">
                    <?php if(isset($search_form)){
                      if($this->session->userdata('field')=='act') $act = 'selected';
                      elseif($this->session->userdata('field')=='username') $username = 'selected';
                      elseif($this->session->userdata('field')=='req') $req = 'req';
                      elseif($this->session->userdata('field')=='res') $res = 'res';
                      else{
                        $act      = '';
                        $username = '';
                        $req      = '';
                        $res      = '';
                      }

                      ?>
                    <form class="form-header" action="<?php echo base_url('activity-log')?>" method="get">
                        <select name="search_col" id="select" class="form-control" style="height: auto;">
                            <option value="act">Activity</option>
                            <option value="username" selected>User</option>
                            <option value="req">Req Payload</option>
                            <option value="res">Res Payload</option>
                        </select>
                        <input class="au-input au-input--xl" type="text" value="<?php echo $this->session->userdata('val')?>" name="search_in" placeholder="Search" />
                        <button class="au-btn--submit" type="submit" name="search">
                            <i class="zmdi zmdi-search"></i>
                        </button>
                    </form>
                    <?php } else{?>
                    <form class="form-header" action="" method="">
                        <input class="au-input au-input--xl" type="text" name="search" placeholder="Search is unavailable this page" />
                        <button class="au-btn--submit" type="submit">
                            <i class="zmdi zmdi-search"></i>
                        </button>
                    </form>
                    <?php }?>
                    <div class="header-button">
                        <div class="account-wrap">
                            <div class="account-item clearfix js-item-menu">
                                <div class="content">
                                  <a class="js-acc-btn" href="#" style="color:#87BE3F"><?php echo $this->session->userdata('name')?></a>
                                </div>
                                <div class="account-dropdown js-dropdown">
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                            <i class="zmdi zmdi-account"></i>Account</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="#">
                                            <i class="zmdi zmdi-settings"></i>Setting</a>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__footer">
                                        <a href="<?php echo base_url('logout')?>">
                                        <i class="zmdi zmdi-power"></i>Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- HEADER DESKTOP-->
