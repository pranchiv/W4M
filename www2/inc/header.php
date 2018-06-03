<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- Top Background Image Wrapper -->
<div class="bgded"> 
    <div class="top-bar wrapper">
    	<div class="top-bar-ins hoc clearfix">
        	<ul>
            	<?php 
				if(!isset($_SESSION['user_id']) || $_SESSION['user_id']=='')
				{
					?>
                    <li><a href="index.php">Login</a></li>
                    <li><a href="userregistration.php">Register</a></li>
                    <?php
				}
				else
				{
					?>
					<li><a class="drop" href="#">Hi <?php if(isset($_SESSION['userName'])){echo $_SESSION['userName'];}?></a>
						<ul>
                        <?php
                        if($_SESSION['userType']=='donor')
						{
							?>
                            <li><a href="donation.php">Donate</a></li>
							<li><a href="restaurantorderlist.php">Donation History</a></li>
							<?php
						}
						elseif($_SESSION['userType']=='receiver')
						{
							?>
							<li><a href="receiverconfirm.php">Confirm Request</a></li>
							<li><a href="receiverorderlist.php">History</a></li>
							<?php
						}
						elseif($_SESSION['userType']=='driver')
						{
							?>
							<li><a href="pickuprequest.php">Donation Request</a></li>
							<li><a href="confirmorderlist.php">History</a></li>
							<?php
						}
						elseif($_SESSION['userType']=='admin')
						{
							?>
							<li><a href="userlist.php">Userlist</a></li>
							<li><a href="orderlist.php">Donationlist</a></li>
							<?php
						}
						?>
						  <!--<li><a href="#">Level 1</a></li>
						  <li><a href="#">Level 2</a></li>
						  <li><a href="#">Level 3</a></li>
						  <li><a href="#">Level 4</a></li>-->
						</ul>  
					</li>
                    <li><a href="logout.php">Logout!</a></li>
					<?php
				}
				?>
            	<!--<li><a href="#">Logout!</a></li>-->
            </ul>
        </div>
    </div>
	<div class="wrapper row1 site-header">
    <header id="header" class="hoc clear"> 
      <!-- ################################################################################################ -->
      <div id="logo" class="fl_left">
        <h1><a href="index.php"><img src="images/logo.png" alt=""></a></h1>
      </div>
      <nav id="mainav" class="fl_right">
        <ul class="clear">
        <?php
		if(isset($_SESSION['user_id']) && $_SESSION['user_id']>0)
		{
			if($_SESSION['userType']=='donor')
			{
				?>
                <li><a href="donation.php">Home</a></li>
				<!--<li><a href="donation.php">Donate</a></li>-->
				<li><a href="restaurantorderlist.php">History</a></li>
				<?php
			}
			elseif($_SESSION['userType']=='receiver')
			{
				?>
				<li><a href="receiverconfirm.php">Home</a></li>
				<!--<li><a href="receiverconfirm.php">Confirm Request</a></li>-->
				<li><a href="receiverorderlist.php">History</a></li>
                <?php
			}
			elseif($_SESSION['userType']=='driver')
			{
				?>
                <li><a href="pickuprequest.php">Home</a></li>
				<!--<li><a href="pickuprequest.php">Donation Request</a></li>-->
				<li><a href="confirmorderlist.php">History</a></li>
				<?php
			}
			elseif($_SESSION['userType']=='admin')
			{
				?>
				<li><a href="userlist.php">Home</a></li>
				<!--<li><a href="userlist.php">Userlist</a></li>-->
				<li><a href="orderlist.php">Donationlist</a></li>
                <!--<li><a href="#">About Us</a></li>
            	<li><a href="#">Contact</a></li>-->
                <?php
			}
		}
		else
		{
			?>
			<!--<li><a href="index.php">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>-->
			<?php
		}
		?>
        </ul>
      </nav>
      <!-- ################################################################################################ -->
    </header>
  </div>
  <!-- ################################################################################################ -->
  <!-- ################################################################################################ -->
  <!-- ################################################################################################ -->
  <div id="pageintro" class="clear"> 
    <!-- ################################################################################################ -->
    <!--<article class="introtxt">
      <h2 class="heading">Suscipit ex aliquam</h2>
      <p>Erat volutpat vivamus et velit at risus.</p>
      <footer><a class="btn inverse" href="#">Laoreet pretium</a></footer>
    </article>-->

<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="images/demo/backgrounds/slider1.jpg" alt="">
      <div class="carousel-caption">
        <h3>$13.4 Billion</h3>
        <h4>Food Wasted in US alone</h4>
        <h4>Every year!</h4>
        <p>By Restaurants, Grocery Stores, Fast Food Outlets, Corporations</p>
      </div>
    </div>

    <div class="item">
      <img src="images/demo/backgrounds/slider2.jpg" alt="">
      <div class="carousel-caption">
        <h3>12,306,250</h3>
        <h4>Number of People that Go Hungry!!</h4>
        <h4>Every 3 Day in US.</h4>
        <p>Emergency Shelters, Food Banks, Charities try hard to serve Meals!</p>
      </div>
    </div>

    <div class="item">
      <img src="images/demo/backgrounds/slider3.jpg" alt="">
      <div class="carousel-caption">
        <h3>Bring Your ‘Wheels-4-Meals’!!</h3>
        <h4>Pick Up Donated Food.</h4>
        <h4>Deliver to Food Pantries in your Area!</h4>
        <p>Register. Get notified of Food Donations & where to deliver.</p>
      </div>
    </div>
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="fa fa-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="fa fa-chevron-right"></span>
  </a>
</div>    
      
  <!-- ################################################################################################ -->
</div>
</div>
<!-- End Top Background Image Wrapper -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->