<?php
$to = 'info@bergundkraft.com';
$subject = 'test';
$headers = 'From: rohit@icreativetechnolabs.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();;
$message = 'for testing gmail sending';
//mail($to, $subject, $message, $headers);
if (mail($to, $subject, $message, $headers))
{
    echo "hello". $headers;
}

?>