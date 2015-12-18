<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package roq_newsevent
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_RoqNewsevent_Domain_Model_Event extends \GeorgRinger\News\Domain\Model\News {

    /**
   	 * Is event
   	 *
   	 * @var boolean
   	 */
   	protected $isEvent = FALSE;

	/**
	 * Event start date
	 *
	 * @var DateTime
	 * @validate NotEmpty
	 */
	protected $eventStartdate;

	/**
	 * Event start time
	 *
	 * @var DateTime
	 */
	protected $eventStarttime;

	/**
	 * Event end date
	 *
	 * @var DateTime
	 */
	protected $eventEnddate;

	/**
	 * Event end time
	 *
	 * @var DateTime
	 */
	protected $eventEndtime;

	/**
	 * Even location (City, County)
	 *
	 * @var string
	 */

    protected $eventLocation;

   	/**
   	 * Returns the isEvent
   	 *
   	 * @return boolean $isEvent
   	 */
   	public function getIsEvent() {
   		return $this->isEvent;
   	}

   	/**
   	 * Sets the isEvent
   	 *
   	 * @param boolean $isEvent
   	 * @return void
   	 */
   	public function setIsEvent($isEvent) {
   		$this->isEvent = $isEvent;
   	}

   	/**
   	 * Returns the boolean state of isEvent
   	 *
   	 * @return boolean
   	 */
   	public function isIsEvent() {
   		return $this->getIsEvent();
   	}

	/**
	 * Returns the eventStartdate
	 *
	 * @return DateTime $eventStartdate
	 */
	public function getEventStartdate() {
		return $this->eventStartdate;
	}

	/**
	 * Sets the eventStartdate
	 *
	 * @param DateTime $eventStartdate
	 * @return void
	 */
	public function setEventStartdate($eventStartdate) {
		$this->eventStartdate = $eventStartdate;
	}

	/**
	 * Returns the eventStarttime
	 *
	 * @return DateTime $eventStarttime
	 */
	public function getEventStarttime() {
		return $this->eventStarttime;
	}

	/**
	 * Sets the eventStarttime
	 *
	 * @param DateTime $eventStarttime
	 * @return void
	 */
	public function setEventStarttime($eventStarttime) {
		$this->eventStarttime = $eventStarttime;
	}

	/**
	 * Returns the eventEnddate
	 *
	 * @return DateTime $eventEnddate
	 */
	public function getEventEnddate() {
		return $this->eventEnddate;
	}

	/**
	 * Sets the eventEnddate
	 *
	 * @param DateTime $eventEnddate
	 * @return void
	 */
	public function setEventEnddate($eventEnddate) {
		$this->eventEnddate = $eventEnddate;
	}

	/**
	 * Returns the eventEndtime
	 *
	 * @return DateTime $eventEndtime
	 */
	public function getEventEndtime() {
		return $this->eventEndtime;
	}

	/**
	 * Sets the eventEndtime
	 *
	 * @param DateTime $eventEndtime
	 * @return void
	 */
	public function setEventEndtime($eventEndtime) {
		$this->eventEndtime = $eventEndtime;
	}

	/**
	 * Returns the eventLocation
	 *
	 * @return string $eventLocation
	 */
	public function getEventLocation() {
		return $this->eventLocation;
	}

	/**
	 * Sets the eventLocation
	 *
	 * @param string $eventLocation
	 * @return void
	 */
	public function setEventLocation($eventLocation) {
		$this->eventLocation = $eventLocation;
	}

    /**
     * Get year of eventStartdate
     *
     * @return integer
     */
    public function getYearOfEventStartdate() {
        return $this->getEventStartdate()->format('Y');
    }

    /**
     * Get month of eventStartdate
     *
     * @return integer
     */
    public function getMonthOfEventStartdate() {
        return $this->getEventStartdate()->format('m');
    }

    /**
     * Get day of eventStartdate
     *
     * @return integer
     */
    public function getDayOfEventStartdate() {
        return $this->getEventStartdate()->format('d');
    }

}
?>