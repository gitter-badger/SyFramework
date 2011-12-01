<?php
namespace Sy\Generator;

use Sy\Component;
use Sy\Code\Classe;
use Sy\Code\Property;
use Sy\Code\Method;
use Sy\Code\Parameter;

class Project {

	private $path;
	private $name;

	public function __construct($path, $name) {
		$this->path = $path;
		$this->name = $name;
	}

	public function generate() {
		$this->generateFile($this->name . '/Component/Application.php', 'Project/Component/Application.php');
		$this->generateFile($this->name . '/Component/Application/templates/Application.html', 'Project/Component/Application/templates/Application.html');
		$this->generateFile($this->name . '/Component/Application/templates/Home.html', 'Project/Component/Application/templates/Home.html');
		$this->generateFile('index.php');
		$this->generateFile('index_dev.php');
		$this->generateFile('conf/conf.php');
		$this->generateFile('conf/conf.default.php');
		$this->generateFile('conf/inc.php');
	}

	private function generateFile($outputFile, $inputFile = NULL) {
		$c = new Component();
		$c->setTemplateFile(is_null($inputFile) ? __DIR__ . "/Project/templates/$outputFile" : __DIR__ . "/Project/templates/$inputFile");
		$c->setVar('PROJECT_NAME', $this->name);
		$file = new File($this->path . DIRECTORY_SEPARATOR . $outputFile, $c->__toString());
		$file->generate();
	}

}