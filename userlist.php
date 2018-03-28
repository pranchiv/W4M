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
  	<h2>Donor List</h2><hr>
    
    <div class="table-responsive" id="donorid">
    <table class="table" border="1">
        <tr>
            <th>Organisation Name</th>
            <th>Contact Name</th>
            <th>Phone No.</th>
            <th>Address</th>
            <th>Status</th>
            <th>Registration Date</th>            
        </tr>
        <?php
		  $fetchcat = $db->fetchQuery("select * from userregister where userType = 'donor' and profileStatus = 'Y'");
		  if($fetchcat[0]['id']>0)
		  {			
			  for($i=0;$i<count($fetchcat);$i++)
			  {
				  ?>
				  <tr>
				  <td><?=$fetchcat[$i]['orgName']?></td>
				  <td><?=$fetchcat[$i]['firstName'].' '.$fetchcat[$i]['lastName']?></td>
				  <td><?=$fetchcat[$i]['mobile']?></td>
				  <td><?php echo $fetchcat[$i]['streetAddress'].', '.$fetchcat[$i]['city'].','.$fetchcat[$i]['state'].'-'.$fetchcat[$i]['zipCode'];?></td>
				  <td>
				  <select onChange="changeStatus(this.value,'<?=$fetchcat[$i]['id']?>','donorid');">
				  <option value="N" <?php if($fetchcat[$i]['profileStatus']=='N'){ ?> selected <?php }?>>N</option>
				  <option value="Y" <?php if($fetchcat[$i]['profileStatus']=='Y'){ ?> selected <?php }?>>Y</option>
				  </select>
				  </td>
				  <td><?=date('j F,Y',strtotime($fetchcat[$i]['addDate']));?></td>
				  </tr>
				  <?php
			  }
			  }
		  else
		  {
			  ?>
			  <tr>
			  <td colspan="6" align="center"><div class="alert"><span>No Donors there with Status 'Y'</span></div></td>
              </tr>
			  <?php
		  }
		?>
    </table>
    
    <table class="table" border="1">
        <tr>
            <th>Organisation Name</th>
            <th>Contact Name</th>
            <th>Phone No.</th>
            <th>Address</th>
            <th>Status</th>
            <th>Registration Date</th>            
        </tr>
        <?php
		  $fetchcat = $db->fetchQuery("select * from userregister where userType = 'donor' and profileStatus = 'N'");			
		  if($fetchcat[0]['id']>0)
		  {	
			  for($i=0;$i<count($fetchcat);$i++)
			  {
				  ?>
				  <tr>
				  <td><?=$fetchcat[$i]['orgName']?></td>
				  <td><?=$fetchcat[$i]['firstName'].' '.$fetchcat[$i]['lastName']?></td>
				  <td><?=$fetchcat[$i]['mobile']?></td>
				  <td><?php echo $fetchcat[$i]['streetAddress'].', '.$fetchcat[$i]['city'].','.$fetchcat[$i]['state'].'-'.$fetchcat[$i]['zipCode'];?></td>
				  <td>
				  <select onChange="changeStatus(this.value,'<?=$fetchcat[$i]['id']?>','donorid');">
				  <option value="N" <?php if($fetchcat[$i]['profileStatus']=='N'){ ?> selected <?php }?>>N</option>
				  <option value="Y" <?php if($fetchcat[$i]['profileStatus']=='Y'){ ?> selected <?php }?>>Y</option>
				  </select>
				  </td>
				  <td><?=date('j F,Y',strtotime($fetchcat[$i]['addDate']));?></td>
				  </tr>
				  <?php
			  }
		  }
		  else
		  {
			  ?>
			  <tr>
			  <td colspan="6" align="center"><div class="alert"><span>No Donors there with Status 'N'</span></div></td>
              </tr>
			  <?php
		  }
		?>
    </table>
    </div>
    
	<h2>Driver List</h2><hr>
    
    <div class="table-responsive" id="driverid">
    <table class="table" border="1">
        <tr>
            <th>Organisation Name</th>
            <th>Contact Name</th>
            <th>Phone No.</th>
            <th>Address</th>
            <th>Status</th>
            <th>Registration Date</th>
        </tr>
        <?php
		  $fetchcat = $db->fetchQuery("select * from userregister where userType = 'driver' and profileStatus = 'Y'");			
		  if($fetchcat[0]['id']>0)
		  {			
			  for($i=0;$i<count($fetchcat);$i++)
			  {
				  ?>
				  <tr>
				  <td><?=$fetchcat[$i]['orgName']?></td>
				  <td><?=$fetchcat[$i]['firstName'].' '.$fetchcat[$i]['lastName']?></td>
				  <td><?=$fetchcat[$i]['mobile']?></td>
				  <td><?php echo $fetchcat[$i]['streetAddress'].', '.$fetchcat[$i]['city'].','.$fetchcat[$i]['state'].'-'.$fetchcat[$i]['zipCode'];?></td>
				  <td>
				  <select onChange="changeStatus(this.value,'<?=$fetchcat[$i]['id']?>','driverid');">
				  <option value="N" <?php if($fetchcat[$i]['profileStatus']=='N'){ ?> selected <?php }?>>N</option>
				  <option value="Y" <?php if($fetchcat[$i]['profileStatus']=='Y'){ ?> selected <?php }?>>Y</option>
				  </select>
				  </td>
				  <td><?=date('j F,Y',strtotime($fetchcat[$i]['addDate']));?></td>
				  </tr>
				  <?php
			  }
		  }
		  else
		  {
			  ?>
			  <tr>
			  <td colspan="6" align="center"><div class="alert"><span>No Drivers there with Status 'Y'</span></div></td>
              </tr>
			  <?php
		  }
		?>
    </table>
    
    <table class="table" border="1">
        <tr>
            <th>Organisation Name</th>
            <th>Contact Name</th>
            <th>Phone No.</th>
            <th>Address</th>
            <th>Status</th>
            <th>Registration Date</th>
        </tr>
        <?php
		  $fetchcat = $db->fetchQuery("select * from userregister where userType = 'driver' and profileStatus = 'N'");			
		  if($fetchcat[0]['id']>0)
		  {						
			  for($i=0;$i<count($fetchcat);$i++)
			  {
				  ?>
				  <tr>
				  <td><?=$fetchcat[$i]['orgName']?></td>
				  <td><?=$fetchcat[$i]['firstName'].' '.$fetchcat[$i]['lastName']?></td>
				  <td><?=$fetchcat[$i]['mobile']?></td>
				  <td><?php echo $fetchcat[$i]['streetAddress'].', '.$fetchcat[$i]['city'].','.$fetchcat[$i]['state'].'-'.$fetchcat[$i]['zipCode'];?></td>
				  <td>
				  <select onChange="changeStatus(this.value,'<?=$fetchcat[$i]['id']?>','driverid');">
				  <option value="N" <?php if($fetchcat[$i]['profileStatus']=='N'){ ?> selected <?php }?>>N</option>
				  <option value="Y" <?php if($fetchcat[$i]['profileStatus']=='Y'){ ?> selected <?php }?>>Y</option>
				  </select>
				  </td>
				  <td><?=date('j F,Y',strtotime($fetchcat[$i]['addDate']));?></td>
				  </tr>
				  <?php
			  }
		  }
		  else
		  {
			  ?>
			  <tr>
			  <td colspan="6" align="center"><div class="alert"><span>No Drivers there with Status 'N'</span></div></td>
              </tr>
			  <?php
		  }
		?>
    </table>
    </div>
    
	<h2>Beneficiary List</h2><hr>
    
    <div class="table-responsive" id="benid">
    <table class="table" border="1">
        <tr>
            <th>Organisation Name</th>
            <th>Contact Name</th>
            <th>Phone No.</th>
            <th>Address</th>
            <th>Status</th>
            <th>Registration Date</th>
        </tr>
        <?php
		  $fetchcat = $db->fetchQuery("select * from userregister where userType = 'receiver' and profileStatus = 'Y'");						
		  if($fetchcat[0]['id']>0)
		  {	
			  for($i=0;$i<count($fetchcat);$i++)
			  {
				  ?>
				  <tr>
				  <td><?=$fetchcat[$i]['orgName']?></td>
				  <td><?=$fetchcat[$i]['firstName'].' '.$fetchcat[$i]['lastName']?></td>
				  <td><?=$fetchcat[$i]['mobile']?></td>
				  <td><?php echo $fetchcat[$i]['streetAddress'].'-'.$fetchcat[$i]['city'].','.$fetchcat[$i]['state'].'-'.$fetchcat[$i]['zipCode'];?></td>
				  <td><?php if($fetchcat[$i]['id']!=27){?>
				  <select onChange="changeStatus(this.value,'<?=$fetchcat[$i]['id']?>','benid');">
				  <option value="N" <?php if($fetchcat[$i]['profileStatus']=='N'){ ?> selected <?php }?>>N</option>
				  <option value="Y" <?php if($fetchcat[$i]['profileStatus']=='Y'){ ?> selected <?php }?>>Y</option>
				  </select>
                  <?php }elseif($fetchcat[$i]['id']==27){echo "Y";}?>
				  </td>
				  <td><?=date('j F,Y',strtotime($fetchcat[$i]['addDate']));?></td>
				  </tr>
				  <?php
			  }
		  }
		  else
		  {
			  ?>
			  <tr>
			  <td colspan="6" align="center"><div class="alert"><span>No Beneficiary there with Status 'Y'</span></div></td>
              </tr>
			  <?php
		  }
		?>
    </table>
    
    <table class="table" border="1">
        <tr>
            <th>Organisation Name</th>
            <th>Contact Name</th>
            <th>Phone No.</th>
            <th>Address</th>
            <th>Status</th>
            <th>Registration Date</th>
        </tr>
        <?php
		  $fetchcat = $db->fetchQuery("select * from userregister where userType = 'receiver' and profileStatus = 'N'");									
		  if($fetchcat[0]['id']>0)
		  {
			  for($i=0;$i<count($fetchcat);$i++)
			  {
				  ?>
				  <tr>
				  <td><?=$fetchcat[$i]['orgName']?></td>
				  <td><?=$fetchcat[$i]['firstName'].' '.$fetchcat[$i]['lastName']?></td>
				  <td><?=$fetchcat[$i]['mobile']?></td>
				  <td><?php echo $fetchcat[$i]['streetAddress'].'-'.$fetchcat[$i]['city'].','.$fetchcat[$i]['state'].'-'.$fetchcat[$i]['zipCode'];?></td>
				  <td>
				  <select onChange="changeStatus(this.value,'<?=$fetchcat[$i]['id']?>','benid');">
				  <option value="N" <?php if($fetchcat[$i]['profileStatus']=='N'){ ?> selected <?php }?>>N</option>
				  <option value="Y" <?php if($fetchcat[$i]['profileStatus']=='Y'){ ?> selected <?php }?>>Y</option>
				  </select>
				  </td>
				  <td><?=date('j F,Y',strtotime($fetchcat[$i]['addDate']));?></td>
				  </tr>
				  <?php
			  }
		  }
		  else
		  {
			  ?>
			  <tr>
			  <td colspan="6" align="center"><div class="alert"><span>No Beneficiary there with Status 'N'</span></div></td>
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
function changeStatus(stat,id,divid) 
{
	if(confirm("Are you sure you want to delete this?")){
	actiontype = 'changestatus';
	$.post("ajaxSubhadip.php",{ stat : stat, id : id, actiontype : actiontype,divid : divid },function(data){
		//console.log(data);
		$("#"+divid).html(data);
	});
	}
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