<?php



// The message
$message = "Line 1\r\nLine 2\r\nLine 3";
$message = str_replace("\n.", "\n..", $message);

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

$headers = 'Content-type: text/html' . "\r\n" .'From: FeedbackDeliverySystem@example.com' . "\r\n" .
    'Reply-To: i.d.c.k.wong-11@student.lboro.ac.uk';

session_start();
$moduleCode = $_POST[ 'moduleCode'];
$message = $_POST[ 'dataString'];
$subject = "Feedback for " . $moduleCode;
$emailAddress = $_POST[ 'emailAddress'];

//echo $a;
mail( $emailAddress, $subject, $message, $headers);

header('location: mymodules.php');

?>