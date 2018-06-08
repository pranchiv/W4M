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
    public function register() {
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

    public function logIn() {
        $result = null;
        $isError = false;
        $errorMessage = '';

        $db = DB::getInstance();

        try {
            $username = $db->real_escape_string($_POST['Username']);
            $password = $db->real_escape_string($_POST['Password']);

            $sql = "SELECT * FROM Member WHERE Username = '$username'";
            $dataset = $db->query($sql);

            if ($dataset) {
                if ($dataset->num_rows > 0) {
                    while ($row = $dataset->fetch_assoc()):
                        $MemberID = $row["MemberID"];
                        $hashPswd = $row["Password"];
                    endwhile;
                    
                    if (password_verify($password, $hashPswd)) {
                        //self::setSessionVariables($row);
                        $isError = false;
                        $errorMessage = "login successful (MemberID $MemberID)";
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

        $result = array('error' => $isError, 'errorMessage' => $errorMessage);
        echo json_encode($result);        
    }

    public function logOut() {
        self::setSessionVariables(null);
    }

    private function setSessionVariables($member) {
        if ($member == null) {
            // clear them all
        } else {
            //$_SESSION['CompanyID'] = $company['CompanyID'];
            //$_SESSION['Company'] = $company['Name'];
            $_SESSION['MemberID'] = $member['MemberID'];
            $_SESSION['MemberName'] = $member['FirstName'].' '.$member['LastName'];
            $_SESSION['MemberType'] = '';
            $_SESSION['MemberStatus'] = '';
        }
    }
}
?>
