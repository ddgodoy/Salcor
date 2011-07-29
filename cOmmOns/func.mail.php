<?php
	function send_mail($emailaddress, $fromaddress, $emailsubject, $html, $texto='', $copia='', $oculta=''){
		$mime_boundary = md5('pinika');
		$resultado = false;
		$message = '';

		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			$eol = "\r\n";
		} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
			$eol = "\r";
		} else {
			$eol = "\n";
		}
		$headers = "From: $fromaddress".$eol;
		if (!empty($copia)){
			$headers .= "Cc: $copia".$eol;
		}
		if (!empty($oculta)){
			$headers .= "Bcc: $oculta".$eol;
		}
		$headers.= "Reply-To: $fromaddress".$eol;
		$headers.= "Return-Path: $fromaddress".$eol;
		$headers.= "MIME-Version: 1.0".$eol;
		$headers.= "X-Mailer: PHP v".phpversion().$eol;
		$headers.= "Message-ID: <".uniqid('')."@".$_SERVER['SERVER_NAME'].">".$eol;
		$headers.= "Content-Type: multipart/alternative; boundary=$mime_boundary".$eol.$eol;

		if (!empty($texto)){
			$message.= "--$mime_boundary".$eol;
			$message.= "Content-Type: text/plain; charset=ISO-8859-1".$eol;
			$message.= "Content-Transfer-Encoding: base64".$eol.$eol;
			$message.= chunk_split(base64_encode($texto));
		}
		$message.= "--$mime_boundary".$eol;
		$message.= "Content-Type: text/html; charset=ISO-8859-1".$eol;
		$message.= "Content-Transfer-Encoding: base64".$eol.$eol;
		$message.= chunk_split(base64_encode($html));

		ini_set("sendmail_from",$fromaddress);
		if (mail($emailaddress, $emailsubject, $message, $headers)){
			$resultado = true;
		}
		ini_restore("sendmail_from");
		return $resultado;
	}
/*---------------------------------------------------------------------------*/
	function has_no_newlines($text){return preg_match("/(%0A|%0D|\\n+|\\r+)/i", $text) == 0;}
/*---------------------------------------------------------------------------*/
	function has_no_emailheaders($text){return preg_match("/(%0A|%0D|\\n+|\\r+)(content-type:|to:|cc:|bcc:)/i", $text) == 0;}
?>