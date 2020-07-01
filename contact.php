<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

//Setting up mailer
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";
$mail->SMTPDebug  = 0;  
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "rupalinteriormail@gmail.com";
$mail->Password   = "rupalinterior";

//mysqlConnection is MySQL connection object
$mysqlDB = 'rupalinteriordb';
$mysqlUsername = 'root';
$mysqlPassword = '';
$mysqlHost = 'localhost';
$mysqlConnection=mysqli_connect($mysqlHost, $mysqlUsername, $mysqlPassword);

if(!$mysqlConnection)
{
    echo "Error estabilishing connection with MySQL cluster. Try again after some time";
    return;
}
if(!mysqli_select_db($mysqlConnection, $mysqlDB))
{
    echo "No Database found!";
    return;
}

$name=$_POST['name'];
$phone=$_POST['phone'];
$company=$_POST['company'];
$email=$_POST['email'];
$message=$_POST['message'];


//Composing and sending the mail
$mail->IsHTML(true);
$mail->AddAddress($email, $name);
$mail->SetFrom('rupalinterior@gmail.com', 'Rupal Interior');
$mail->Subject = 'Hi! '.$name.', Your response on Rupal Interior has been sent to us.';
$content = '<b>'.$message.'</b>';




//Saving the response in database
$query="INSERT INTO contacts(name, phone , company, email, message)
VALUES ('$name','$phone','$company','$email','$message')";

if(!mysqli_query($mysqlConnection, $query))
{
	echo "Error while inserting the document";

}
else
{
    echo "You response has been saved. We will get to you shortly! Email has been sent to you. Redirecting to website ......";
    
    //Sending Mail
    $mail->MsgHTML($content); 
    if(!$mail->Send()) {
    echo "Error while sending Email.";
    var_dump($mail);
    } else {
    echo "Email sent successfully";
    }

}

header("refresh:2;url=contact.html");

?>