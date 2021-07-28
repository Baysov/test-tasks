<?php

namespace test_tasks_secure_code\Libs\PHPMailer_5_2_4_sendmail;

//----------------------------------------------------
if (!empty($smtp_gmail_config['From_email'])
and !empty($smtp_gmail_config['From_login_email'])
and !empty($smtp_gmail_config['Password'])
and !empty($smtp_gmail_config['FromName'])
and !empty($smtp_gmail_config['To_email'])
and !empty($smtp_gmail_config['To_name'])
and !empty($smtp_gmail_config['Subject'])
and !empty($smtp_gmail_config['Body'])
)
{
	error_reporting(0);
	router::spl_autoload_register('class.phpmailer');
	
	$mail = new PHPMailer();
	
	$mail->CharSet = 'utf-8';
	$mail->IsSMTP();
//	$mail->SMTPDebug = 2;
	$mail->From = $smtp_gmail_config['From_email'];
	$mail->FromName = $smtp_gmail_config['FromName'];
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465;
	$mail->SMTPAuth = true;
	$mail->Username = $smtp_gmail_config['From_login_email'];
	$mail->Password = $smtp_gmail_config['Password'];
	$mail->AddAddress($smtp_gmail_config['To_email'],$smtp_gmail_config['To_name']);
	//$mail->AddReplyTo('name@gmail.com','Reply Name');
	$mail->WordWrap = 50;
	
	$mail->IsHTML(true);
	$mail->Subject = $smtp_gmail_config['Subject'];
	$mail->Body = $smtp_gmail_config['Body'];
	
	if($mail->Send()) { print 'send_msg_complete'; }
	else
	{
	 print 'error send';
	 $error_send_mail = true; // trow error
	}
}

?>