<?php
include('includes/dbconn.php');
$db = new dbconn();
?>
<style>
.pop{
	display: block; /* Hidden by default */
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
<div id="popload" class="pop"><img src="images/loading-white-d.gif" /></div>
<?php
if($_SERVER['REQUEST_METHOD']=='POST')
{
	extract($_POST);
	if($extType=='donor')
	{
		$userData = array(
			'orgName' => $orgName,			
			'firstName' => $drvrFname,
			'lastName' => $drvrLname,
			'streetAddress' => $orgAdrs,
			'city' => $orgCity,
			'state' => $orgState,
			'mobile' => $orgMobile,
			'email' => $orgEmail,
			'passWord' => $orgPass
		);
		$updtID=array('id'=>$extID);
		$update_id = $db->update('userregister',$userData,$updtID);
		
		if($zip>0)
		{
			$zipData = array(
				'zipCode' => $zip
			);
			$updtZIP=array('id'=>$extID);
			$update_zip = $db->update('userregister',$zipData,$updtZIP);
		}
	}
	elseif($extType=='receiver')
	{
		$userData = array(
			'orgName' => $orgName,			
			'firstName' => $drvrFname,
			'lastName' => $drvrLname,
			'streetAddress' => $orgAdrs,
			'city' => $orgCity,
			'state' => $orgState,
			'mobile' => $orgMobile,
			'email' => $orgEmail,
			'passWord' => $orgPass
		);
		$updtID=array('id'=>$extID);
		$update_id = $db->update('userregister',$userData,$updtID);
		
		if($zip>0)
		{
			$zipData = array(
				'zipCode' => $zip
			);
			$updtZIP=array('id'=>$extID);
			$update_zip = $db->update('userregister',$zipData,$updtZIP);
		}
		
		for($i=0;$i<count($_POST['selday']);$i++)
		{
			$hourData = array(
				'restaurantId' => $extID,
				'dayName' => $_POST['selday'][$i],
				'fromhr' => $_POST['formhr'][$i],
				'frommin' => $_POST['formmin'][$i],
				'fromtiming' => $_POST['fromtiming'][$i],
				'tohr' => $_POST['tohr'][$i],
				'tomin' => $_POST['tomin'][$i],
				'totiming' => $_POST['totiming'][$i]
			);
			$insertHR = $db->insert('donoropentime',$hourData);
		}
	}
	elseif($extType=='driver')
	{
		$userData = array(
			'firstName' => $drvrFname,
			'lastName' => $drvrLname,
			'mobile' => $drvrMobile,
			'email' => $drvrEmail,
			'passWord' => $drvrPass
		);
		$updtID=array('id'=>$extID);
		$update_id = $db->update('userregister',$userData,$updtID);
		
		if($zip>0)
		{
			$zipData = array(
				'zipCode' => $zip
			);
			$updtZIP=array('id'=>$extID);
			$update_zip = $db->update('userregister',$zipData,$updtZIP);
		}
	}
	?>
    <script>
    document.location="loginconfirmation.php";
    </script>
	<?php
	
}
?>