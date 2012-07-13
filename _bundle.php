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
	public function date($time = 'now', $zone = null) {

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

		// If no format is passed use the saved one
		if(empty($format)) $format = $this->format;

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
	 * Is the currently loaded date in the future
	 */
	public function isFutureDate($date = null, $zone = null) {

		// If specifying a specific date pass thru to the date function
		if(!empty($date)) $this->date($date, !is_null($zone) ? $zone : null);

		/**
		 * Get the time difference
		 */
		$nowTime = new DateTime('now', new DateTimeZone('UTC'));
		$timeDiff = $nowTime->diff($this->date);

		/**
		 * Do the math
		 */
		if($timeDiff->invert)
			return false;
		else return true;
	}

	/**
	 * Time Since Function
	 */
	public function timeSince($date = null, $zone = null) {

		// If specifying a specific date pass thru to the date function
		if(!empty($date)) $this->date($date, !is_null($zone) ? $zone : null);

		/**
		 * Get the time difference
		 */
		$nowTime = new DateTime('now', new DateTimeZone('UTC'));
		$timeDiff = $nowTime->diff($this->date);

		// We only want to process past dates
		if(!$timeDiff->invert) throw new Exception("The date you specified is in the future");

		/**
		 * Bluralize the word if the count is greater then one
		 */
		$plural = function($c, $t) {
			return $c.' '.($c > 1 ? $t.'s' : $t);
		};

		/**
		 * Return rough estimate of time since the date
		 */
		if($timeDiff->y > 0) return $plural($timeDiff->y, 'year').' ago';
		if($timeDiff->m > 0) return $plural($timeDiff->m, 'month').' ago';
		if($timeDiff->d > 0) return $plural($timeDiff->d, 'day').' ago';
		if($timeDiff->h > 0) return $plural($timeDiff->h, 'hour').' ago';
		if($timeDiff->i > 0) return $plural($timeDiff->i, 'minute').' ago';
		return $plural($timeDiff->s, 'second').' ago';
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