<?php
// test sending emails (texts)
$result = null;
$isError = false;
$errorMessage = '';

if (isset($_REQUEST['to'])) {
    $to = $_REQUEST['to'];
} else {
    $to = '2153606902@vtext.com';
}

$subject = 'Notification from W4M';
$body = 'New company registered';
$headers = "From: admin@wheels4meals.org" . PHP_EOL;
$headers .= "Cc: brimer@gmail.com" . PHP_EOL;

$body .= ' ' . PHP_EOL . 'http://wheels4meals.org';

try {
    $isError = ! mail($to, $subject, $body, $headers);
} catch (Exception $ex) {
    $isError = true;
    $errorMessage = $ex->getMessage();
} catch (Error $er) {
    $isError = true;
    $errorMessage = 'dunno';
}

$result = array('error' => $isError, 'errorMessage' => $errorMessage);

echo json_encode($result);
?>
