<?php
/*!
 * @desc Form Mailer - Mail posted form information to recipient.
 * @author Shawn Melton <shawn.a.melton@gmail.com>
 * @version $Id: $
 */
class FormMail {
	static function send($domain) {
		$info = array();
		foreach( $_POST as $field => $value ) {
			if( $value != '' ) {
				$info[] = ucwords(str_replace('_', ' ', $field)) .': '. $value;
			}
		}
		
		$site = new Site();
		$site->load(dirname(dirname(__FILE__)) .'/httpdocs/themes/'. $domain .'/');
		$formName = ucwords(str_replace('_', ' ', $_POST['fns']));
		
		$message = 'A new '. $formName .' submission has been sent using the '. str_replace('&amp;', '&', $site->name) .' mobile site. The following information was submitted:'."\r\n\r\n". join("\r\n", $info);
		
		$headers = array('From: '. $site->emailname .' Mobile <mobile@'. $site->domain .'>');
    	$headers[] = 'MIME-Version: 1.0'; 
    	$headers[] = 'Content-type: text/plain; charset=utf-8';
    	$headers[] = 'Content-Transfer-Encoding: 8bit';
		
		$recipients = $site->getFormInfo($_POST['fns'], 'recipients');
		//$recipients = 'tests@webteks.com';
		mail($recipients, $site->emailname .' Mobile > '. $formName .' Submission', $message, $headers);
		echo json_encode(array('status' => 'success'));
	}
}