<?php

/**
 * Email configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * transport => The name of a supported transport; valid options are as follows:
 *		Mail 		- Send using PHP mail function
 *		Smtp		- Send using SMTP
 *		Debug		- Do not send the email, just return the result
 *
 * You can add custom transports (or override existing transports) by adding the
 * appropriate file to app/Network/Email. Transports should be named 'YourTransport.php',
 * where 'Your' is the name of the transport.
 *
 * from =>
 * The origin email. See CakeEmail::from() about the valid values
 *
 */
class EmailConfig {

	// !! This app uses an extended AppEmail class. See Lib/Network/AppEmail.php for usage. !!

	/**
	 * Production configuration, use the server's mail config.
	 *
	 * @access	public
	 * @var	array
	 */
	public $default = array(
		'transport' => 'Mail',
		'from' => 'no-reply@_PROJECT_DOMAIN_.com',
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
		'emailFormat' => 'html',
		'log' => true,

		//'sender' => null,
		//'to' => null,
		//'cc' => null,
		//'bcc' => null,
		//'replyTo' => null,
		//'readReceipt' => null,
		//'returnPath' => null,
		//'messageId' => true,
		//'subject' => null,
		//'message' => null,
		//'headers' => null,
		//'viewRender' => null,
		//'template' => false,
		//'layout' => false,
		//'viewVars' => null,
		//'attachments' => null,
		//'host' => 'localhost',
		//'port' => 25,
		//'timeout' => 30,
		//'username' => 'user',
		//'password' => 'secret',
		//'client' => null,
	);

	/**
	 * Staging configuration, use the server's mail config.
	 *
	 * @access	public
	 * @var	array
	 */
	public $staging = array(
		'transport' => 'Mail',
		'from' => 'no-reply@_PROJECT_DOMAIN_.com',
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
		'emailFormat' => 'html',
		'log' => true,
	);

	/**
	 * In development, only dump emails to the log file.
	 *
	 * @access	public
	 * @var	array
	 */
	public $vagrant = array(
		'transport' => 'Debug',
		'from' => 'no-reply@_PROJECT_DOMAIN_.com',
		'charset' => 'utf-8',
		'headerCharset' => 'utf-8',
		'emailFormat' => 'html',
		'log' => true,
	);

	/**
	 * Set up dynmaic configs and set the appropriate configuration based on
	 * the APP_ENV environment variable.
	 *
	 * If the APP_ENV env var is not set, or set to something that does not
	 * match one of the available configurations above, 'default' will be used.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {
		// Determine which config is the "default" for the given environment.
		$available = array_keys(get_class_vars(get_class($this)));
		$env = getenv('APP_ENV');
		$env = (in_array($env, $available) ? $env : 'default');
		$this->default = $this->{$env};
	}
}
