<?php
namespace Sy\Component\Html;

class Form extends Form\FieldContainer {

	private static $instances = 0;

	private $formId = 0;

	private $success;

	public function __construct(array $attributes = array('action' => NULL, 'method' => 'post')) {
		parent::__construct();
		$this->setTemplateFile(__DIR__ . '/Form/templates/Form.tpl', 'php');
		$this->formId = ++self::$instances;
		if (is_null($attributes['action'])) {
			$attributes['action'] = $_SERVER['REQUEST_URI'];
			$this->addHidden(array('name' => 'formAction' . $this->formId, 'value' => 'submit'));
		}
		$this->setAttributes($attributes);
		$this->success = false;
		$this->init();
		if ($this->request('formAction' . $this->formId) == 'submit') {
			$info = $this->getDebugTrace();
			$info['type'] = 'Form submit';
			$message = 'Call method ' . get_class($this) . '::submitAction';
			$this->log($message, $info);
			$this->submitAction();
		}
	}

	/**
	 * Validate the form
	 *
	 * @param array $values
	 * @return boolean
	 */
	public function isValid($values) {
		$valid = parent::isValid($values);
		if ($valid) {
			$this->success = true;
		} else {
			$this->setError(true);
		}
		return $valid;
	}

	public function __toString() {
		$this->setVar('SUCCESS', $this->success);
		$this->setVar('ACTION', $this->getAttribute('action'));
		return parent::__toString();
	}

	public function init() {

	}

	public function submitAction() {

	}

}