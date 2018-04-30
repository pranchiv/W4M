<?php 
include('includes/dbconn.php');
$db = new dbconn();

if(!isset($_SESSION['user_id']) || $_SESSION['userType']!='admin')
{
	?>
	<script>
    document.location="index.php";
    </script>
	<?php
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
<?php include('inc/header.php');?>
<div class="wrapper row3">
  <main class="hoc container clear"> 
  	<h2>Order List</h2><hr>
    <ul>
    <li>Open = Request Placed</li>
    <li>Hold = Receiver Confirmed</li>
    <li>Confirm = Driver Confirmed</li>
    <li>Delivered = Delivery Done</li>
    </ul>
    <div class="table-responsive">
    <table class="table" border="1">
        <tr>
            <th>Donor</th>
            <th>Donor Address</th>
            <th>Donor Phone</th>
            <th>Donor Email</th>
            <th>Pickup Confirmation(Donor)</th>
            <th>Preffered Food</th>
            <th>No.of Boxes</th>
            <th>Weight(in lbs)</th>
            <th>Receiver</th>
            <th>Receiver Address</th>
            <th>Receiver Phone</th>
            <th>Receiver Email</th>
            <th>Delivery Confirmation(Receiver)</th>
            <th>Driver</th>            
            <th>Driver Phone</th>
            <th>Driver Email</th>
            <th>Status</th>  
            <th>Date & Time</th>         
        </tr>
        <?php
		  $fetchcat = $db->fetchQuery("select * from donatefood order by id desc");		  		
		  foreach($fetchcat as $donateData)
		  {
			$DonorRec=$db->getRows('userregister',array('where'=>array('id'=>$donateData['restaurantId']),'return_type'=>'single'));
			$ReceiverRec=$db->getRows('userregister',array('where'=>array('id'=>$donateData['receiverId']),'return_type'=>'single'));
			$DriverRec=$db->getRows('userregister',array('where'=>array('id'=>$donateData['driverId']),'return_type'=>'single'));
			$strdatem=strtotime($donateData['addDate']);
			$donationDT=date('m/d/Y H:iA',$strdatem);
		  ?>
		  <tr>
			  <td><?=$DonorRec['orgName']?></td>
			  <td><?=$DonorRec['streetAddress'].', '.$DonorRec['city'].','.$DonorRec['state'].'-'.$DonorRec['zipCode']?></td>
              <td><?=$DonorRec['mobile']?></td>
              <td><?=$DonorRec['email']?></td>
              <td><?=$donateData['pickupConf']?></td>              
			  <td><?=$donateData['preferredFood']?></td>
              <td><?=$donateData['numbox']?></td>
              <td><?=$donateData['appweight']?></td>
              <td><?=$ReceiverRec['orgName']?></td>
			  <td><?=$ReceiverRec['streetAddress'].', '.$ReceiverRec['city'].','.$ReceiverRec['state'].'-'.$ReceiverRec['zipCode']?></td>
              <td><?=$ReceiverRec['mobile']?></td>
              <td><?=$ReceiverRec['email']?></td>
              <td><?=$donateData['deliveryConf']?></td>
              <td><?=$DriverRec['firstName'].' '.$DriverRec['lastName']?></td>
              <td><?=$DriverRec['mobile']?></td>
              <td><?=$DriverRec['email']?></td>
              <td><?php echo ucfirst($donateData['foodStatus']);?></td>    
              <td><?=$donationDT?></td>  
		  </tr>
		  <?php
		  }
		?>
    </table>
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