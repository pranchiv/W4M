<?php 
$controller = new DonationController();
$controller->{ $_REQUEST['action'] }();

$db = new dbconn();

class CompanyController {
    public function checkForExpiring() {
		$curtstamp = time();

		$allopendonations = $db->getRows('Donation',array('where'=>array('foodStatus'=>'open')));
		
		foreach($allopendonations as $donateData)
		{
			$strconversion = strtotime($donateData['addDate']);
			$strconversionTot = $strconversion+(30*60);
			if ($curtstamp > $strconversionTot)	{
				$userData = array(
					'foodStatus' => 'hold',
					'receiverId' => 27
				);
				$updtID = array('id'=>$donateData['id']);
				$update_id = $db->update('Donation', $userData, $updtID);
			}
		}
	}
}
?>