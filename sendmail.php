<?php

// Create the headers
$headers = 'Content-type: text/html' . "\r\n" .'From: FeedbackDeliverySystem' . "\r\n" .
    'Reply-To: i.d.c.k.wong-11@student.lboro.ac.uk';

session_start();
$moduleCode = $_POST[ 'moduleCode'];
$message = $_POST[ 'dataString'];
$subject = "Feedback for " . $moduleCode;
$emailAddress = $_POST[ 'emailAddress'];

mail( $emailAddress, $subject, $message, $headers);

header('location: mymodules.php');

?>