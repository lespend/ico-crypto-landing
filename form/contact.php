<?php
header('Content-type: application/json');
require_once('php-mailer/PHPMailerAutoload.php'); // Include PHPMailer

$mail = new PHPMailer();
$emailTO = $emailBCC =  $emailCC = array(); $formEmail = '';

### Enter Your Sitename 
$sitename = 'ICO CRYPTO';

### Enter your email addresses: @required
$emailTO[] = array( 'email' => 'baydakov.17@yandex.ru', 'name' => 'Your Name' ); 

### Enable bellow parameters & update your BCC email if require.
//$emailBCC[] = array( 'email' => 'email@yoursite.com', 'name' => 'Your Name' );

### Enable bellow parameters & update your CC email if require.
//$emailCC[] = array( 'email' => 'email@yoursite.com', 'name' => 'Your Name' );

### Enter Email Subject
$subject = "Обмен криптовалют" . ' - ' . $sitename; 

### If your did not recive email after submit form please enable below line and must change to your correct domain name. eg. noreply@example.com
//$formEmail = 'noreply@yoursite.com';
	
### Success Messages
$msg_success = "We have <strong>successfully</strong> received your message. We'll get back to you soon.";

if( $_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST["contact-email"]) && $_POST["contact-email"] != '' && isset($_POST["contact-name"]) && $_POST["contact-name"] != '') {
		### Form Fields
		$cf_phone = $_POST["contact-email"];
		$cf_name = $_POST["contact-name"];

		// $honeypot 	= isset($_POST["form-anti-honeypot"]) ? $_POST["form-anti-honeypot"] : 'bot';
		$honeypot = '';
		$bodymsg = '';
		
		if ($honeypot == '' && !(empty($emailTO))) {
			### If you want use SMTP 
			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = 25;
			$mail->SMTPAuth = true;
			$mail->Username = 'thelautix123@gmail.com';
			$mail->Password = 'qabpcxkberfqrgtp';
			
			### Regular email configure
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';

			// //$mail->From = ($formEmail !='') ? $formEmail : $cf_phone;
			// $mail->FromName = $cf_name . ' - ' . $sitename;
			// $mail->AddReplyTo($cf_phone, $cf_name);
			$mail->Subject = $subject;
			
			foreach( $emailTO as $to ) {
				$mail->AddAddress( $to['email'] , $to['name'] );
			}
			
			// ### if CC found
			// if (!empty($emailCC)) {
			// 	foreach( $emailCC as $cc ) {
			// 		$mail->AddCC( $cc['email'] , $cc['name'] );
			// 	}
			// }
			
			// ### if BCC found
			// if (!empty($emailBCC)) {
			// 	foreach( $emailBCC as $bcc ) {
			// 		$mail->AddBCC( $bcc['email'] , $bcc['name'] );
			// 	}				
			// }

			### Include Form Fields into Body Message
			$bodymsg .= isset($cf_name) ? "Имя: $cf_name<br>" : '';
			$bodymsg .= isset($cf_phone) ? "Телефон: $cf_phone<br>" : '';
			//$bodymsg .= $_SERVER['HTTP_REFERER'] ? '<br>---<br><br>This email was sent from: ' . $_SERVER['HTTP_REFERER'] : '';
			
			// Mailing
			$mail->MsgHTML( $bodymsg );
			$is_emailed = $mail->Send();

			if( $is_emailed === true ) {
				$response = array ('result' => "success", 'message' => $msg_success);
			} else {
				$response = array ('result' => "error", 'message' => $mail->ErrorInfo);
			}
			echo json_encode($response);
			
		} else {
			echo json_encode(array ('result' => "error", 'message' => "Bot <strong>Detected</strong>.! Clean yourself Botster.!"));
		}
	} else {
		echo json_encode(array ('result' => "error", 'message' => "Please <strong>Fill up</strong> all required fields and try again."));
	}
}