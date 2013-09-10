<?php

namespace model;

/**
 * returns Swedish date and time (Swedish)
 * @return String
 */

class LocalTime{

	public static function getLocalTime() {

	setlocale(LC_ALL, 'sv_SE');
	
	$localTimeString = strftime('%A, den %e %B år %G. Klockan är [%H:%M:%S]' , time());
	
	return $localTimeString;
	}
}