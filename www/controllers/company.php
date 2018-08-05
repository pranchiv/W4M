<?php
// if $top is already set, then common is already loaded
if (!isset($top)) { require_once('../includes/common.php'); }
require_once($top.'connection.php');
require_once($top.'controllers/notification.php');

if (Utilities::PageWasCalledDirectly('company')) {
    $companyController = new CompanyController();
    header('content-type:application/json');

    if (isset($_REQUEST['action'])) {
        $companyController->{ $_REQUEST['action'] }();
    }
}

class CompanyController {
    public function startRegistration() {
        $_SESSION['RegistrationType'] = $_POST['RegistrationType'];
        $_SESSION['Zip'] = $_POST['Zip'];

        echo true;
    }

    public function register() {
        $result = null;
        $db = DB::getInstance();

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
                $notifications = NotificationController::send(null, NotificationType::NewCompany, $CompanyName);
                $result = array('error' => false, 'exists' => false, 'company' => $company, 'notifications' => $notifications);
            }
        }

        return Utilities::ReturnAppropriateResult('company', $result);
    }

    public static function getCompanies($status = null, $active = null) {
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
    
    public static function updateStatus($selected = null, $status = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($selected)) { $selected = $_REQUEST['selected']; }
        if (!isset($status)) { $status = $_REQUEST['status']; }
        $selected = $db->real_escape_string($selected);
        $status = $db->real_escape_string($status);
        $DBResult = DB::callProcWithRecordset("CALL UpdateCompanyStatus('$selected', $status)");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('company', $result);        
    }

    public static function loadSchedule($companyId = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($companyId)) {
            if (isset($_REQUEST['companyId'])) { $companyId = $_REQUEST['companyId']; } else { $companyId = $_SESSION['CompanyID']; }
        }
        $companyId = $db->real_escape_string($companyId);
        $DBResult = DB::callProcWithRecordset("CALL GetCompanySchedule($companyId)");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('company', $result);        
    }

    public static function addSchedule($companyId = null, $day = null, $start = null, $end = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($companyId)) {
            if (isset($_REQUEST['companyId'])) { $companyId = $_REQUEST['companyId']; } else { $companyId = $_SESSION['CompanyID']; }
        }
        if (!isset($day)) { $day = $_REQUEST['Day']; }
        if (!isset($start)) { $start = $_REQUEST['Start']; }
        if (!isset($end)) { $end = $_REQUEST['End']; }

        $companyId = $db->real_escape_string($companyId);
        $day = $db->real_escape_string($day);
        $start = $db->real_escape_string($start);
        $end = $db->real_escape_string($end);
        $DBResult = DB::callProcWithRecordset("CALL AddCompanySchedule($companyId, $day, '$start', '$end')");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('company', $result);
    }

    public static function removeSchedule($companyId = null, $companyScheduleId = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($companyId)) {
            if (isset($_REQUEST['companyId'])) { $companyId = $_REQUEST['companyId']; } else { $companyId = $_SESSION['CompanyID']; }
        }
        if (!isset($companyScheduleId)) { $companyScheduleId = $_REQUEST['companyScheduleId']; }

        $companyId = $db->real_escape_string($companyId);
        $companyScheduleId = $db->real_escape_string($companyScheduleId);
        $DBResult = DB::callProcWithRecordset("CALL DeleteCompanySchedule($companyId, $companyScheduleId)");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('company', $result);
    }

    public static function updateDonationTypes($companyId = null, $donationTypes = null) {
        $result = null;
        $isError = false;
        $message = '';

        $db = DB::getInstance();

        if (!isset($companyId)) {
            if (isset($_REQUEST['companyId'])) { $companyId = $_REQUEST['companyId']; } else { $companyId = $_SESSION['CompanyID']; }
        }
        if (!isset($donationTypes)) {
            if (isset($_REQUEST['donationTypes'])) {
                $donationTypes = Utilities::BuildCsvFromArray($_REQUEST['donationTypes'], true);
            } else {
                $donationTypes = '';
            }
        }

        $companyId = $db->real_escape_string($companyId);
        $donationTypes = $db->real_escape_string($donationTypes);

        $DBResult = DB::callProcWithRecordset("CALL UpdateCompanyDonationTypes($companyId, '$donationTypes')");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        } else {
            $message = 'donation types updated';
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('company', $result);
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
        return Utilities::ReturnAppropriateResult('company', $result);
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
        return Utilities::ReturnAppropriateResult('company', $result);
    }
}
?>
