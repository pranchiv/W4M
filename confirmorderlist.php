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
	<div class="container low-pad-top" id="newdata">
        <?php
		$query = "select * from donatefood where curDate>='$dateToday' and foodStatus='confirm' and driverId=$driverId";
		$fetchcat = $db->fetchQuery($query);
		?>
        <h2>Picked up donations List</h2><hr>
		<?php
		if($fetchcat[0]['id']>0)
		{
		?>
        <div class="table-responsive">
        <table class="table" border="1">
            <tr>
                <th>&nbsp;</th>
                <th>Donor</th>
                <th>Donor Address</th>
                <th>Donor Phone</th>
                <th>Donor Email</th>
                <th>Preffered Food</th>
                <th>No.of Boxes</th>
                <th>Weight(in lbs)</th>
                <th>Pickup Time</th>
                <th>Receiver</th>
                <th>Receiver Address</th>
                <th>Receiver Phone</th>
                <th>Receiver Email</th>            
            </tr>
        <?php
		$cnfcounter=1;
		$query = "select * from donatefood where curDate>='$dateToday' and foodStatus='confirm' and driverId=$driverId order by id desc";
		$donateReq = $db->fetchQuery($query);
			foreach($donateReq as $recdata)
			{
				$endtime=$dateToday.' '.$recdata['hrdata'].':'.$recdata['minuteData'].$recdata['amORpm'];
				$endstr=strtotime($endtime);
				if($endstr>$currenttimestamp)
				{
					$DonorRec=$db->getRows('userregister',array('where'=>array('id'=>$recdata['restaurantId']),'return_type'=>'single'));
					$ReceiverRec=$db->getRows('userregister',array('where'=>array('id'=>$recdata['receiverId']),'return_type'=>'single'));
					$actTimePick=date('m/d/Y H:iA');
					?>
						<!--<div class="tabl-row">
							<div class="checkbox">
								<input type="hidden" name="restauID[]" value="<?=$recdata['restaurantId']?>" />
								<input type="checkbox" class="first-chk" name="reqID[]" id="chkbx<?=$count?>" value="<?=$recdata['id']?>" />
								<h3><?php echo $DonorRec['orgName'];?></h3>
								<p><?php echo $DonorRec['streetAddress'].', '.$DonorRec['city'].'-'.$DonorRec['zipCode'];?></p>
								<p><?php echo $recdata['preferredFood'].'/'.$recdata['numbox'].'boxes/'.$recdata['appweight'].'lbs';?></p>
								<p>Pick Up Before <?php echo $recdata['hrdata'].':'.$recdata['minuteData'].''.$recdata['amORpm'];?></p>
								<p class="yes"><a href="availablerecipient.php?reqrec=<?=$recdata['id']?>">Click Here</a> See List of Available Recipients</p>
							</div>
						</div>-->
                        <input type="hidden" name="restauID[]" value="<?=$recdata['restaurantId']?>" />
                        <tr>
                        	<td><button id="btn<?=$cnfcounter?>" onClick="confirmdeliver('<?=$recdata['id']?>','newdata','<?=$driverId?>');">Delivered</button></td>
                            <td><?=$DonorRec['orgName']?></td>
                            <td><?php echo $DonorRec['streetAddress'].', '.$DonorRec['city'].','.$DonorRec['state'].'-'.$DonorRec['zipCode']?></td>
                            <td><?=$DonorRec['mobile']?></td>
                            <td><?=$DonorRec['email']?></td>
                            <td><?=$recdata['preferredFood']?></td>
                            <td><?=$recdata['numbox']?></td>
                            <td><?php if($recdata['appweight']!=''){echo $recdata['appweight'].' lbs';}?></td>
                            <td><?php echo $actTimePick;?></td>
                            <td><?=$ReceiverRec['orgName']?></td>
                            <td><?php echo $ReceiverRec['streetAddress'].', '.$ReceiverRec['city'].','.$ReceiverRec['state'].'-'.$ReceiverRec['zipCode']?></td>
                            <td><?=$ReceiverRec['mobile']?></td>
                            <td><?=$ReceiverRec['email']?></td>
                        </tr>
					<?php
					$cnfcounter++;
				}
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
        <h2>Delivered donations List</h2><hr>
        <?php
		$query = "select * from donatefood where foodStatus='delivered' and driverId = $driverId";
		$fetchcat = $db->fetchQuery($query);
		if($fetchcat[0]['id']>0)
		{
		?>
        <div class="table-responsive">
        <table class="table" border="1">
            <tr>
                <th>Donor</th>
                <th>Donor Address</th>
                <th>Donor Phone</th>
                <th>Donor Email</th>
                <th>Preffered Food</th>
                <th>No.of Boxes</th>
                <th>Weight(in lbs)</th>
                <th>Pickup Time</th>
                <th>Receiver</th>
                <th>Receiver Address</th>
                <th>Receiver Phone</th>
                <th>Receiver Email</th>            
            </tr>
        <?php
		$query = "select * from donatefood where foodStatus='delivered' and driverId=$driverId order by id desc";
		$donateReq = $db->fetchQuery($query);
			foreach($donateReq as $recdata)
			{
				$endtime=$dateToday.' '.$recdata['hrdata'].':'.$recdata['minuteData'].$recdata['amORpm'];
				$endstr=strtotime($endtime);
				if($endstr>$currenttimestamp)
				{
					$DonorRec=$db->getRows('userregister',array('where'=>array('id'=>$recdata['restaurantId']),'return_type'=>'single'));
					$ReceiverRec=$db->getRows('userregister',array('where'=>array('id'=>$recdata['receiverId']),'return_type'=>'single'));
					$actTimePick=date('m/d/Y H:iA');
					?>
                        <tr>
                            <td><?=$DonorRec['orgName']?></td>
                            <td><?php echo $DonorRec['streetAddress'].', '.$DonorRec['city'].','.$DonorRec['state'].'-'.$DonorRec['zipCode']?></td>
                            <td><?=$DonorRec['mobile']?></td>
                            <td><?=$DonorRec['email']?></td>
                            <td><?=$recdata['preferredFood']?></td>
                            <td><?=$recdata['numbox']?></td>
                            <td><?php if($recdata['appweight']!=''){echo $recdata['appweight'].' lbs';}?></td>
                            <td><?php echo $actTimePick;?></td>
                            <td><?=$ReceiverRec['orgName']?></td>
                            <td><?php echo $ReceiverRec['streetAddress'].', '.$ReceiverRec['city'].','.$ReceiverRec['state'].'-'.$ReceiverRec['zipCode']?></td>
                            <td><?=$ReceiverRec['mobile']?></td>
                            <td><?=$ReceiverRec['email']?></td>
                        </tr>
					<?php
				}
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
function confirmdeliver(order_id,divid,driverId)
{
	actiontype = 'confirmdeliver';
	$.post("ajaxSubhadip.php",{ order_id : order_id,actiontype : actiontype,driverId : driverId },function(data){
	console.log(data);
		$("#"+divid).html(data);
	});
}
</script>

</body>
</html>