<?php 
include('includes/dbconn.php');
$db = new dbconn();
$actionName = $_REQUEST['actiontype'];
$dateNow = date('Y-m-d H:i:s');
if($actionName=='startReg')
{
	$zipCode = $_REQUEST['zip'];
	$userType = $_REQUEST['utyp'];
	$userData = array(
		'zipCode' => $zipCode,
		'userType' => $userType,
		'addDate' => $dateNow
	);
	$insert_id = $db->insert('userregister',$userData);
	if($userType=='donor')
	{
		?>
        <form action="fullreg.php" method="post">
            <input type="hidden" name="extID" value="<?=$insert_id?>" />
            <input type="hidden" name="extType" value="<?=$userType?>" />
            <label>
                <span>Select Zipcode : </span>
                <select name="zip">
                    <option value="">Select Zipcode</option>
                    <?php
                    $allZip=$db->getRows('allzipcodes',array('order_by'=>'zipCode ASC'));
                    foreach($allZip as $zipRec)
                    {
                        ?>
                        <option value="<?=$zipRec['zipCode']?>" <?php if($zipCode==$zipRec['zipCode']){echo "selected";}?>><?=$zipRec['zipCode']?></option>
                        <?php
                    }
                    ?>
                </select>
            </label><br/>
            <label>
                <span>Organization Name : </span>
                <input type="text" required="required" name="orgName" id="orgName" /> 
            </label><br/>
            <label>
                <span>First Name : </span>
                <input type="text" required="required" name="drvrFname" id="drvrFname" /> 
            </label><br/>
            <label>
                <span>Last Name : </span>
                <input type="text" name="drvrLname" id="drvrLname" /> 
            </label><br/>
            <label>
                <span>Street Address : </span>
                <input type="text" required="required" name="orgAdrs" id="orgAdrs" /> 
            </label><br/>
            <label>
                <span>City : </span>
                <input type="text" required="required" name="orgCity" id="orgCity" /> 
            </label><br/>
            <label>
                <span>State : </span>
                <input type="text" required="required" name="orgState" id="orgState" /> 
            </label><br/>
            <label>
                <span>Mobile : </span>
                <input type="text" required="required" name="orgMobile" id="orgMobile" /> 
            </label><br/>
            <label>
                <span>Email : </span>
                <input type="text" required="required" name="orgEmail" id="orgEmail" /> 
            </label><br/>
            <label>
                <span>Password : </span>
                <input type="password" required="required" name="orgPass" id="orgPass" /> 
            </label><br/>
            <label>
            <span>Preferred Type of Donatioon : </span>
            <div><input type="checkbox" name="pDon[]" id="pDon" value="Hot" />&nbsp;&nbsp; Hot</div>
            <div><input type="checkbox" name="pDon[]" id="pDon1" value="Cold" />&nbsp;&nbsp; Cold </div>
            <div><input type="checkbox" name="pDon[]" id="pDon2" value="Canned" />&nbsp;&nbsp; Canned</div>
            <div><input type="checkbox" name="pDon[]" id="pDon3" value="Trays" />&nbsp;&nbsp; Trays</div>
            <div><input type="checkbox" name="pDon[]" id="pDon4" value="Soup/Juices" />&nbsp;&nbsp; Soup/Juices</div> 
        </label><br/>
        	<input type="reset" name="resetform" class="btn_class" value="Cancel" />
            <input type="submit" name="sbt" class="btn_class" value="Register" />
        </form>
        <?php
	}
	elseif($userType=='receiver')
	{
		?>
        <form action="fullreg.php" method="post">
            <input type="hidden" name="extID" value="<?=$insert_id?>" />
            <input type="hidden" name="extType" value="<?=$userType?>" />
            <label>
                <span>Select Zipcode : </span>
                <select name="zip">
                    <option value="">Select Zipcode</option>
                    <?php
                    $allZip=$db->getRows('allzipcodes',array('order_by'=>'zipCode ASC'));
                    foreach($allZip as $zipRec)
                    {
                        ?>
                        <option value="<?=$zipRec['zipCode']?>" <?php if($zipCode==$zipRec['zipCode']){echo "selected";}?>><?=$zipRec['zipCode']?></option>
                        <?php
                    }
                    ?>
                </select>
            </label><br/>
            <label>
                <span>Organization Name : </span>
                <input type="text" required="required" name="orgName" id="orgName" /> 
            </label><br/>
            <label>
                <span>First Name : </span>
                <input type="text" required="required" name="drvrFname" id="drvrFname" /> 
            </label><br/>
            <label>
                <span>Last Name : </span>
                <input type="text" name="drvrLname" id="drvrLname" /> 
            </label><br/>
            <label>
                <span>Street Address : </span>
                <input type="text" required="required" name="orgAdrs" id="orgAdrs" /> 
            </label><br/>
            <label>
                <span>City : </span>
                <input type="text" required="required" name="orgCity" id="orgCity" /> 
            </label><br/>
            <label>
                <span>State : </span>
                <input type="text" required="required" name="orgState" id="orgState" /> 
            </label><br/>
            <label>
                <span>Mobile : </span>
                <input type="text" required="required" name="orgMobile" id="orgMobile" /> 
            </label><br/>
            <label>
                <span>Email : </span>
                <input type="text" required="required" name="orgEmail" id="orgEmail" /> 
            </label><br/>
            <label>
                <span>Password : </span>
                <input type="password" required="required" name="orgPass" id="orgPass" /> 
            </label><br/>
            <label>
            <span>Preferred Type of Donatioon : </span>
           <div><input type="checkbox" name="pDon[]" id="pDon" value="Hot" />&nbsp;&nbsp; Hot</div>
            <div><input type="checkbox" name="pDon[]" id="pDon1" value="Cold" />&nbsp;&nbsp; Cold</div> 
            <div><input type="checkbox" name="pDon[]" id="pDon2" value="Canned" />&nbsp;&nbsp; Canned</div>
            <div><input type="checkbox" name="pDon[]" id="pDon3" value="Trays" />&nbsp;&nbsp; Trays</div>
            <div><input type="checkbox" name="pDon[]" id="pDon4" value="Soup/Juices" />&nbsp;&nbsp; Soup/Juices</div> 
        </label><br/>
        <label>
        	<span onclick="addproducer()">Add preferred Donation Hour</span>
        </label>
            <div id="collapseDiv"></div>
            <input type="reset" name="resetform" class="btn_class" value="Cancel" />
            <input type="submit" name="sbt" class="btn_class" value="Register" />
        </form>
        <?php
	}
	elseif($userType=='driver')
	{
		?>
        <form action="fullreg.php" method="post">
            <input type="hidden" name="extID" value="<?=$insert_id?>" />
            <input type="hidden" name="extType" value="<?=$userType?>" />
            <label>
                <span>Select Zipcode : </span>
                <select name="zip">
                    <option value="">Select Zipcode</option>
                    <?php
                    $allZip=$db->getRows('allzipcodes',array('order_by'=>'zipCode ASC'));
                    foreach($allZip as $zipRec)
                    {
                        ?>
                        <option value="<?=$zipRec['zipCode']?>" <?php if($zipCode==$zipRec['zipCode']){echo "selected";}?>><?=$zipRec['zipCode']?></option>
                        <?php
                    }
                    ?>
                </select>
            </label><br/>
            <label>
                <span>First Name : </span>
                <input type="text" required="required" name="drvrFname" id="drvrFname" /> 
            </label><br/>
            <label>
                <span>Last Name : </span>
                <input type="text" required="required" name="drvrLname" id="drvrLname" /> 
            </label><br/>
            <label>
                <span>Email : </span>
                <input type="text" required="required" name="drvrEmail" id="drvrEmail" /> 
            </label><br/>
            <label>
                <span>Password : </span>
                <input type="password" required="required" name="drvrPass" id="drvrPass" /> 
            </label><br/>            
            <label>
                <span>Mobile : </span>
                <input type="text" required="required" name="drvrMobile" id="drvrMobile" /> 
            </label><br/>
            <label>
                <span>Carrier : </span>
                <input type="text" name="carrier" id="carrier" /> 
            </label><br/>
            <input type="reset" name="resetform" class="btn_class" value="Cancel" />
            <input type="submit" name="sbt" class="btn_class" value="Register" />
        </form>
        <?php
	}
}
if($actionName=='donateFood')
{
	$restaurantID = $_REQUEST['hideID'];
	$pickbefore = $_REQUEST['pickbefore'];
	$minutedata = $_REQUEST['minutedata'];
	$dayNight = $_REQUEST['timing'];
	$preferredFood = $_REQUEST['pdon'];
	$nbox = $_REQUEST['nbox'];
	$appwght = $_REQUEST['appwght'];
	$onlydate = date('Y-m-d');
	$onlytime = date('H:iA');
	
	$userData = array(
		'restaurantId' => $restaurantID,
		'curDate' => $onlydate,
		'curTiming' => $onlytime,
		'amORpm' => $dayNight,
		'preferredFood' => $preferredFood,
		'numbox' => $nbox,
		'appweight' => $appwght,
		'hrdata' => $pickbefore,
		'minuteData' => $minutedata,		
		'foodStatus' => 'open',
		'addDate' => $dateNow
	);
	$insert_id = $db->insert('donatefood',$userData);
}
if($actionName=='changestatus')
{
	extract($_POST);
	$userData = array(
					'profileStatus' => $_POST['stat']
				);
	$condition = array('id' => $_POST[id]);
	$update = $db->update('userregister',$userData,$condition);
	if($divid=='donorid')
	{
	?>	
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
		  if($fetchcat[0]['id']!='')
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
			  <td colspan="6" align="center">No Donors there with Status 'Y'</td>
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
		  if($fetchcat[0]['id']!='')
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
			  <td colspan="6" align="center">No Donors there with Status 'N'</td>
              </tr>
			  <?php
		  }
		?>
    </table>    
	<?php	
	}
	if($divid=='driverid')
	{
	?>	
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
		  if($fetchcat[0]['id']!='')
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
			  <td colspan="6" align="center">No Drivers there with Status 'Y'</td>
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
		  if($fetchcat[0]['id']!='')
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
			  <td colspan="6" align="center">No Drivers there with Status 'N'</td>
              </tr>
			  <?php
		  }
		?>
    </table>  
	<?php	
	}
	if($divid=='benid')
	{
	?>	
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
		  if($fetchcat[0]['id']!='')
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
			  <td colspan="6" align="center">No Beneficiary there with Status 'Y'</td>
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
		  if($fetchcat[0]['id']!='')
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
			  <td colspan="6" align="center">No Beneficiary there with Status 'N'</td>
              </tr>
			  <?php
		  }
		?>
    </table>
	<?php	
	}
}
if($actionName=='confirmdeliver')
{
	extract($_POST);
	$userData = array(
					'foodStatus' => 'delivered'
				);
	$condition = array('id' => $_POST['order_id']);
	$update = $db->update('donatefood',$userData,$condition);
	
	$dateToday=date('Y-m-d');
	$currenttimestamp=time().'<br/>';
	
	$query = "select * from donatefood where curDate>='$dateToday' and foodStatus='confirm' and driverId=$driverId";
	$fetchcat = $db->fetchQuery($query);
	?>
	<h2>Picked up donations List</h2><hr>
	<?php
	if($fetchcat[0]['id']!='')
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
			<th>Weight</th>
			<th>Pickup Time</th>
			<th>Receiver</th>
			<th>Receiver Address</th>
			<th>Receiver Phone</th>
			<th>Receiver Email</th>            
		</tr>
	<?php
	$cnfcounter=1;
	$query = "select * from donatefood where curDate>='$dateToday' and foodStatus='confirm' and driverId=$driverId";
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
						<td><button id="btn<?=$cnfcounter?>" onClick="confirmdeliver('<?=$recdata['id']?>','newdata','<?=$driverId?>');">Deliver ?</button></td>
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
	<span>No Data Found.</span>
	<?php	
	}
	?>
	<h2>Delivered donations List</h2><hr>
	<?php
	$query = "select * from donatefood where foodStatus='delivered' and driverId = $driverId";
	$fetchcat = $db->fetchQuery($query);
	if($fetchcat[0]['id']!='')
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
			<th>Weight</th>
			<th>Pickup Time</th>
			<th>Receiver</th>
			<th>Receiver Address</th>
			<th>Receiver Phone</th>
			<th>Receiver Email</th>            
		</tr>
	<?php
	$query = "select * from donatefood where foodStatus='delivered' and driverId=$driverId";
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
				$count++;
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
	<span>No Data Found.</span>
	<?php	
	}
}
if($actionName=='pickupconfirmdonor')
{
	extract($_POST);
	$userData = array(
		'pickupConf' => 'confirmed'
	);
	$condition = array('id' => $_REQUEST['donateid']);
	$update = $db->update('donatefood',$userData,$condition);
	echo "confirmed";
}
if($actionName=='deliveryconfirmreceiver')
{
	extract($_POST);
	$userData = array(
		'deliveryConf' => 'confirmed'
	);
	$condition = array('id' => $_REQUEST['donateid']);
	$update = $db->update('donatefood',$userData,$condition);
	echo "confirmed";
}
?>