<?php

/*
 * 
 */

namespace crazedsanity\bitwise;
/**
 * Derived from an example http://www.stevenmcmillan.co.uk/blog/2011/php-simple-permission-framework-with-bitwise-operations/
 *
 * @author danf
 */
class Bitwise {
	
	protected $lastBit=null;
	
	//	public function __construct($db) {
	//		$this->db = $db;
	//	}
	
	/**
	 * 
	 * @param type $name
	 * @param type $value
	 * @throws \InvalidArgumentException
	 * @codeCoverageIgnore
	 */
	public function __set($name, $value) {
		switch($name) {
			case 'lastBit':
				$this->lastBit = $value;
				break;
			
			default:
				throw new \InvalidArgumentException;
		}
	}
	
	
	public static function isValid($bit) {
		$retval = false;
		if(is_int($bit)) {
			$retval = (($bit != 0) && (($bit & ($bit - 1)) == 0));
		}
		else {
			throw new \InvalidArgumentException(__METHOD__ .": value (". $bit .") is not an integer");
		}
		return $retval;
	}
	
	
	public function nextBit($lastBit=null) {
		if(!is_null($lastBit)) {
			if(self::isValid($lastBit)) {
				$this->lastBit = $lastBit;
			}
			else {
				throw new \InvalidArgumentException;
			}
			$this->lastBit = $this->lastBit * 2;
		}
		elseif(is_null($this->lastBit)) {
			$this->lastBit = 1;
		}
		else {
			$this->lastBit = $this->lastBit * 2;
		}
		
		return $this->lastBit;
	}//end nextBit()
	
	
	
	public static function addAccess($originalPerm, $newPerm) {
		if($originalPerm == 0 || is_null($originalPerm)) {
			$retval = $newPerm;
		}
		elseif(self::isValid($newPerm)) {
			$retval = $originalPerm;
			if(!self::canAccess($originalPerm, $newPerm)) {
				$retval = $originalPerm + $newPerm;
			}
		}
		else {
			throw new \InvalidArgumentException;
		}
		return $retval;
	}//end addAccess()
	
	
	
	public static function canAccess($permToCheck, $hasBit) {
		$retval = false;
		if(self::isValid($hasBit)) {
			$retval = (bool)($permToCheck & $hasBit);
		}
		else {
			throw new \InvalidArgumentException;
		}
		return $retval;
	}
	
	
	public static function removeAccess($originalPerm, $removePerm) {
		if($originalPerm == 0 || is_null($originalPerm)) {
			$retval = $originalPerm;
		}
		elseif($originalPerm < 0 || !self::isValid($removePerm)) {
			throw new \InvalidArgumentException;
		}
		else {
			$retval = $originalPerm - $removePerm;
		}
		return $retval;
	}
}
