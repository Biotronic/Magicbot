<?php

function uniq($arr, $fn) {
    $alreadyFound = array();
	$result = array();
    foreach($arr as $elem) {
		$tmp = $fn($elem);
		if (in_array($tmp, $alreadyFound)) {
			continue;
		}
		$alreadyFound[] = $tmp;
		$result[] = $elem;
	}
	return $result;
}

class Cards {
	public function __construct($filename) {
		$this->load_cards($filename);
	}
	
	public function find_card($name) {
		if (preg_match("/^\s*(.*\S)\s*\[([^,]*)(\s*,\s*(\d+))?\]\s*$/", $name, $parts)) {
			$setCode = $parts[2];
			$variant = $parts[4];
			$name = $parts[1];
		}

		$name = trim($name);
		
		$diff = $this->maxDiff;
		$found = array();
		foreach ($this->cards as $card) {
			$card['variant'] = $variant;
			$this->compare_card($name, $card, 'name', $found, $diff);
			$this->compare_card($name, $card, 'prefix', $found, $diff);
		}
				
		if ($setCode) {
			$this->filter_sets($found, $setCode);
		}
		
		$this->filter_unique($found);
		
		return $found;
	}
	
	private function filter_unique(&$found) {
		$found = uniq($found, function($elem){ return strtolower($elem['name']); });
	}
	
	private function filter_sets(&$found, $setCode) {
		$tmp = array_filter($found, function($elem) use ($setCode) {
				return strtolower($elem['set']) == $setCode;
			});
		if (count($tmp) > 0) {
			$found = $tmp;
		}
	}
	
	private function load_cards($filename) {	
		$cardtext = file($filename);
		
		foreach ($cardtext as $card) {
			preg_match("/^(\d+)\t(\S+)\t(.*)$/", $card, $matches);
			preg_match("/([^,]+),/", $matches[3], $prefix);
			
			$this->cards[] = array(
					"id" => $matches[1],
					"set" => $matches[2],
					"name" => $matches[3],
					"image_url" => $this->cleanup_name($matches[3]),
					"prefix" => $prefix[1]
					);
		}
	}
	
	private function cleanup_name($name) {
		return trim(preg_replace('/[!"&\'(),-.:?_\\s]+/', '+', $name), ' +');
	}
	
	private function compare_card($name, $card, $key, &$found, &$diff) {
		$tmpDiff = levenshtein(strtolower($card[$key]), $name);
		
		if ($tmpDiff < $diff) {
			$diff = $tmpDiff;
			$found = array();
		}
		if ($tmpDiff <= $diff) {
			$found[] = $card;
		}
	}
	
	private $cards;
	private $maxDiff = 3;
}
?>