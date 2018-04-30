<?php 
include('includes/dbconn.php');
$db = new dbconn();
$dateToday=date('Y-m-d');
$currenttimestamp=time().'<br/>';
if($_SERVER['REQUEST_METHOD']=='POST')
{
	for($i=0;$i<count($_POST['reqID']);$i++)
	{
		$userData = array(
			'foodStatus' => 'Hold'
		);
		$updtID=array('id'=>$_POST['reqID'][$i]);
		$update_id = $db->update('donatefood',$userData,$updtID);
		
		
		$reqData = array(
			'donorId' => $_POST['restauID'][$i],
			'driverId' => $_SESSION['user_id'],
			'addDate' => date('Y-m-d H:i:s')
		);
		$insertcHold = $db->insert('pickuporder',$reqData);
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
</head>
<body id="top">
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- Top Background Image Wrapper -->
<div class="bgded overlay"> 
  <?php include('inc/header.php');?>
</div>
<!--<div class="wrapper row3">
	<div class="container no-padd">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Hold for Pick-Up</a></li>
          <li class="breadcrumb-item"><a href="#">Confirm Delivery</a></li>
          <li class="breadcrumb-item active"><a href="#">History</a></li>
        </ol>    
    </div>
</div>-->

<div class="wrapper row3">
	<div class="hoc container clear">

						<div class="tabl-row">
							<div class="conf-log">
                            	<h2>Profile pending admin approval</h2>
							</div>
						</div>
    </div>
</div>



<?php include('inc/footer.php');?>
<a id="backtotop" href="#top"><i class="fa fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="layout/scripts/jquery.min.js"></script>
<script src="layout/scripts/bootstrap.min.js"></script>
<script src="layout/scripts/jquery.backtotop.js"></script>
<script src="layout/scripts/jquery.mobilemenu.js"></script>

<!--text slider-->

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