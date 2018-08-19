<?php
// if $top is already set, then common is already loaded
if (!isset($top)) { require_once('../includes/common.php'); }
require_once($top.'connection.php');

abstract class NotificationType {
    const NewCompany                = 1;
    const NewMember                 = 2;
    const DonationPosted            = 3;
    const DonationClaimed           = 4;
    const DonationScheduled         = 5;
    const DonationDroppedOff        = 6;
    const DonationReceived          = 7;
    const DonationUnclaimed         = 8;
    const DonationModified          = 9;
    const DonationExpired           = 10;
    const MemberApproved            = 11;
    const CompanyApproved           = 12;
    const SecondMemberVerification  = 13;
}

if (Utilities::PageWasCalledDirectly('notification')) {
    $notificationController = new NotificationController();
    header('content-type:application/json');

    if (isset($_REQUEST['action'])) {
        $notificationController->{ $_REQUEST['action'] }();
    }
}

class NotificationController {
    public static function send($donationId = null, $type = null, $description = null) {
        $result = null;
        $db = DB::getInstance();

        if ($donationId == null) { $donationId = 'null'; }
        if ($type == null) { $type = NotificationType::NewCompany; }
        if ($description == null) { $description = $_POST['CompanyName']; }

        switch ($type) {
            case NotificationType::NewCompany :
                $subject = 'New company registered';
                $description = 'New company "' . $description . '" registered';
                break;
            case NotificationType::NewMember :
                $subject = 'New member registered';
                $description = 'New member "' . $description . '" registered';
                break;
            case NotificationType::DonationPosted :
                $subject = 'Donation Posted';
                $description = $description . ' has posted a donation';
                break;
            case NotificationType::DonationClaimed :
                $subject = 'Donation Claimed';
                $description = $description . ' has claimed a donation';
                break;
            case NotificationType::DonationScheduled :
                $subject = 'Donation Scheduled';
                $description = $description . ' has scheduled a donation';
                break;
            case NotificationType::DonationDroppedOff :
                $subject = 'Donation Dropped Off';
                $description = $description . ' has dropped off a donation';
                break;
           case NotificationType::DonationReceived :
                $subject = 'Donation Received';
                $description = $description . ' has received a donation';
                break;
            case NotificationType::DonationUnclaimed :
                $subject = 'Donation Unclaimed';
                $description = $description . ' has unclaimed a donation';
                break;
            case NotificationType::DonationModified :
                $subject = 'Donation Modified';
                $description = $description . ' has modified a donation';
                break;
            case NotificationType::DonationExpired :
                $subject = 'Donation Expired';
                $description = $description . 's donation has expired';
                break;
            case NotificationType::MemberApproved :
                $subject = 'Member Approved';
                $description = $description . ' has been approved';
                break;
            case NotificationType::CompanyApproved :
                $subject = 'Company Approved';
                $description = $description . ' has been approved';
                break;
            case NotificationType::SecondMemberVerification :
                $subject = 'Second Member Verification';
                $description = $description . ' has been verified as a second member';
                break;

            default:
                $subject = 'Notification';
                break;
        }

        $DBResult = DB::callProcWithRecordset("CALL GetNotificationRecipients($donationId, $type)");

        if (is_null($DBResult)) {
            $result = array('error' => true, 'errorMessage' => 'Database error');
        } else {
            if (ENV == 'local') {
                $result = array('error' => false, 'subject' => $subject, 'body' => $description, 'recipients' => $DBResult);
            } else {
                foreach ($DBResult as $row) {
                    sendEmail($row["TextAddress"], $subject, $description);
                }

                $result = array('error' => false);
            }
        }

        return Utilities::ReturnAppropriateResult('notification', $result);
    }

    public static function sendEmail($to, $subject, $body) {
        $result = null;
        $isError = false;
        $errorMessage = '';

        //$to = 'brimer@gmail.com';
        //$subject = 'Notification from W4M';
        //$body = 'New company registered: ' . $_POST['CompanyName'];
        $headers = "From: admin@wheels4meals.org" . PHP_EOL;
        $headers .= "Cc: brian@brimer.net" . PHP_EOL;

        try {
            $isError = ! mail($to, $subject, $body, $headers);
            if ($isError) { $errorMessage = 'ERROR: dunno'; }
        } catch (Exception $ex) {
            $isError = true;
            $errorMessage = $ex->getMessage();
        } catch (Error $er) {
            $isError = true;
            $errorMessage = 'ERROR: dunno';
        }
        
        $result = array('error' => $isError, 'errorMessage' => $errorMessage);

        return Utilities::ReturnAppropriateResult('notification', $result);
    }
}
?>
