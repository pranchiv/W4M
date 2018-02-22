<?php
require_once('../includes/common.php');
require_once('../connection.php');

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

        // use form data $_POST array as parameters for DB call
        // use real_escape_string function to allow quotes and prevent SQL injection hacks
        $CompanyTypeID  = $db->real_escape_string('1');
        $CompanyName    = $db->real_escape_string($_POST[CompanyName]);
        $Address1       = $db->real_escape_string('123 Main St');
        $Address2       = $db->real_escape_string('');
        $City           = $db->real_escape_string('Yardley');
        $State          = $db->real_escape_string('PA');
        $Zip            = $db->real_escape_string('19067');
        $Exists         = $db->real_escape_string('1');

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
                $result = array('error' => false, 'exists' => false, 'company' => $company);
            }
        }

        echo json_encode($result);
    }

    public function getAvailableCompanies() {
        
    }
}
?>
