<?php
require_once('../includes/common.php');
require_once('../connection.php');

abstract class NotificationType {
    const NewCompany        = 1;
    const NewMember         = 2;
    const DonationPosted    = 3;
    const DonationClaimed   = 4;
    const DonationScheduled = 5;
    const DonationDroppedOff= 6;
    const DonationReceived  = 7;
    const DonationUnclaimed = 8;
    const DonationModified  = 9;
    const DonationExpired   = 10;
}

//$notificationController = new NotificationController();

// if ($_REQUEST['action'] != null) {
//     $notificationController->{ $_REQUEST['action'] }();
// }

class NotificationController {
    public static function send($type = null, $description = null) {
        $result = null;
        $db = DB::getInstance();
        header('content-type:application/json');

        if ($type == null) { $type = NotificationType::NewCompany; }
        if ($description == null) { $description = $_POST['CompanyName']; }

        switch ($type) {
            case NotificationType::NewCompany :
                $subject = 'New company registered (' . $description . ')';
                $description = 'New company "' . $description . '" registered';
                break;
            case NotificationType::NewMember :
                $subject = 'New member registered (' . $description . ')';
                $description = 'New member "' . $description . '" registered';
                break;
            default:
                $subject = 'Notification';
                break;
        }

        $DBResult = DB::callProcWithRecordset("CALL GetNotificationRecipients($type)");

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

        return $result;
        //echo json_encode($result);
    }

    public static function sendEmail($to, $subject, $body) {
        $result = null;
        $isError = false;
        $errorMessage = '';
        header('content-type:application/json');

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

        echo json_encode($result);
    }
}
?>
