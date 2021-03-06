<?php
namespace Sy\Translate;

class GettextTranslator extends Translator implements ITranslator {

	public function loadTranslationData() {
		$data = array();
		$lang = $this->getTranslationLang();
		$dir  = $this->getTranslationDir();
		if (file_exists("$dir/$lang.mo")) {
			$data = $this->readData("$dir/$lang.mo");
		}
		$this->setTranslationData($data);
	}

	private function readData($filename) {
		$data = array();

		if (file_exists($filename)) {
			$file = @fopen($filename, 'rb');

			if (!$file) {
				throw new \Exception($filename . ' not found');
			}

			if (filesize($filename) < 10) {
				@fclose($file);
				throw new \Exception($filename . ' is not a MO file');
			}

			// Get Endian
			$input = $this->readMOData($file, 1);

			if (strtolower(substr(dechex($input[1]), -8)) == "950412de") {
				$bigEndian = false;
			} else if (strtolower(substr(dechex($input[1]), -8)) == "de120495") {
				$bigEndian = true;
			} else {
				@fclose($file);
			}

			// Read revision
			$input = $this->readMOData($file, 1, $bigEndian);

			// Number of bytes
			$input = $this->readMOData($file, 1, $bigEndian);
			$total = $input[1];

			// Number of original strings
			$input = $this->readMOData($file, 1, $bigEndian);
			$OOffset = $input[1];

			// Number of translation strings
			$input = $this->readMOData($file, 1, $bigEndian);
			$TOffset = $input[1];

			// Fill the original table
			fseek($file, $OOffset);
			$origtemp = $this->readMOData($file, 2 * $total, $bigEndian);
			fseek($file, $TOffset);
			$transtemp = $this->readMOData($file, 2 * $total, $bigEndian);

			for($count = 0; $count < $total; ++$count) {
				if ($origtemp[$count * 2 + 1] != 0) {
					fseek($file, $origtemp[$count * 2 + 2]);
					$original = @fread($file, $origtemp[$count * 2 + 1]);
					$original = explode("\0", $original);
				} else {
					$original[0] = '';
				}

				if ($transtemp[$count * 2 + 1] != 0) {
					fseek($file, $transtemp[$count * 2 + 2]);
					$translate = fread($file, $transtemp[$count * 2 + 1]);
					$translate = explode("\0", $translate);
					if ((count($original) > 1) && (count($translate) > 1)) {
						$data[$locale][$original[0]] = $translate;
						array_shift($original);
						foreach ($original as $orig) {
							$data[$orig] = '';
						}
					} else {
						$data[$original[0]] = $translate[0];
					}
				}
			}
			@fclose($file);
		}
		return $data;
	}


	/**
     * Read values from the MO file
     *
	 * @param string $file file handler
     * @param string $bytes
	 * @param bool   $bigEndian
     */
    private function readMOData($file, $bytes, $bigEndian = false) {
        if ($bigEndian === false) {
            return unpack('V' . $bytes, fread($file, 4 * $bytes));
        } else {
            return unpack('N' . $bytes, fread($file, 4 * $bytes));
        }
    }

}
