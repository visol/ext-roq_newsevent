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
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class Tx_RoqNewsevent_Domain_Model_Event.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage News event
 *
 */
class Tx_RoqNewsevent_Domain_Model_EventTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var Tx_RoqNewsevent_Domain_Model_Event
     */
    protected $fixture;

    public function setUp()
    {
        $this->fixture = new Tx_RoqNewsevent_Domain_Model_Event();
    }

    public function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @test
     */
    public function getIsEventReturnsInitialValueForBoolean()
    {
        $this->assertSame(
            true,
            $this->fixture->getIsEvent()
        );
    }

    /**
     * @test
     */
    public function setIsEventForBooleanSetsIsEvent()
    {
        $this->fixture->setIsEvent(true);

        $this->assertSame(
            true,
            $this->fixture->getIsEvent()
        );
    }

    /**
     * @test
     */
    public function getEventStartdateReturnsInitialValueForDate()
    {
    }

    /**
     * @test
     */
    public function setEventStartdateForDateSetsEventStartdate()
    {
    }

    /**
     * @test
     */
    public function getEventStarttimeReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setEventStarttimeForIntSetsEventStarttime()
    {
    }

    /**
     * @test
     */
    public function getEventEnddateReturnsInitialValueForDate()
    {
    }

    /**
     * @test
     */
    public function setEventEnddateForDateSetsEventEnddate()
    {
    }

    /**
     * @test
     */
    public function getEventEndtimeReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setEventEndtimeForIntSetsEventEndtime()
    {
    }

    /**
     * @test
     */
    public function getEventLocationReturnsInitialValueForString()
    {
    }

    /**
     * @test
     */
    public function setEventLocationForStringSetsEventLocation()
    {
        $this->fixture->setEventLocation('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->fixture->getEventLocation()
        );
    }

}
