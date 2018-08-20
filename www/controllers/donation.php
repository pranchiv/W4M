<?php
// if $top is already set, then common is already loaded
if (!isset($top)) { require_once('../includes/common.php'); }
require_once($top.'connection.php');
require_once($top.'controllers/notification.php');

abstract class DonationStatus {
    const Posted	    = 1;
    const Claimed	    = 2;
    const Scheduled	    = 3;
    const PickedUp	    = 4;
    const DroppedOff	= 5;
    const Received	    = 6;
    const Canceled	    = 7;
    const Lost	        = 8;
    const Damaged	    = 9;
    const Expired	    = 10;    
}

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
            //$message = $call;
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
            $result = array('error' => $isError, 'message' => $message);
        } else if ($DBResult[0][0]['Error'] != 0) {
            $isError = true;
            $message = 'ERROR: donation could not be added';
            $result = array('error' => $isError, 'message' => $message);
        } else {
            $donation = $DBResult[1][0]; // 1 = skips the error; 0 = donation data vs types
            $notifications = NotificationController::send($donation['DonationID'], NotificationType::DonationPosted, $_SESSION['Company']);
            $message = 'Thank you for your donation!';

            // get rid of first resultset: error codes
            array_splice($DBResult, 0, 1);

            $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult, 'notifications' => $notifications);
            return Utilities::ReturnAppropriateResult('donation', $result);
        }
    }
    
    public static function updateStatus($donationId = null, $action = null, $previousStatusId = null, $previousBeneficiaryId = null, $previousDriverId = null) {
        $result = null;
        $isError = false;
        $message = '';
        $statusId = null;
        $beneficiaryId = null;
        $driverId = null;
        $DBResult = null;

        $db = DB::getInstance();

        // the combination of these variables and the "action" indicated will determine what values are sent to the DB
        $memberId = $_SESSION['MemberID'];
        $companyId = $_SESSION['CompanyID'];
        
        if (isset($_REQUEST['Role'])) {
            $role = $_REQUEST['Role'];
        } else {
            switch ($_SESSION['MemberTypeID']) {
                case 1: $role = 'Admin'; break;
                case 2: $role = 'Driver'; break;
                case 3: $role = 'Donor'; break;
                case 4: $role = 'Beneficiary'; break;
            }
        }

        if (!isset($donationId)) {
            if (isset($_REQUEST['DonationId'])) { $donationId = $_REQUEST['DonationId']; }
        }
        if (!isset($previousStatusId)) {
            if (isset($_REQUEST['PreviousStatus'])) { $previousStatusId = $_REQUEST['PreviousStatus']; }
        }
        if (!isset($previousBeneficiaryId)) {
            if (isset($_REQUEST['PreviousBeneficiaryId']) && $_REQUEST['PreviousBeneficiaryId'] != 0) { $previousBeneficiaryId = $_REQUEST['PreviousBeneficiaryId']; }
        }
        if (!isset($previousDriverId)) {
            if (isset($_REQUEST['PreviousDriverId']) && $_REQUEST['PreviousDriverId'] != 0) { $previousDriverId = $_REQUEST['PreviousDriverId']; }
        }

        if (!isset($action)) { if (isset($_REQUEST['Action'])) { $action = $_REQUEST['Action']; } }
        if (isset($action)) {
            switch ($action) {
                case 'Claim'        : $statusId = DonationStatus::Claimed;              $beneficiaryId = $companyId;             $driverId = $previousDriverId;    $NotificationType = NotificationType::DonationClaimed;          break;
                case 'Schedule'     : $statusId = DonationStatus::Scheduled;            $beneficiaryId = $previousBeneficiaryId; $driverId = $memberId;            $NotificationType = NotificationType::DonationScheduled;        break;
                case 'Pick Up'      : $statusId = DonationStatus::PickedUp;             $beneficiaryId = $previousBeneficiaryId; $driverId = $previousDriverId;    $NotificationType = NotificationType::DonationPickedUp;         break;
                case 'Drop Off'     : $statusId = DonationStatus::DroppedOff;           $beneficiaryId = $previousBeneficiaryId; $driverId = $previousDriverId;    $NotificationType = NotificationType::DonationDroppedOff;       break;
                case 'Receive'      : $statusId = DonationStatus::Received;             $beneficiaryId = $previousBeneficiaryId; $driverId = $previousDriverId;    $NotificationType = NotificationType::DonationReceived;         break;
                case 'Cancel'       : $statusId = DonationStatus::Canceled;             $beneficiaryId = $previousBeneficiaryId; $driverId = $previousDriverId;    $NotificationType = null;         break;
                case 'Lost'         : $statusId = DonationStatus::Lost;                 $beneficiaryId = $previousBeneficiaryId; $driverId = $previousDriverId;    $NotificationType = NotificationType::DonationLost;             break;
                case 'Damaged'      : $statusId = DonationStatus::Damaged;              $beneficiaryId = $previousBeneficiaryId; $driverId = $previousDriverId;    $NotificationType = NotificationType::DonationDamaged;          break;
                case 'Unclaim'      : 
                    if ($previousStatusId == DonationStatus::Claimed) {
                        $statusId = DonationStatus::Posted;
                        $NotificationType = NotificationType::DonationPosted;
                    } else {
                        $statusId = $previousStatusId;
                        $NotificationType = NotificationType::DonationUnclaimed;
                    }
                    $beneficiaryId = null;
                    $driverId = $previousDriverId;
                    break;
                case 'Unschedule'   :
                    $statusId = DonationStatus::Claimed;
                    $NotificationType = NotificationType::DonationUnscheduled;
                    $beneficiaryId = $previousBeneficiaryId;
                    $driverId = null;
                    break;
            }
        }
        
        // security
        if (isset($beneficiaryId) && $role == 'Beneficiary' && $beneficiaryId != $companyId) {
            $isError = true;
            $message = 'Illegal update';
        }

        if (! $isError) {
            $donationId = ($donationId == null ? 'null' : $db->real_escape_string($donationId));
            $statusId = ($statusId == null ? 'null' : $db->real_escape_string($statusId));
            $beneficiaryId = ($beneficiaryId == null ? 'null' : $db->real_escape_string($beneficiaryId));
            $driverId = ($driverId == null ? 'null' : $db->real_escape_string($driverId));
            $call = "CALL UpdateDonationStatus($donationId, $statusId, $beneficiaryId, $driverId, $memberId)";
            $DBResult = DB::callProcWithRecordset($call);

            if (is_null($DBResult)) {
                $isError = true;
                $message = $db->error;
                $result = array('error' => $isError, 'message' => $message);
            } else if ($DBResult[0][0]['Error'] != 0) {
                $isError = true;
                $message = 'ERROR: donation could not be updated';
                $result = array('error' => $isError, 'message' => $message);
            } else {
                $message = 'Donation has been updated';

                // get rid of first resultset: error codes
                array_splice($DBResult, 0, 1);
                switch ($NotificationType) {
                    case NotificationType::DonationScheduled:
                    case NotificationType::DonationDroppedOff:
                    case NotificationType::DonationPickedUp:
                        $description = $_SESSION['MemberName'];
                        break;

                    default:
                        $description = $_SESSION['Company'];
                        break;
                }

                $notifications = null;
                if ($NotificationType != null) { $notifications = NotificationController::send($donationId, $NotificationType, $description); }

                $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult, 'notifications' => $notifications);
                return Utilities::ReturnAppropriateResult('donation', $result);
            }

        }
    }
}
?>
