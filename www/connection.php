<?php
require_once('appSettings.php');

echo '<div class="location_id">connection.php</div><br/>' . "\n\n";

class DB {
    private static $instance = NULL;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        if (!isset(self::$instance)) {
            // Create connection
            self::$instance = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DBNAME);
            // Check connection
            if (self::$instance->connect_error) {
                die('<p style="color: red; font-style: italic;">Connection failed: ' . self::$instance->connect_error . "</p>\n");
            }
        }
        return self::$instance;
    }
}

class DBPDO {
    private static $instance = NULL;
    
    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        //echo '<div class="location_id">DB getInstance()</div><br/>' . "\n\n";

        if (!isset(self::$instance)) {
            try {
                $connection_string = "mysql:host=" . DB_SERVER . ";dbname=" . DB_DBNAME; $u = DB_USERNAME; $p = DB_PASSWORD;
                
                echo "<p>Trying connection string: <span style=\"font-size: 8px;\">$connection_string</span></p>";

                self::$instance = new PDO($connection_string, $u, $p);
    
            } catch (PDOException $e) {
                die("Error: " . $e->getMessage() . "<br/>\n");
            }
        }

        return self::$instance;
    }

/*
$sql = "{:retval = CALL spGetSomethingById (@Id=:userID,@myemail=:userEmail)}";
$stmt = $db->prepare($sql);

$retval = null;
$userID = 2;
$userEmail = "";

$stmt->bindParam('retval', $retval, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT, 4);
$stmt->bindParam('userID', $userID, PDO::PARAM_INT);
$stmt->bindParam('userEmail', $userEmail, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 50);

$stmt->execute();

$results = array();
do {
    $results []= $stmt->fetchAll();
} while ($stmt->nextRowset());

echo '<pre>';
print_r($retval);echo "\n"; // the return value: 5
print_r($userEmail);echo "\n"; // email for record id=1
print_r($results);echo "\n"; // all record sets
echo '</pre>';

$stmt->closeCursor();
unset($stmt);

*/    
}

?>