<?php
    session_start();

    $protocol = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol .= 's'; }
    $root = $protocol . '://' . $_SERVER["SERVER_NAME"];
    
    $top = Utilities::DeterminePathToTop();
    $showBanner = true; // this is the default value. Override on any page if necessary.

    require_once($top.'controllers/member.php');

    Utilities::VerifyPageAccess($root.'/');
    
    class Utilities {
        public static function DeterminePathToTop() {
            $result = '';
            $count = substr_count($_SERVER['REQUEST_URI'], '/');
            $result = str_repeat('../', $count - 1);

            return $result;
        }

        public static function VerifyPageAccess($root_local) {
            $verified = false;
            $path = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
            $requestedpage = str_replace('.php', '', basename($path));
            $redirectpage = '';

            $loggedin = ($_SESSION['MemberID'] != null);

            // check for persist cookie and log in automatically if valid
            if (! $loggedin) {
                MemberController::usePersistLoginIfValid();
                $loggedin = ($_SESSION['MemberID'] != null);
            }
            
            if ($loggedin) {
                switch ($requestedpage) {
                    case 'admin':           $types = array(MemberType::Admin); break;
                    case 'adminDonations':  $types = array(MemberType::Admin); break;
                    case 'beneficiary':     $types = array(MemberType::Beneficiary); break;
                    case 'companySettings': $types = array(MemberType::Donor, MemberType::Beneficiary); break;
                    case 'donationHistory': $types = array(MemberType::Admin, MemberType::Driver, MemberType::Donor, MemberType::Beneficiary); break;
                    case 'donor':           $types = array(MemberType::Donor); break;
                    case 'driver':          $types = array(MemberType::Driver); break;
                    case 'company':         $types = array(MemberType::Admin, MemberType::Driver, MemberType::Donor, MemberType::Beneficiary); break; // controllers via ajax
                    case 'donation':        $types = array(MemberType::Admin, MemberType::Driver, MemberType::Donor, MemberType::Beneficiary); break; // controllers via ajax
                    case 'member':          $types = array(MemberType::Admin, MemberType::Driver, MemberType::Donor, MemberType::Beneficiary); break; // controllers via ajax
                    case 'notification':    $types = array(MemberType::Admin, MemberType::Driver, MemberType::Donor, MemberType::Beneficiary); break; // controllers via ajax
                    default:                $types = array(); break;
                }

                // if not allowed on requested page, set redirect to start page for member type
                if (in_array($_SESSION['MemberTypeID'], $types)) {
                    $verified = true;
                } else {
                    $redirectpage = MemberController::determineStartPage();
                }
            } else {
                switch ($requestedpage) {
                    case 'logIn':           $verified = true; break;
                    case 'register':        $verified = true; break;
                    case 'registerCompany': $verified = true; break;
                    case 'registerMember':  $verified = true; break;
                    case 'company':         $verified = true; break; // controllers via ajax
                    case 'donation':        $verified = true; break; // controllers via ajax
                    case 'member':          $verified = true; break; // controllers via ajax
                    case 'notification':    $verified = true; break; // controllers via ajax
                    default:                break;
                }
            }

            // if they can't be here, send them to the most useful page
            if (! $verified) {
                header('Location: '.$root_local.$redirectpage);
                die();
            }
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

        public static function NullableInt($value) {
            return ($value == null ? null : (int)$value);
        }

        public static function BuildCsvFromArray($array, $isInt) {
            $result = '';
            $delim = '';
            $quote = ($isInt ? "" : "'");

            for ($i=0; $i < count($array); $i++) {
                $result .= $delim.$quote.$array[$i].$quote;
                $delim = ',';
            }

            return $result;
        }
    }
?>
