<?php
class Kohana_Recaptcha {
	protected $_config;
	protected $_error = NULL;
	public function __construct(array $config = NULL)
	{
		$this->_config = $config;

		if ($config === NULL)
		{
			// Load the configuration for this database
			$this->_config = Kohana::$config->load('recaptcha')->default;
		}

		include_once Kohana::find_file('vendor', 'recaptcha/recaptchalib');

		return $this;
	}
	public function check($answers,$fields=array("recaptcha_challenge_field","recaptcha_response_field"))
	{
		$recaptcha_resp = recaptcha_check_answer($this->_config['private_key'],
													$_SERVER['REMOTE_ADDR'],
													$answers[$fields[0]],
													$answers[$fields[1]]);

		$this->_error=$recaptcha_resp->error;
		return $recaptcha_resp->is_valid;
	}
	public function error()
	{
		return $this->_error;
	}
	public function html()
	{
		return recaptcha_get_html($this->_config['public_key'], $this->_error);
	}
	public function public_key() {
		return $this->_config['public_key'];
	}

	public static function validRecaptcha($subject) {
		$Recaptcha = new Recaptcha();

		if($Recaptcha->check($_POST)) {
			return true;
		} else {
			return false;
		}
	}
}