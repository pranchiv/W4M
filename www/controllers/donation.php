<?php
// if $top is already set, then common is already loaded
if (!isset($top)) { require_once('../includes/common.php'); }
require_once($top.'connection.php');
require_once($top.'controllers/notification.php');

if (Utilities::PageWasCalledDirectly('donation')) {
    $donationController = new DonationController();
    header('content-type:application/json');

    if (isset($_REQUEST['action'])) {
        $donationController->{ $_REQUEST['action'] }();
    }
}

class DonationController {
    public static function getDonations($status = null, $active = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($status)) {
            if (isset($_REQUEST['status'])) { $status = $_REQUEST['status']; } else { $status = null; }
        }
        if (!isset($active)) {
            if (isset($_REQUEST['active'])) { $active = $_REQUEST['active']; } else { $active = null; }
        }
        $status = ($status == null ? 'null' : $db->real_escape_string($status));
        $active = ($active == null ? 'null' : $db->real_escape_string($active));
        $DBResult = DB::callProcWithRecordset("CALL GetCompanies($status, $active)");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('company', $result);        
    }
    
    public static function add($companyId = null, $pickupTime = null, $donationTypes = null, $numBoxes = null, $weight = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($companyId)) {
            if (isset($_REQUEST['companyId'])) { $companyId = $_REQUEST['companyId']; } else { $companyId = $_SESSION['CompanyID']; }
        }
        
        if (!isset($donationTypes)) {
            if (isset($_REQUEST['DonationTypes'])) {
                $donationTypes = Utilities::BuildCsvFromArray($_REQUEST['DonationTypes'], true);
            } else {
                $donationTypes = '';
            }
        }
        
        if (!isset($pickupTime)) { $pickupTime = $_REQUEST['PickupTime']; }
        if (!isset($numBoxes)) { $numBoxes = $_REQUEST['NumBoxes']; }
        if (!isset($weight)) { $weight = $_REQUEST['Weight']; }

        $companyId = $db->real_escape_string($companyId);
        $pickupTime = $db->real_escape_string($pickupTime);
        $donationTypes = $db->real_escape_string($donationTypes);
        $numBoxes = $db->real_escape_string($numBoxes);
        $weight = $db->real_escape_string($weight);
        $memberId = $_SESSION['MemberID'];
        $DBResult = DB::callProcWithRecordset("CALL AddDonation($companyId, '$pickupTime', '$donationTypes', $numBoxes, $weight, $memberId)");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        } else {
            $message = 'Thank you for your donation!';
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('donation', $result);
    }
}
?>
