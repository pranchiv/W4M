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
        $CellNumber = preg_replace('/\D/', '', $_POST['CellNumber']); // special treatment: remove anything that's not a digit
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
                self::setSessionVariables($member, false);
                $nextPage = self::determineStartPage();        
                $notifications = NotificationController::send(null, NotificationType::NewMember, $_SESSION['MemberName']);
                $result = array('error' => false, 'exists' => false, 'member' => $member, 'notifications' => $notifications, 'nextPage' => $nextPage);
            } else {
                $result = array('error' => false, 'exists' => true, 'existsType' => $member['Exists']);
            }
        }

        return Utilities::ReturnAppropriateResult('member', $result);
    }

    public static function logIn($username = null, $password = null, $persist = null) {
        $result = null;
        $isError = false;
        $errorMessage = '';
        $nextPage = 'logIn';

        $db = DB::getInstance();

        try {
            if (!isset($username)) { $username = $_POST['Username']; }
            if (!isset($password)) { $password = $_POST['Password']; }
            if (!isset($persist)) { $persist = array_key_exists('Persist', $_POST); }

            $username = $db->real_escape_string($username);
            $password = $db->real_escape_string($password);
            $DBResult = DB::callProcWithRecordset("CALL GetMember(null, '$username')");

            if (is_null($DBResult)) {
                $isError = true;
                $errorMessage = $db->error;
            } else {
                $member = $DBResult[0];
                $credentials = $DBResult[1];
                $filter_type1 = self::filterByCredentialType(1);
                $login_credentials = array_filter($credentials, $filter_type1);
                $login_credentials = array_values($login_credentials);

                if (count($member) > 0 && count($login_credentials) > 0) {
                    $row = $member[0];
                    $cred = $login_credentials[0];

                    if (password_verify($password, $cred["Credential"])) {
                        if ($persist) { self::setPersistLogin($row["MemberID"]); }

                        $isError = false;
                        $errorMessage = "login successful (MemberID ".$row["MemberID"].")";
                        self::setSessionVariables($row, false);
                        $nextPage = self::determineStartPage();
                    } else {
                        $isError = true;
                        $errorMessage = "invalid password";
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
        return Utilities::ReturnAppropriateResult('member', $result);
    }

    public static function logOut() {
        self::setSessionVariables(null, false);
        self::unsetPersistLogin();
        return Utilities::ReturnAppropriateResult('member', true);
    }

    private static function setPersistLogin($memberID) {
        $result = false;
        $isError = false;
        $errorMessage = '';

        $token = self::generateToken();
        $db = DB::getInstance();

        try {
            $hashtoken = password_hash($token, PASSWORD_DEFAULT);

            // credential type 2 = persist
            $DBResult = DB::callProcWithRecordset("CALL SetMemberCredential($memberID, 2, '$hashtoken', null)");

            $cookieval = $token.str_pad($memberID, 3, '0', STR_PAD_LEFT);
            $expiration = strtotime('+10 years'); // store cookie for 10 years
            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;

            setcookie('persist', $cookieval, $expiration, '/', $domain, false);
            $result = true;

        } catch (Exception $ex) {
            $isError = true;
            $errorMessage = $ex->getMessage();
        } catch (Error $er) {
            $isError = true;
            $errorMessage = 'ERROR: dunno';
        }

        return $result;
    }

    public static function usePersistLoginIfValid() {
        if (isset($_COOKIE['persist'])) {
            $isError = false;
            $errorMessage = '';
            $cookie = $_COOKIE['persist'];
            $token = substr($cookie, 0, -3);
            $memberID = substr($cookie, -3);

            try {
                $db = DB::getInstance();
                $DBResult = DB::callProcWithRecordset("CALL GetMember($memberID, null)");

                if (is_null($DBResult)) {
                    $isError = true;
                    $errorMessage = $db->error;
                } else {
                    $member = $DBResult[0];
                    $credentials = $DBResult[1];
                    $filter_type2 = self::filterByCredentialType(2);
                    $persist_credentials = array_filter($credentials, $filter_type2);

                    if (count($member) > 0 && count($persist_credentials) > 0) {
                        $row = $member[0];
                        $match = false;

                        foreach ($persist_credentials as $cred) {
                            if (password_verify($token, $cred["Credential"])) {
                                $match = true;
                                break;
                            }
                        }

                        if ($match) {
                            $isError = false;
                            $errorMessage = "persist successful (MemberID ".$row["MemberID"].")";
                            self::setSessionVariables($row, false);
                        } else {
                            $isError = true;
                            $errorMessage = "token does not match";
                        }
                } else {
                        $isError = true;
                        $errorMessage = "no account matches those credentials";
                    }

                    // if this cookie doesn't match a valid member or credential anymore, get rid of it
                    if ($isError) { self::unsetPersistLogin(); }
                }
            } catch (Exception $ex) {
                $isError = true;
                $errorMessage = $ex->getMessage();
            } catch (Error $er) {
                $isError = true;
                $errorMessage = 'ERROR: dunno';
            }
        }
    }

    private static function unsetPersistLogin() {
        if (isset($_COOKIE['persist'])) {
            // also should clear from DB, since it can't be used anymore once the cookie is cleared

            unset($_COOKIE['persist']);

            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
            setcookie('persist', '', 1, '/', $domain, false); // set it to expire
        }
    }

    public static function getAccount() {
        $result = null;
        $isError = false;
        $errorMessage = '';
        $memberID = $_SESSION['MemberID'];

        $db = DB::getInstance();

        try {
            $DBResult = DB::callProcWithRecordset("CALL GetMember($memberID, null)");

            if (is_null($DBResult)) {
                $isError = true;
                $errorMessage = $db->error;
            } else {
                $member = $DBResult[0];
                $credentials = $DBResult[1];
                $filter_type1 = self::filterByCredentialType(1);
                $login_credentials = array_filter($credentials, $filter_type1);
                $login_credentials = array_values($login_credentials);

                if (count($member) > 0 && count($login_credentials) > 0) {
                    $member = $member[0];
                    $cred = $login_credentials[0];
                    $isError = false;
                } else {
                    $isError = true;
                    $errorMessage = "couldn't get account info";
                }
            }
        } catch (Exception $ex) {
            $isError = true;
            $errorMessage = $ex->getMessage();
        } catch (Error $er) {
            $isError = true;
            $errorMessage = 'ERROR: dunno';
        }

        $result = array('error' => $isError, 'errorMessage' => $errorMessage, 'member' => $member);
        return Utilities::ReturnAppropriateResult('member', $result);
    }

    public static function updateAccount() {
        $result = null;
        $isError = false;
        $errorMessage = '';
        $memberID = $_SESSION['MemberID'];

        $db = DB::getInstance();

        try {
            // use form data $_POST array as parameters for DB call
            // use real_escape_string function to allow quotes and prevent SQL injection hacks
            $FirstName  = $db->real_escape_string($_POST['FirstName']);
            $LastName   = $db->real_escape_string($_POST['LastName']);
            $Email      = $db->real_escape_string($_POST['Email']);
            $CellNumber = preg_replace('/\D/', '', $_POST['CellNumber']); // special treatment: remove anything that's not a digit
            $CarrierID  = $db->real_escape_string($_POST['CellCarrier']);
            $Username   = $db->real_escape_string($_POST['Username']);

            $sql = "CALL UpdateMember($memberID, '$FirstName', '$LastName', '$CellNumber', $CarrierID, '$Email', '$Username')";
            $DBResult = DB::callProcWithRecordset($sql);

            if (is_null($DBResult)) {
                $result = array('error' => true, 'errorMessage' => 'Database error');
            } else {
                $member = $DBResult[0];
    
                if ($member['Exists'] == 0) {
                    self::setSessionVariables($member, false);
                    $result = array('error' => false, 'exists' => false, 'member' => $member);
                } else {
                    $result = array('error' => false, 'exists' => true, 'existsType' => $member['Exists']);
                }
            }
    
        } catch (Exception $ex) {
            $isError = true;
            $errorMessage = $ex->getMessage();
        } catch (Error $er) {
            $isError = true;
            $errorMessage = 'ERROR: dunno';
        }

        $result = array('error' => $isError, 'errorMessage' => $errorMessage);
        return Utilities::ReturnAppropriateResult('member', $result);
    }

    public static function updatePassword() {
        $isError = false;
        $errorMessage = '';

        $db = DB::getInstance();

        $memberID = $_SESSION['MemberID'];
        $raw = $db->real_escape_string($_POST['Password']);
        $hashpass = password_hash($raw, PASSWORD_DEFAULT);

        try {
            // credential type 1 = password
            $DBResult = DB::callProcWithRecordset("CALL SetMemberCredential($memberID, 1, '$hashpass', null)");
            $_SESSION['ForgotPassword'] = null;

        } catch (Exception $ex) {
            $isError = true;
            $errorMessage = $ex->getMessage();
        } catch (Error $er) {
            $isError = true;
            $errorMessage = 'ERROR: dunno';
        }

        $result = array('error' => $isError, 'errorMessage' => $errorMessage);
        return Utilities::ReturnAppropriateResult('member', $result);
    }

    private static function generateToken($length = 20) {
        return bin2hex(random_bytes($length));
    }

    private static function filterByCredentialType($type) {
        return function($test) use($type) { return ($test['CredentialTypeID'] == $type); };
    }

    private static function setSessionVariables($member, $forgotPassword) {
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
            $_SESSION['ForgotPassword'] = null;
        } else {
            $_SESSION['MemberID'] = (int)$member['MemberID'];
            $_SESSION['MemberName'] = $member['FirstName'].' '.$member['LastName'];
            $_SESSION['MemberTypeID'] = (int)$member['MemberTypeID'];
            $_SESSION['MemberType'] = $member['MemberType'];
            $_SESSION['MemberStatusID'] = (int)$member['MemberStatusID'];
            $_SESSION['MemberStatus'] = $member['MemberStatus'];
            $_SESSION['CompanyID'] = Utilities::NullableInt($member['CompanyID']);
            $_SESSION['Company'] = $member['CompanyName'];
            $_SESSION['ForgotPassword'] = ($forgotPassword ? true : null);
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
        $message = '';

        $db = DB::getInstance();

        if (!isset($status)) { $status = $_REQUEST['status']; }
        $status = $db->real_escape_string($status);
        $DBResult = DB::callProcWithRecordset("CALL GetMembers($status)");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('member', $result);        
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
        $DBResult = DB::callProcWithRecordset("CALL UpdateMemberStatus('$selected', $status)");

        if (is_null($DBResult)) {
            $isError = true;
            $message = $db->error;
        }

        $result = array('error' => $isError, 'message' => $message, 'data' => $DBResult);
        return Utilities::ReturnAppropriateResult('member', $result);        
    }
}
?>
