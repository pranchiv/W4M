<?php 
include('includes/dbconn.php');
$db = new dbconn();

if(!isset($_SESSION['user_id']) || $_SESSION['userType']!='driver')
{
	?>
	<script>
    document.location="index.php";
    </script>
	<?php
}

$dateToday=date('l');

$reQid=$_REQUEST['reqrec'];
$reqData=$db->getRows('donatefood',array('where'=>array('id'=>$reQid),'return_type'=>'single'));
$pickupTime=date('Y-m-d').' '.$reqData['hrdata'].':'.$reqData['minuteData'].$reqData['amORpm'];
$strpickup=strtotime($pickupTime);
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
  <h2>Available Receiver List</h2>
  	<table>
	<tr>
        <th>Recipient Name</th>
        <th>Address</th>
        <th>Open Till</th>
        <th>Phone</th>
        <th>Email</th>
    </tr>
    <?php
	$restaurantID=array();
    $recipientData=$db->getRows('donoropentime',array('dayName'=>$dateToday));
	foreach($recipientData as $recData)
	{
		$tillTime=date('Y-m-d').' '.$recData['tohr'].':'.$recData['tomin'].$recData['totiming'];
		$strlastTime=strtotime($tillTime);//$timeNow=time();
		//echo $strlastTime.'>'.$strpickup.'<br/>';
		if($strlastTime>$strpickup)
		{
			if(!in_array($recData['restaurantId'],$restaurantID))
			{
				$DonorRec=$db->getRows('userregister',array('where'=>array('id'=>$recData['restaurantId']),'return_type'=>'single'));
				?>
				<tr>
					<td><?=$DonorRec['orgName']?></td>
					<td><?php echo $DonorRec['streetAddress'].', '.$DonorRec['city'].', '.$DonorRec['state'].'-'.$DonorRec['zipCode'];?></td>
					<td><?php echo $recData['tohr'].':'.$recData['tomin'].$recData['totiming'];?></td>
					<td><?=$DonorRec['mobile']?></td>
					<td><?=$DonorRec['email']?></td>
				</tr>
				<?php
				array_push($restaurantID,$recData['restaurantId']);
			}
		}
	}
	?>
</table>
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