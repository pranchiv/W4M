<?php 
include('includes/dbconn.php');
$db = new dbconn();
$msg='';
if($_SERVER['REQUEST_METHOD']=='POST')
{
	extract($_POST);
	$users = $db->getRows('userregister',array('where'=>array('email'=>$uname,'passWord'=>$passwd),'return_type'=>'single'));
	//$countuser=count($users);
	if($users['id']>0)
	{
		if($users['profileStatus']=='Y')
		{
			$_SESSION['user_id'] = $users['id'];
			$_SESSION['userType'] = $users['userType'];
			$_SESSION['userName'] =  $users['firstName'];
			/*$count = 0; foreach($users as $user)
			{ 			
				$count++;
			}*/
			if($_SESSION['userType']=='donor')
			{
				?><script>
				document.location="donation.php";
				</script><?php
			}
			elseif($_SESSION['userType']=='receiver')
			{
				?><script>
                document.location="receiverconfirm.php";
                </script><?php
			}
			elseif($_SESSION['userType']=='driver')
			{
				?><script>
				document.location="pickuprequest.php";
				</script><?php
			}
			elseif($_SESSION['userType']=='admin')
			{
				?><script>
				document.location="userlist.php";
				</script><?php
			}
		}
		else
		{
			?>
			<script>
            document.location="loginconfirmation.php";
            </script>
			<?php
		}
	}
	else
	{
		 $msg='Wrong Username or Password';;
	} 
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Wheels4Meals</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
<link href="layout/styles/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
<link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
<style>
.pop{
	display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	text-align:center;
}
.pop img{
	width:5%;
	margin-top:190px;
}
</style>
</head>
<body id="top">
<div id="popload" class="pop"><img src="images/loading-white-d.gif" /></div>
<?php include('inc/header.php');?>
<div class="wrapper row3">
  <main class="hoc container clear"> 
    <h2 class="headclass">Login</h2>
  	<div class="form-data" id="contentDiv">
    	<?php 
		if($msg!=''){ echo $msg;}
		?>
		<form action="" method="post">
		  <label>
          	<span>Email : </span>
			<input type="email" id="uname" name="uname" required />
		  </label>
		  <label>
            <span>Password : </span>
			<input type="password" id="passwd" name="passwd" required />
		  </label>
		  <label>
			<input type="submit" value="Login" class="btn_class" name="sbt" />
		  </label>
		</form>
    </div>
    <div class="clear"></div>
  </main>
</div>
<?php include('inc/footer.php');?>
<a id="backtotop" href="#top"><i class="fa fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="layout/scripts/jquery.min.js"></script>
<script src="layout/scripts/bootstrap.min.js"></script>
<script src="layout/scripts/jquery.backtotop.js"></script>
<script src="layout/scripts/jquery.mobilemenu.js"></script>
<script>
$(document).ready(function () {
    //rotation speed and timer
    var speed = 3000;
 
    var run = setInterval(rotate, speed);
    var slides = $('.slide');
    var container = $('#slides ul');
    var elm = container.find(':first-child').prop("tagName");
    var item_width = container.width();
    var previous = 'prev'; //id of previous button
    var next = 'next'; //id of next button
    slides.width(item_width); //set the slides to the correct pixel width
    container.parent().width(item_width);
    container.width(slides.length * item_width); //set the slides container to the correct total width
    container.find(elm + ':first').before(container.find(elm + ':last'));
    resetSlides();
 
 
    //if user clicked on prev button
 
    $('#buttons a').click(function (e) {
        //slide the item
 
        if (container.is(':animated')) {
            return false;
        }
        if (e.target.id == previous) {
            container.stop().animate({
                'left': 0
            }, 1500, function () {
                container.find(elm + ':first').before(container.find(elm + ':last'));
                resetSlides();
            });
        }
 
        if (e.target.id == next) {
            container.stop().animate({
                'left': item_width * -2
            }, 1500, function () {
                container.find(elm + ':last').after(container.find(elm + ':first'));
                resetSlides();
            });
        }
 
        //cancel the link behavior            
        return false;
 
    });
 
    //if mouse hover, pause the auto rotation, otherwise rotate it    
    container.parent().mouseenter(function () {
        clearInterval(run);
    }).mouseleave(function () {
        run = setInterval(rotate, speed);
    });
 
 
    function resetSlides() {
        //and adjust the container so current is in the frame
        container.css({
            'left': -1 * item_width
        });
    }
 
});
//a simple function to click next link
//a timer will call this function, and the rotation will begin
 
function rotate() {
    $('#next').click();
}
</script>
</body>
</html>