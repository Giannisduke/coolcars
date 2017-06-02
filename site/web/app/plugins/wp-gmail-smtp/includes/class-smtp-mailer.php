<?php

class WPGmail_Mailer {

	private $options;
	private $host = 'smtp.gmail.com';

	static function Init( $options ) {
		$obj = new WPGmail_Mailer();
		$obj->options = $options;
		//Add an action on phpmailer_init
		add_action( 'phpmailer_init', array( $obj, 'phpmailer_init_smtp' ) );
		//Add filters to replace the mail from name and emailaddress
		add_filter( 'wp_mail_from', array( $obj, 'mail_smtp_mail_from' ) );
		add_filter( 'wp_mail_from_name', array( $obj, 'mail_smtp_mail_from_name' ) );
	}

	function mail_smtp_mail_from( $orig ) {

		// Get the site domain and get rid of www.
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$default_from = 'wordpress@' . $sitename;

		// If the from email is not the default, return it unchanged
		if ( $orig != $default_from ) {
			return $orig;
		}

		if ( !$this->options['is_active'] || $this->options['is_active'] == 'no' ) {
			return $orig;
		}
		if ( isset( $this->options['from_email'] ) && is_email( $this->options['from_email'], false ) ) {
			return $this->options['from_email'];
		}

		// If in doubt, return the original value
		return $orig;
	}

	function mail_smtp_mail_from_name( $orig ) {

		if ( !$this->options['is_active'] || $this->options['is_active'] == 'no' ) {
			return $orig;
		}
		// Only filter if the from name is the default
		if ( $orig == 'WordPress' ) {
			if ( isset( $this->options['from_name'] ) && $this->options['from_name'] != "" && is_string( $this->options['from_name'] ) ) {
				return $this->options['from_name'];
			}
		}

		// If in doubt, return the original value
		return $orig;
	}

	function phpmailer_init_smtp( $phpmailer ) {

		// Check that mailer is not blank, and if mailer=smtp, host is not blank
		if ( !$this->options['is_active'] || $this->options['is_active'] == 'no' ) {
			return;
		}

		// Set the mailer type as per config above, this overrides the already called isMail method
		$phpmailer->Mailer = 'smtp';

		// Set the Sender (return-path) if required
		if ( isset($this->options['mail_set_return_path']) )
			$phpmailer->Sender = $phpmailer->From;

		// If we're sending via SMTP, set the host
		// Set the SMTPSecure value, if set to none, leave this blank
		$phpmailer->SMTPSecure = $this->options['smtp_encryption'] == 'none' ? '' : $this->options['smtp_encryption'];


		// Set the other options
		$phpmailer->Host = $this->host;
		if ( $this->options['smtp_encryption'] == 'ssl' ) {
			$phpmailer->Port = '465';
		} else {
			$phpmailer->Port = '587';
		}

		// If we're using smtp auth, set the username & password
		if ( $this->options['smtp_authentication'] == "true" ) {
			$phpmailer->SMTPAuth = true;
			$phpmailer->Username = $this->options['smtp_username'];
			$phpmailer->Password = $this->options['smtp_password'];
		}

		$phpmailer = apply_filters( 'wp_gmail_smtp_mailer', $phpmailer );
	}

}
