<?php 
include('includes/dbconn.php');
$db = new dbconn();

if(!isset($_SESSION['user_id']) || $_SESSION['userType']!='receiver')
{
	?>
	<script>
    document.location="index.php";
    </script>
	<?php
}

$dateToday=date('Y-m-d');
$currenttimestamp=time().'<br/>';
$driverId = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html>
<head>
<title>Wheels4Meals</title><!--restaurant confirm order list-->
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
        <?php
		$query = "select * from donatefood where receiverId ='".$_SESSION['user_id']."' order by id desc";
		$fetchcat = $db->fetchQuery($query);
		if($fetchcat[0]['id']>0)
		{
		?>

	  	<h2>Receiver Order List</h2><hr>
        
        <div class="table-responsive">
        <table class="table">
        <tr>
        	<th>Delivery Confirmation(Receiver)</th>
            <th>Donor</th>
            <th>Donor Address</th>
            <th>Donor Phone</th>
            <th>Donor Email</th>
            <th>Driver</th>
            <th>Driver Email</th>
            <th>Driver Phone</th>
            <th>Food Status</th>
            <th>Preffered Food</th>
            <th>Number of Boxes</th>
            <th>Weight (in lbs)</th>
            <th>Order Date & Time</th>            
        </tr>
		<?php
		for($i=0;$i<count($fetchcat);$i++)
		{
			if($fetchcat[$i]['foodStatus']!='open')
			{
			  $fetchdri = $db->fetchQuery("select driverId from pickuporder where donorId = '".$fetchcat[$i]['restaurantId']."' ");	
			  $dridetails = $db->getRows('userregister',array("where"=>array('id'=>$fetchdri[0]['driverId']),'return_type'=>'single'));
			  $donordetails = $db->getRows('userregister',array("where"=>array('id'=>$fetchcat[0]['restaurantId']),'return_type'=>'single'));
			  //print_r($receiverdetails);
			  $driname = $dridetails['firstName'].' '.$dridetails['lastName'];
			  $driemail = $dridetails['email'];
			  $drimob = $dridetails['mobile'];
			}
			else
			{
				$driname = $driemail = $drimob = 'N/A';
			}
				?>
                 <tr>
                 	<td id="btnID<?=$i?>"><?php if($fetchcat[$i]['deliveryConf']==''){if($fetchcat[$i]['foodStatus']=='hold' || $fetchcat[$i]['foodStatus']=='confirm' || $fetchcat[$i]['foodStatus']=='delivered'){?><button class="btn_class" onClick="deliverConfirm(<?=$fetchcat[$i]['id']?>,<?=$i?>)">Confirm</button></a><?php }}else{echo $fetchcat[$i]['deliveryConf'];}?></td>
                 	<td><?=$donordetails['orgName'];?></td>
                 	<td><?php echo $donordetails['streetAddress'].', '.$donordetails['city'].','.$donordetails['state'].'-'.$donordetails['zipCode']?></td>
                    <td><?=$donordetails['mobile'];?></td>
                    <td><?=$donordetails['email'];?></td>
                    <td><?=$driname?></td>
                    <td><?=$driemail?></td>
                    <td><?=$drimob?></td>
                    <td><?=ucfirst($fetchcat[$i]['foodStatus'])?></td>
                    <td><?=$fetchcat[$i]['preferredFood']?></td>
                    <td><?=$fetchcat[$i]['numbox']?></td>
                    <td><?=$fetchcat[$i]['appweight']?></td>
                    <td><?=date('j F,Y',strtotime($fetchcat[$i]['curDate'])).' ,'.$fetchcat[$i]['curTiming'];?></td>
                 </tr>   
                <?php
		}
		?>
		</table>
        </div>
		<?php
		}
		else
		{
		?>
		<div class="alert"><span>No Data Found.</span></div>
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
function deliverConfirm(donateid,counter)
{
	actiontype='deliveryconfirmreceiver';
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		document.getElementById("btnID"+counter).innerHTML = this.responseText;
	}};
	xhttp.open("GET", "ajaxSubhadip.php?actiontype="+actiontype+"&donateid="+donateid, true);
	xhttp.send();
}
</script>
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