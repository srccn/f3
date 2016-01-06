<?php 

class MailController {
	
	private $to;
	private $subject;
	private $msg;
	private $From;
	private $CC;
	private $header = "From: webmaster@example.com\r\n";
	
	
	function setMailHeader() {
		$this->$headers = "From: webmaster@example.com" ."\r\n" ;
	}
	
	function mailRateNotice($user, $user_email, $rate_target, $rate_real) {
		$msg_template = 
"Hello $user: \n 
The target rate $rate_target with your mortgage rate monitor setting has been reached, current rate is $rate_real !\n
Please contact us ASAP by calling 617-456-7890 to confirm and lock this rate.\n
thanks
-- Mortgage Pro rate monitor ";
		
		$subject= "Your rate target reached";
		
		mail($user_email, $subject, $msg_template, $this->header);
		echo "Done";
	}
	
	function testMailNotice () {
		$this->mailRateNotice("Rate notice user", "user@test.com", "4.125", "4.000");
	}
	
	function mailTest() {
		$headers = $this->header ;
		$to = "someone@example.com";
		$subject = "My subject";
		$msg = "Hello from php email";
		// $headers .= "CC: somebodyelse@example.com";

		if (empty($to) || empty($subject) || empty($msg) || empty($headers) ) {
			Util::dump("Failed send email ". $to .";".$subject.";".$msg.";".$From );
		} else {
			Util::dump("Send email ". $to .";".$subject.";".$msg.";" );
			mail($to, $subject, $msg, $headers);
		}
		
	}
	
	
}

?>