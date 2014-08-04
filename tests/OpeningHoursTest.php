<?php

include_once( "OpeningHours.php" );


class OpeningHoursTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider hoursData
     */
    public function testSetHoursIgnoresUnknownHashes( $hours )
    {
        
        $openingHours = new OpeningHours();
        
        $hoursIn = $hours;
        $hoursIn[ 'Pit' ] = array('1:42', '15:11');
        
        $openingHours->setHours( $hoursIn );
        
        $this->assertEquals( $openingHours->hours, $hours );
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @dataProvider hoursData
     */
    public function testSetHoursFailsOnMissingDays( $hours )
    {
        
        $openingHours = new OpeningHours();
        
        $hoursIn = $hours;
        
        array_splice( $hoursIn, 3, 1 );
        
        $openingHours->setHours( $hoursIn );
                
    }
    
    /**
     * @dataProvider hoursData
     */
    public function testGetHoursForASpecificDate( $hoursIn ) {
        
        $openingHours = new OpeningHours();
        
        // Should be a saturday
        $date = new DateTime( '2000/07/01', new DateTimeZone( 'Europe/Copenhagen' ) );
        
        $openingHours->setHours( $hoursIn );
        
        $hours = $openingHours->getHours( $date );
         
        $this->assertEquals( $hours, $hoursIn[ 'Sat' ] );
    }
    
    /**
     * @dataProvider hoursData
     */
    public function testGetHoursWithoutADate( $hoursIn ) {
        
        $openingHours = new OpeningHours();
        
        $openingHours->setHours( $hoursIn );
        
        $hours = $openingHours->getHours();
        
        $today = new DateTime( 'today', new DateTimeZone( 'UTC' ) );
        $weekday = $today->format( 'D' );
         
        $this->assertEquals( $hours, $hoursIn[ $weekday ] );
    }
    
    public function hoursData() {
        $hours = array(
            array(
                array(
                    'Mon' => array('8:00', '17:30'),
                    'Tue' => array('9:00', '18:30'),
                    'Wed' => array('10:00', '19:30'),
                    'Thu' => array('11:00', '20:30'),
                    'Fri' => array('12:00', '21:30'),
                    'Sat' => array('13:00', '22:30'),
                    'Sun' => null
                )
            ),
            array(
                array(
                    'Mon' => array('8:00', '17:30'),
                    'Tue' => array('9:00', '18:30'),
                    'Thu' => array('11:00', '20:30'),
                    'Wed' => array('10:00', '19:30'),
                    'Fri' => array('12:00', '21:30'),
                    'Sat' => array('13:00', '22:30'),
                    'Sun' => array('14:00', '23:30')
                )
            )
    	);
        
        return $hours;
    }
    
}