<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['error'])) {

	$postData = $_POST;

	// DEBUG TO LOG
    file_put_contents('./cc_decline.log', json_encode($_POST).',', FILE_APPEND);

    $mail = new PHPMailer(true);
	try {
	    //Server settings
	    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
	    $mail->isSMTP();                                      // Set mailer to use SMTP
	    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	    $mail->SMTPAuth = true;                               // Enable SMTP authentication
	    $mail->Username = 'email';                 // SMTP username
	    $mail->Password = 'password';                           // SMTP password
	    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	    $mail->Port = 587;                                    // TCP port to connect to

	    //Recipients
	    $mail->setFrom('robot@airslamit.com', 'Airslamit Robot');
	    $mail->addAddress('mark@airslamit.com');           
	    $mail->addCC('randy@airslamit.com');        
	   
	    //Content
	    $mail->isHTML(true); // Set email format to HTML
	    $mail->Subject = 'CC Declined for user '.$postData['payload']['email'].' - Airslamit Store';
	    $mail->Body    = '<p>An order has been declined on Airslamit store. Error message was :</p>';
	    $mail->Body    .= '<p>'.$postData['error'].'</p>';
	    $mail->Body    .= '<p><b>Payment method</b>:'.$postData['payload']['paymentMethod'].'</p>';
	    $mail->Body    .= '<p>Billing information :</p>';
	    $mail->Body    .= '<p><b>First name</b>: '.$postData['payload']['billingAddress']['firstName'].'</p>';
	    $mail->Body    .= '<p><b>Last name</b>: '.$postData['payload']['billingAddress']['lastName'].'</p>';
	    $mail->Body    .= '<p><b>Company</b>: '.$postData['payload']['billingAddress']['company'].'</p>';
	    $mail->Body    .= '<p><b>Telephone</b>: '.$postData['payload']['billingAddress']['telephone'].'</p>';
	    $mail->Body    .= '<p><b>Street</b>: '.$postData['payload']['billingAddress']['street'][0].'</p>';
	    $mail->Body    .= '<p><b>Street additional </b>: '.$postData['payload']['billingAddress']['street'][1].'</p>';
	    $mail->Body    .= '<p><b>City</b>: '.$postData['payload']['billingAddress']['city'].'</p>';
	    $mail->Body    .= '<p><b>Region</b>: '.$postData['payload']['billingAddress']['region'].'</p>';
	    $mail->Body    .= '<p><b>Country ID</b>: '.$postData['payload']['billingAddress']['countryId'].'</p>';
	    $mail->Body    .= '<p><b>Postcode</b>: '.$postData['payload']['billingAddress']['postcode'].'</p>';

	    $mail->send();
	    die('Message has been sent');
	} catch (Exception $e) {
	    die('Mailer Error: ' . $mail->ErrorInfo);
	}
} else {
	die('BAD REQUEST');
}