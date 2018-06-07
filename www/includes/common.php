<?php
    session_start();
    $root = 'http://' . $_SERVER["SERVER_NAME"];
    $top = Utilities::DeterminePathToTop();
    $showBanner = true; // this is the default value. Override on any page if necessary.

    class Utilities {
        public static function DeterminePathToTop() {
            $result = '';
            $count = substr_count($_SERVER['REQUEST_URI'], '/');
            $result = str_repeat('../', $count - 1);

            return $result;
        }

        // check $_SERVER['REQUEST_URI'] to see if page was called directly vs as include
        public static function PageWasCalledDirectly($pagename) {
            // assume ".php" ending if none given
            if (!preg_match('/\./', $pagename)) { $pagename .= '.php'; }

            $path = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
            $result = self::EndsWith($path, $pagename);

            return $result;
        }

        public static function ReturnAppropriateResult($pagename, $result) {
            if (self::PageWasCalledDirectly($pagename)) {
                echo json_encode($result);
                return true;
            } else {
                return $result;
            }
        }

        public static function StartsWith($container, $text)
        {
            $length = strlen($text);
            return (substr($container, 0, $length) === $text);
        }

        public static function EndsWith($container, $text)
        {
            $length = strlen($text);
            return $length === 0 || (substr($container, -$length) === $text);
        }
    }
?>
