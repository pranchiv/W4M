<?php
require_once('../includes/common.php');
require_once('../connection.php');
require_once('notification.php');

$controller = new CompanyController();
$controller->{ $_REQUEST['action'] }();

class CompanyController {
    public function startRegistration() {
        $_SESSION['RegistrationType'] = $_POST['RegistrationType'];
        $_SESSION['Zip'] = $_POST['Zip'];

        echo true;
    }

    public function register() {
        $result = null;
        $db = DB::getInstance();
        header('content-type:application/json');

        switch ($_SESSION['RegistrationType']) {
            case 'Donor':       $CompanyTypeID = 1; break;
            case 'Beneficiary': $CompanyTypeID = 2; break;
        }

        // use form data $_POST array as parameters for DB call
        // use real_escape_string function to allow quotes and prevent SQL injection hacks
        $CompanyName    = $db->real_escape_string($_POST['CompanyName']);
        $Address1       = $db->real_escape_string($_POST['Address1']);
        $Address2       = $db->real_escape_string($_POST['Address2']);
        $City           = $db->real_escape_string($_POST['City']);
        $State          = $db->real_escape_string($_POST['State']);
        $Zip            = $db->real_escape_string($_POST['Zip']);
        $Exists         = '1';

        // put single quotes around any text fields: '$CompanyName'
        // no quotes are necessary around numeric fields: $CompanyTypeID
        $DBResult = DB::callProcWithRecordset("CALL RegisterCompany($CompanyTypeID, '$CompanyName', '$Address1', '$Address2', '$City', '$State', '$Zip', $Exists)");

        if (is_null($DBResult)) {
            $result = array('error' => true, 'errorMessage' => 'Database error');
        } else {
            $company = $DBResult[0];

            if ($company['Exists'] == 1) {
                $result = array('error' => false, 'exists' => true);
            } else {
                $_SESSION['CompanyID'] = $company['CompanyID'];
                $_SESSION['Company'] = $company['Name'];
                $notifications = NotificationController::send(NotificationType::NewCompany, $CompanyName);
                $result = array('error' => false, 'exists' => false, 'company' => $company, 'notifications' => $notifications);
            }
        }

        echo json_encode($result);
    }

    public function getAvailableCompanies() {
        
    }

    public function testEmail() {
        // test sending emails (texts)
        $result = null;
        $isError = false;
        $errorMessage = '';

        $to = 'brimer@gmail.com';
        $subject = 'Notification from W4M';
        $body = 'New company registered: ' . $_POST['CompanyName'];
        $headers = "From: admin@wheels4meals.org" . PHP_EOL;

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

    public function testStorePassword() {
        $result = null;
        $isError = false;
        $errorMessage = '';

        $db = DB::getInstance();

        try {
            $username = $db->real_escape_string($_POST['Username']);
            $raw = $db->real_escape_string($_POST['Password']);
            $hash = password_hash($raw, PASSWORD_DEFAULT);
            $sql = "UPDATE Member SET Password = '$hash' WHERE username = '$username'";

            if ($db->query($sql) === TRUE) {
                $isError = false;
                $errorMessage = 'stored "' . $hash . '"';
            } else {
                $isError = true;
                $errorMessage = $db->error;
            }
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
