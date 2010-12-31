<?php
namespace Sy\Form;

class Checkbox extends Input implements FillableElement, ValidableElement {
	
	public function __construct() {
		parent::__construct('checkbox');
	}

	public function fill($value) {
		if (is_null($value)) return;
		if (is_array($value)) {
			if (in_array($this->getAttribute('value'), $value))
				$this->setAttribute('checked', 'checked');
		}
		if ($this->getAttribute('value') == $value)
			$this->setAttribute('checked', 'checked');
	}

	public function isValid($value) {
		if ($this->isRequired()) {
			if (!isset($value) or $value === '') {
				$this->setError(true);
				return false;
			}
		}
		return true;
	}
	
}