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
                $_SESSION['MemberID'] = $member['MemberID'];
                $_SESSION['MemberName'] = $member['FirstName'].' '.$member['LastName'];
                $notifications = NotificationController::send(NotificationType::NewMember, $_SESSION['MemberName']);
                $result = array('error' => false, 'exists' => false, 'member' => $member, 'notifications' => $notifications);
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

            $sql = "SELECT c.Name `CompanyName`, m.* 
                    FROM Member m 
                    LEFT JOIN Company c ON c.CompanyID = m.CompanyID 
                    WHERE m.Username = '$username' 
                    AND m.DeleteDate IS NULL";
            $dataset = $db->query($sql);

            if ($dataset) {
                if ($dataset->num_rows > 0) {
                    $row = $dataset->fetch_assoc();

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
            $_SESSION['MemberType'] = null;
            $_SESSION['MemberStatus'] = null;
            $_SESSION['CompanyID'] = null;
            $_SESSION['Company'] = null;
        } else {
            $_SESSION['MemberID'] = Utilities::NullableInt($member['MemberID']);
            $_SESSION['MemberName'] = $member['FirstName'].' '.$member['LastName'];
            $_SESSION['MemberType'] = Utilities::NullableInt($member['MemberTypeID']);
            $_SESSION['MemberStatus'] = Utilities::NullableInt($member['MemberStatusID']);
            $_SESSION['CompanyID'] = Utilities::NullableInt($member['CompanyID']);
            $_SESSION['Company'] = $member['CompanyName'];
        }
    }

    public static function determineStartPage() {
        switch ($_SESSION['MemberType']) {
            case MemberType::Admin          : $result = 'pages/admin';  break;
            case MemberType::Driver         : $result = 'pages/driver';  break;
            case MemberType::Donor          : $result = 'pages/donor';  break;
            case MemberType::Beneficiary    : $result = 'pages/beneficiary';  break;            
            default: 'index'; break;
        }

        $result .= '.php';
        return $result;
    }
}
?>
