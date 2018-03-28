<?php 
include('includes/dbconn.php');
$db = new dbconn();
$dateToday=date('Y-m-d');
$currenttimestamp=time().'<br/>';
$driverId = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html>
<head>
<title>Wheels4Meals</title><!--driver confirm order list-->
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
	<div class="container low-pad-top">
        <?php
		$query = "select * from donatefood where curDate>='$dateToday' and foodStatus='Hold' and restaurantId in ( select donorId from pickuporder where driverId = '".$driverId."' )";
		$fetchcat = $db->fetchQuery($query);
		if($fetchcat[0]['id']!='')
		{
		?>
        <table>
        <tr>
            <th>Restaurant</th>
            <th>Street Address</th>
            <th>Address</th>
            <th>Preffered Food</th>
            <th>Number of Boxes</th>
            <th>Weight</th>
            <th>Order Date & Time</th>            
        </tr>
		<?php
		for($i=0;$i<count($fetchcat);$i++)
		{
			  $resdetails = $db->getRows('userregister',array("where"=>array('id'=>$fetchcat[$i]['restaurantId']),'return_type'=>'single'));
				?>
                 <tr>
                      <td><?=$resdetails['orgName']?></td>
                      <td><?=$resdetails['StreetAddress']?></td>
                      <td><?=$resdetails['city'].','.$resdetails['state'].','.$resdetails['zipCode']?></td>
                      <td><?=$fetchcat[$i]['preferredFood']?></td>
                      <td><?=$fetchcat[$i]['numbox']?></td>
                      <td><?=$fetchcat[$i]['appweight']?></td>
                      <td><?=date('j F,Y',strtotime($fetchcat[$i]['curDate'])).' ,'.$fetchcat[$i]['curTiming'];?></td>
                 </tr>   
                <?php
		}
		?>
		</table>
		<?php
		}
		else
		{
		?>
		<span>No Data Found.</span>
		<?php	
		}
		?>  
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