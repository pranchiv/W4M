<?php
require_once('connection.php');

$controller = new CompanyController();
$controller->{ 'register' }();

class CompanyController {
    public function register() {
        $result = null;
        header('content-type:application/json');

        // use form data $_POST array as parameters for DB call
        $company = DB::callProcWithRecordset("CALL RegisterCompany(1, 'name', 'address1', 'address2', 'city', 'state', 'zip', 1)");

        if (is_null($company)) {
            // error
        } else {
            if ($company[0]['Exists'] == 1) {

            }
        }

        echo json_encode($result);
    }
}
?>
