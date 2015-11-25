<?php
class ApiException extends Exception {
	
	private $mDetailCode;
	private $mDetailMessage;

	public function __construct($message, $code, $detailMessage = '', $detailCode = 0, Exception $previous = null) {
			$this->mDetailCode = $detailCode;
			$this->mDetailMessage = $detailMessage;
			parent::__construct($message, $code, $previous);
	}

	public function getError() {
			return array(
				'code' => $this->code,
				'message' => $this->message
			);
	}

	public function __toString() {
			return $this->getString();
	}

	public function getString() {
			return "({$this->code}): {$this->message}: ({$this->mDetailCode}): {$this->mDetailMessage}\n";
	}

	public function getDetailCode() {
			return $this->mDetailCode;
	}

	public function getDetailMessage() {
			return $this->mDetailMessage;
	}
}
