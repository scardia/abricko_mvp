<div class="col-md-3 left_col">
	<div class="left_col scroll-view">
		<div class="navbar nav_title" style="border: 0;">
			<a class="site_title"> <img id="menu_toggle" src="images/logo121.png" alt="..." style="height: 20px; width: auto;cursor:pointer;"/> <span style="font-family:lulo-clean-w01-one-bold,sans-serif;">ABRICKO</span>
				<!--<i class="fa fa-times" id="menu_toggle" style="float: right;padding-top: 6%;"></i></span>-->
			</a>

		</div>

		<div class="clearfix"><span></div>

		<!-- menu profile quick info -->
		<div class="profile clearfix">
			<!-- <div class="profile_pic">
				<img src="images/img.jpg" alt="..." class="img-circle profile_img">
			</div> -->
			<div class="profile_info">
				<center>
					Welcome <span> <?php echo $userName ?></span>
				</center>
			</div>
		</div>
		<!-- /menu profile quick info -->
		<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
			<div class="menu_section">
				<ul class="nav side-menu">
					<li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="profile.php">Profile</a></li>
							<li><a href="changePass.php">Change Password</a></li>
							<li><a href="logout.php">Logout</a></li>
							<img scr="localhost/demo/gentelella-master/production/images/logo.png"  />
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- top navigation
<div class="top_nav">
  <div class="nav_menu">
	<nav>
	  <div class="nav toggle">
		<a id="menu_toggle"><i class="fa fa-bars"></i></a>

	  </div>

	  <ul class="nav navbar-nav navbar-right">
		<li class="">
		  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<?php // echo $userName?>
			<span class=" fa fa-angle-down"></span>
		  </a>
		  <ul class="dropdown-menu dropdown-usermenu pull-right">
			<li><a href="profile.php"> Profile</a></li>
			<li><a href="javascript:;">Help</a></li>
			<li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
		  </ul>
		</li>
	  </ul>
	</nav>
  </div>
</div>-->
