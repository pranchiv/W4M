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
    public static function getDonations($donationId = null, $status = null, $active = null, $donationTypes = null,
                                        $donorId = null, $beneficiaryId = null, $driverId = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($donationId)) {
            if (isset($_REQUEST['DonationId'])) { $donationId = $_REQUEST['DonationId']; } else { $donationId = null; }
        }
        if (!isset($status)) {
            if (isset($_REQUEST['Status'])) { $status = $_REQUEST['Status']; } else { $status = null; }
        }
        if (!isset($active)) {
            if (isset($_REQUEST['Active'])) { $active = $_REQUEST['Active']; } else { $active = null; }
        }
        if (!isset($donationTypes)) {
            if (isset($_REQUEST['DonationTypes'])) {
                $donationTypes = Utilities::BuildCsvFromArray($_REQUEST['DonationTypes'], true);
            } else {
                $donationTypes = '';
            }
        }
        if (!isset($donorId)) {
            if (isset($_REQUEST['DonorId'])) { $donorId = $_REQUEST['DonorId']; } else { $donorId = null; }
        }
        if (!isset($beneficiaryId)) {
            if (isset($_REQUEST['BeneficiaryId'])) { $beneficiaryId = $_REQUEST['BeneficiaryId']; } else { $beneficiaryId = null; }
        }
        if (!isset($driverId)) {
            if (isset($_REQUEST['DriverId'])) { $driverId = $_REQUEST['DriverId']; } else { $driverId = null; }
        }

        if (isset($_REQUEST['Role'])) {
            switch ($_REQUEST['Role']) {
                case 'Donor': $donorId = $_SESSION['CompanyID']; break;
                case 'Beneficiary': $beneficiaryId = $_SESSION['CompanyID']; break;
                case 'Driver': $driverId = $_SESSION['MemberID']; break;
            }
        }

        $donationId = ($donationId == null ? 'null' : $db->real_escape_string($donationId));
        $status = ($status == null ? 'null' : $db->real_escape_string($status));
        $active = ($active == null ? 'null' : $db->real_escape_string($active));
        $donationTypes = $db->real_escape_string($donationTypes);
        $donorId = ($donorId == null ? 'null' : $db->real_escape_string($donorId));
        $beneficiaryId = ($beneficiaryId == null ? 'null' : $db->real_escape_string($beneficiaryId));
        $driverId = ($driverId == null ? 'null' : $db->real_escape_string($driverId));
        $call = "CALL GetDonations($donationId, $status, $active, '$donationTypes', $donorId, $beneficiaryId, $driverId)";
        $DBResult = DB::callProcWithRecordset($call);

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        } else {
            $message = $call;
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('donation', $result);        
    }
    
    public static function add($companyId = null, $pickupTime = null, $donationTypes = null, $numBoxes = null, $weight = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($companyId)) {
            if (isset($_REQUEST['CompanyId'])) { $companyId = $_REQUEST['CompanyId']; } else { $companyId = $_SESSION['CompanyID']; }
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
        } else if ($DBResult[0][0]['Error'] != 0) {
            $isError = true;
            $message = 'ERROR: donation could not be added';
        } else {
            $message = 'Thank you for your donation!';
        }

        // get rid of first resultset: error codes
        array_splice($DBResult, 0, 1);

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('donation', $result);
    }
}
?>
