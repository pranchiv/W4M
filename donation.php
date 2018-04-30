<?php 
include('includes/dbconn.php');
$db = new dbconn();
if(!isset($_SESSION['user_id']) || $_SESSION['userType']!='donor')
{
	?>
	<script>
    document.location="index.php";
    </script>
	<?php
}
/* REQUEST FOR AMERICAN REDCROSS SHELTER */

$curtstamp=time();
$allopendonations=$db->getRows('donatefood',array('where'=>array('foodStatus'=>'open')));
foreach($allopendonations as $donateData)
{
	$strconversion=strtotime($donateData['addDate']);
	$strconversionTot=$strconversion+(30*60);
	if($curtstamp>$strconversionTot)
	{
		$userData = array(
			'foodStatus' => 'hold',
			'receiverId' => 27
		);
		$updtID=array('id'=>$donateData['id']);
		$update_id = $db->update('donatefood',$userData,$updtID);
	}
}

/* REQUEST FOR AMERICAN REDCROSS SHELTER */
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
  <h2 class="headclass">Donate Food</h2>
  	<div class="form-data" id="contentDiv">
    	<input type="hidden" name="hideID" id="hideID" value="<?=$_SESSION['user_id']?>" />
    	<label>
            <span>Date : </span>
            <input type="text" name="crntDate" id="crntDate" readonly value="<?=date('m/d/Y');?>" />
            <span>Time : </span>
            <input type="text" name="crntTime" id="crntTime" readonly value="<?=date('H:iA');?>" />
        </label><br/>
        <label>
            <span>Preferred Type of Donation : </span>
            <div><input type="checkbox" name="pDon[]" id="pDon" value="Hot" />&nbsp;&nbsp; Hot</div>
            <div><input type="checkbox" name="pDon[]" id="pDon1" value="Cold" />&nbsp;&nbsp; Cold</div> 
            <div><input type="checkbox" name="pDon[]" id="pDon2" value="Canned" />&nbsp;&nbsp; Canned</div>
            <div><input type="checkbox" name="pDon[]" id="pDon3" value="Trays" />&nbsp;&nbsp; Trays</div>
            <div><input type="checkbox" name="pDon[]" id="pDon4" value="Soup/Juices" />&nbsp;&nbsp; Soup/Juices</div> 
        </label><br/>
        <label>
            <span>No. of Boxes : </span>
            <input type="number" required name="numBox" id="numBox" placeholder="Unit : boxes" />
        </label><br/>
        <label>
            <span>Approximate Weight : </span>
            <input type="number" required name="appWght" id="appWght" placeholder="Unit : lbs" />
        </label><br/>
        <label>
            <span>Pick Up Before : </span>
            <div class="pickupdate">
            <select name="hrData" id="hrData">
                <option value="">- -</option>
                <?php
                for($i=0;$i<13;$i++)
                {
                    ?>
                    <option value="<?=$i?>"><?=$i?></option>
                    <?php
                }
                ?>
            </select>&nbsp;&nbsp;
            <select name="minData" id="minData">
                <option value="">- -</option>
                	<option value="00">00</option>
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                <?php
                /*for($i=0;$i<60;$i++)
                {
                    ?>
                    <option value="<?=$i?>"><?=$i?></option>
                    <?php
                }*/
                ?>
            </select>&nbsp;&nbsp;
            <select name="dayNight" id="dayNight">
                <option value="">AM / PM</option>
                <option value="AM">AM</option>
                <option value="PM">PM</option>
            </select>
            </div>
        </label><br/>
        <label>
        	<input type="reset" name="resetform" class="btn_class" value="Cancel" />
            <button onClick="sendDonate()" class="btn_class" name="sbt">Donate</button>
        </label>
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
function sendDonate() 
{
	var checkedNum = $('input[name="pDon[]"]:checked').length;
	if (checkedNum>0) {
		// User didn't check any checkboxes
		var val = [];
		$(':checkbox:checked').each(function(i){
		  val[i] = $(this).val();
		});
	}
	
	hideID=document.getElementById("hideID").value;
	hrData=document.getElementById("hrData").value;
	minData=document.getElementById("minData").value;
	dayNight=document.getElementById("dayNight").value;
	numBox=document.getElementById("numBox").value;
	appWght=document.getElementById("appWght").value;
	
	
	opttype='donateFood';
	if(checkedNum>0 && numBox!='' && appWght!='' && hrData!='')
	{
		document.getElementById("popload").style.display="block";
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("contentDiv").innerHTML = this.responseText;
			document.getElementById("popload").style.display="none";
			document.location="restaurantorderlist.php";
		}};
		xhttp.open("GET", "ajaxSubhadip.php?hideID="+hideID+"&minutedata="+minData+"&timing="+dayNight+"&pdon="+val+"&nbox="+numBox+"&appwght="+appWght+"&pickbefore="+hrData+"&actiontype="+opttype, true);
		xhttp.send();
	}
	else
	{
		alert("Please select all the form fields properly.");
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