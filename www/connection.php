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
        $recordset = NULL;
        $db = self::getInstance();

        if ($db->multi_query($sql)) {
            do {
                if ($result = $db->store_result()) {
                    $recordset = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                }
                //if ($db->more_results()) { } // 2nd recordset
            } while ($db->more_results() && $db->next_result());
        } else {
            return NULL;
        }

        // $db->close();
        return $recordset;
    }
}
   
?>