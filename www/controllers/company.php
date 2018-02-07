<?php
require_once('../connection.php');

$controller = new CompanyController();
$controller->{ $_REQUEST['action'] }();

class CompanyController {
    public function register() {
        $result = null;
        header('content-type:application/json');

        // use form data $_POST array as parameters for DB call
        // put single quotes around any text fields: '$_POST[CompanyName]'
        // no quotes are necessary around numeric fields: $_POST[CompanyType]
        $DBResult = DB::callProcWithRecordset("CALL RegisterCompany(1, '$_POST[CompanyName]', 'address1', 'address2', 'city', 'state', 'zip', 1)");

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
}
?>
