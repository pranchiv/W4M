<?php 
include('includes/dbconn.php');
$db = new dbconn();
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
<script>
function loadDoc() 
{
	zipcode=document.getElementById("zip").value;
	if(document.getElementById('utype').checked){ usertype = document.getElementById('utype').value;}
	else if(document.getElementById('utype1').checked){ usertype = document.getElementById('utype1').value;}
	else if(document.getElementById('utype2').checked){ usertype = document.getElementById('utype2').value;}
	opttype='startReg';
	if(zipcode!='' && usertype!='')
	{
		document.getElementById("popload").style.display="block";
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("contentDiv").innerHTML = this.responseText;
			document.getElementById("popload").style.display="none";
		}};
		xhttp.open("GET", "ajaxSubhadip.php?zip="+zipcode+"&utyp="+usertype+"&actiontype="+opttype, true);
		xhttp.send();
	}
	else
	{
		alert("Please select all the form fields properly.");
	}
}
</script>
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
    <h2 class="headclass">User Registration</h2>
  	<div class="form-data" id="contentDiv">
    	<label>
            <span>Select Zipcode : </span>
            <select name="zip" id="zip">
                <option value="">Select Zipcode</option>
                <?php
                $allZip=$db->getRows('allzipcodes',array('order_by'=>'zipCode ASC'));
                foreach($allZip as $zipRec)
                {
                    ?>
                    <option value="<?=$zipRec['zipCode']?>"><?=$zipRec['zipCode']?></option>
                    <?php
                }
                ?>
            </select>
        </label><br/>
        <label>
            <span>Select Usertype : </span>
            <div><input type="radio" name="utype" id="utype" value="donor" />&nbsp;&nbsp; Food Donor (Restaurant/Bakeries/Groceries)</div>
            <div><input type="radio" name="utype" id="utype1" value="receiver" />&nbsp;&nbsp; Food Pantry/Charities</div> 
            <div><input type="radio" name="utype" id="utype2" value="driver" />&nbsp;&nbsp; Volunteer Drivers</div> 
        </label><br/>
        <label>
            <button onClick="loadDoc()" name="sbt" class="btn_class">Register</button>
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
function addproducer(){
	var newcontent = $('#collapseDiv').append("<label><select name='selday[]'><option value='Sunday'>Sunday</option><option value='Monday'>Monday</option><option value='Tuesday'>Tuesday</option><option value='Wednesday'>Wednesday</option><option value='Thursday'>Thursday</option><option value='Friday'>Friday</option><option value='Saturday'>Saturday</option></select>&nbsp;&nbsp;<select name='formhr[]'><option value=''>Open From Hour</option><?php for($i=0;$i<13;$i++){?><option value='<?=$i?>'><?=$i?></option><?php }?></select>&nbsp;:&nbsp;<select name='formmin[]'><option value=''>Open From Minute</option><?php for($i=0;$i<60;$i++){?><option value='<?=$i?>'><?=$i?></option><?php }?></select>&nbsp;:&nbsp;<select name='fromtiming[]'><option value=''>Open From Timing</option><option value='AM'>AM</option><option value='PM'>PM</option></select>&nbsp;:&nbsp; - &nbsp;:&nbsp;<select name='tohr[]'><option value=''>Open Till Hour</option><?php for($i=0;$i<13;$i++){?><option value='<?=$i?>'><?=$i?></option><?php }?></select>&nbsp;:&nbsp;<select name='tomin[]'><option value=''>Open Till Minute</option><?php for($i=0;$i<60;$i++){?><option value='<?=$i?>'><?=$i?></option><?php }?></select>&nbsp;:&nbsp;<select name='totiming[]'><option value=''>Open Till Timing</option><option value='AM'>AM</option><option value='PM'>PM</option></select></label><span onclick='deletemore()'>Delete</span><br><div class='clear'></div>");
}
function deletemore(){
	$('#collapseDiv').children().last().remove();
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