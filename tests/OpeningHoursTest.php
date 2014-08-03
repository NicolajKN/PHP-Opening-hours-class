<?php

include_once( "OpeningHours.php" );

class OpeningHoursTest extends PHPUnit_Framework_TestCase
{

    public function testSetHoursIgnoresUnknownHashes()
    {
        
        $openingHours = new OpeningHours();
        
        $hoursIn = array(
        	'Mon' => array('8:00', '17:30'),
            'Pot' => array('3:00', '4:30'),
            'Tue' => array('9:00', '18:30'),
            'Wed' => array('10:00', '19:30'),
            'Thu' => array('11:00', '20:30'),
            'Fri' => array('12:00', '21:30'),
            'Sat' => array('13:00', '22:30'),
            'Sun' => array('14:00', '23:30')
        );
        
        $hoursOut = array(
        	'Mon' => array('8:00', '17:30'),
            'Tue' => array('9:00', '18:30'),
            'Wed' => array('10:00', '19:30'),
            'Thu' => array('11:00', '20:30'),
            'Fri' => array('12:00', '21:30'),
            'Sat' => array('13:00', '22:30'),
            'Sun' => array('14:00', '23:30')
        );
        
        $openingHours->setHours( $hoursIn );
        
        $this->assertEquals( $openingHours->hours, $hoursOut );
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetHoursFailsOnMissingDays()
    {
        
        $openingHours = new OpeningHours();
        
        $hoursIn = array(
        	'Mon' => array('8:00', '17:30'),
            'Tue' => array('9:00', '18:30'),
            'Wed' => array('10:00', '19:30'),
            'Thu' => array('11:00', '20:30'),
            'Sat' => array('13:00', '22:30'),
            'Sun' => array('14:00', '23:30')
        );
        
        $openingHours->setHours( $hoursIn );
        
        $this->assertEquals( $openingHours->hours, $hoursOut );
    }
}