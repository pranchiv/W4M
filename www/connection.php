<?php
require_once('appSettings.php');

//echo '<div class="location_id">connection.php</div><br/>' . "\n\n";

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

    public static function closeConnection() {
        if (isset(self::$instance)) {
            self::$instance->close();
        }
    }

    public static function callProcWithRecordset($sql) {
        $recordset = array();
        $db = self::getInstance();

        if ($db->multi_query($sql)) {
            do {
                if ($result = $db->store_result()) {
                    $recordset[] = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                }
                //if ($db->more_results()) { } // 2nd recordset
            } while ($db->more_results() && $db->next_result());
        } else {
            return NULL;
        }

        // $db->close();

        if (count($recordset) == 1) {
            return $recordset[0];
        } else {
            return $recordset;
        }
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
}
   
?>