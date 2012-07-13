<?php

namespace Bundles\DateTime;
use DateTimeZone;
use Exception;
use DateTime;
use e;

/**
 * Date Time Bundle
 * @author Kelly Becker
 * @since July 13th, 2012
 */
class Bundle {

	private $date = null;
	private $zone = 'UTC';
	private $format = 'Y-m-d H:i:s';

	/**
	 * Load a date or timestamp
	 */
	public function date($time = null, $zone = null) {
		if(empty($time)) $time = time();

		// Set the timezome
		if(!empty($zone)) $this->zone = $zone;

		/**
		 * Create a new Date Time Instance
		 */
		$this->date = new DateTime(
			is_numeric($time) ? '@'.$time : $time,
			new DateTimeZone($this->zone)
		);

		// Return date
		return $this->date->format($this->format);
	}

	/**
	 * Request a date returned in a specific format
	 */
	public function getFormat($format = null, $reset = false) {

		// If no format is passed reset the object
		if(empty($format)) throw new Exception("You cannot call `e::\$datetime->format()` without specifing a date format.");

		// Reset the date object
		if($reset) $this->date();

		// Set the format to the object
		$this->format = $format;

		// Return date
		if(!empty($this->date))
			return $this->date->format($this->format);
	}

	/**
	 * Request that the date be returned as timestamp format
	 */
	public function getTimestamp($reset = false) {

		// Reset the date object
		if($reset || empty($this->date)) $this->date();

		// Return timestamp
		return $this->date->getTimestamp();
	}

	/**
	 * Set the timezone to be used
	 */
	public function setTimezone($zone = 'UTC') {

		/**
		 * Set the timezone
		 */
		$this->zone = $zone;
		$this->date->setTimezone(new DateTimeZone($this->zone));

		// Return the timezone
		return $this->zone;
	}

	/**
	 * Get the timezone
	 */
	public function getTimezone() {

		// Return the timezone
		return $this->zone;
	}

	/**
	 * Get a listing of available timezones
	 */
	public function timeZones() {
		return DateTimeZone::listIdentifiers();
	}

	/**
	 * Reset the object
	 */
	public function reset() {
		$this->date = null;
		$this->zone = 'UTC';
		$this->format = 'Y-m-d H:i:s';
	}

}