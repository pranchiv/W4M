<?php
// if $top is already set, then common is already loaded
if (!isset($top)) { require_once('../includes/common.php'); }
require_once($top.'connection.php');
require_once($top.'controllers/notification.php');

abstract class MemberType {
    const Admin         = 1;
    const Driver        = 2;
    const Donor         = 3;
    const Beneficiary   = 4;
}

abstract class MemberStatus {
    const Prospective   = 1;
    const Active        = 2;
    const Inactive      = 3;
    const Denied        = 4;
}

if (Utilities::PageWasCalledDirectly('member')) {
    $memberController = new MemberController();
    header('content-type:application/json');

    if (isset($_REQUEST['action'])) {
        $memberController->{ $_REQUEST['action'] }();
    }
}

class MemberController {
    public static function register() {
        $result = null;
        $db = DB::getInstance();

        switch ($_SESSION['RegistrationType']) {
            case 'Donor':       $MemberTypeID = MemberType::Donor;          break;
            case 'Beneficiary': $MemberTypeID = MemberType::Beneficiary;    break;
            case 'Driver':      $MemberTypeID = MemberType::Driver;         break;
        }

        // use form data $_POST array as parameters for DB call
        // use real_escape_string function to allow quotes and prevent SQL injection hacks
        $FirstName  = $db->real_escape_string($_POST['FirstName']);
        $LastName   = $db->real_escape_string($_POST['LastName']);
        $Email      = $db->real_escape_string($_POST['Email']);
        $CellNumber = $db->real_escape_string($_POST['CellNumber']);
        $CarrierID  = $db->real_escape_string($_POST['CellCarrier']);
        $Username   = $db->real_escape_string($_POST['Username']);
    
        $CompanyID = isset($_SESSION['CompanyID']) ? $_SESSION['CompanyID'] : 'null';

        $raw = $db->real_escape_string($_POST['Password']);
        $hashpass = password_hash($raw, PASSWORD_DEFAULT);

        // put single quotes around any text fields: '$CompanyName'
        // no quotes are necessary around numeric fields: $CompanyTypeID
        $sql = "CALL RegisterMember($CompanyID, $MemberTypeID, '$FirstName', '$LastName', "
        ."'$CellNumber', $CarrierID, '$Email', '$Username', '$hashpass')";
        $DBResult = DB::callProcWithRecordset($sql);

        if (is_null($DBResult)) {
            $result = array('error' => true, 'errorMessage' => 'Database error');
        } else {
            $member = $DBResult[0];

            if ($member['Exists'] == 0) {
                self::setSessionVariables($member);
                $nextPage = self::determineStartPage();        
                $notifications = NotificationController::send(NotificationType::NewMember, $_SESSION['MemberName']);
                $result = array('error' => false, 'exists' => false, 'member' => $member, 'notifications' => $notifications, 'nextPage' => $nextPage);
            } else {
                $result = array('error' => false, 'exists' => true, 'existsType' => $member['Exists']);
            }
        }

        return Utilities::ReturnAppropriateResult('member', $result);
    }

    public static function logIn($username = null, $password = null) {
        $result = null;
        $isError = false;
        $errorMessage = '';
        $nextPage = 'logIn';

        $db = DB::getInstance();

        try {
            if (!isset($username)) { $username = $_POST['Username']; }
            if (!isset($password)) { $password = $_POST['Password']; }

            $username = $db->real_escape_string($username);
            $password = $db->real_escape_string($password);
            $DBResult = DB::callProcWithRecordset("CALL GetMember(null, '$username')");

            if (is_null($DBResult)) {
                $isError = true;
                $errorMessage = $db->error;
            } else {
                if (count($DBResult) > 0) {
                    $row = $DBResult[0];

                    if (password_verify($password, $row["Password"])) {
                        $isError = false;
                        $errorMessage = "login successful (MemberID ".$row["MemberID"].")";
                        self::setSessionVariables($row);
                        $nextPage = self::determineStartPage();
                    } else {
                        $isError = true;
                        $errorMessage = "passwords do not match";
                    }
                } else {
                    $isError = true;
                    $errorMessage = "no account matches those credentials";
                }
            }
        } catch (Exception $ex) {
            $isError = true;
            $errorMessage = $ex->getMessage();
        } catch (Error $er) {
            $isError = true;
            $errorMessage = 'ERROR: dunno';
        }

        $result = array('error' => $isError, 'errorMessage' => $errorMessage, 'nextPage' => $nextPage);
        //echo json_encode($result);
        return Utilities::ReturnAppropriateResult('member', $result);
    }

    public static function logOut() {
        self::setSessionVariables(null);
        return Utilities::ReturnAppropriateResult('member', true);
    }

    private static function setSessionVariables($member) {
        if ($member == null) {
            // clear them all
            $_SESSION['MemberID'] = null;
            $_SESSION['MemberName'] = null;
            $_SESSION['MemberTypeID'] = null;
            $_SESSION['MemberType'] = null;
            $_SESSION['MemberStatusID'] = null;
            $_SESSION['MemberStatus'] = null;
            $_SESSION['CompanyID'] = null;
            $_SESSION['Company'] = null;
        } else {
            $_SESSION['MemberID'] = (int)$member['MemberID'];
            $_SESSION['MemberName'] = $member['FirstName'].' '.$member['LastName'];
            $_SESSION['MemberTypeID'] = (int)$member['MemberTypeID'];
            $_SESSION['MemberType'] = $member['MemberType'];
            $_SESSION['MemberStatusID'] = (int)$member['MemberStatusID'];
            $_SESSION['MemberStatus'] = $member['MemberStatus'];
            $_SESSION['CompanyID'] = Utilities::NullableInt($member['CompanyID']);
            $_SESSION['Company'] = $member['CompanyName'];
        }
    }

    public static function determineStartPage() {
        switch ($_SESSION['MemberTypeID']) {
            case MemberType::Admin          : $result = 'pages/admin';  break;
            case MemberType::Driver         : $result = 'pages/driver';  break;
            case MemberType::Donor          : $result = 'pages/donor';  break;
            case MemberType::Beneficiary    : $result = 'pages/beneficiary';  break;            
            default: 'index'; break;
        }

        $result .= '.php';
        return $result;
    }

    public static function getMembers($status = null) {
        $result = null;
        $isError = false;
        $errorMessage = '';

        $db = DB::getInstance();

        if (!isset($status)) { $status = $_GET['status']; }
        $status = $db->real_escape_string($status);
        $DBResult = DB::callProcWithRecordset("CALL GetMembers($status)");

        if (is_null($DBResult)) {
            $isError = true;
            $errorMessage = $db->error;
        }

        $result = array('error' => $isError, 'errorMessage' => $errorMessage, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('member', $result);        
    }
}
?>
