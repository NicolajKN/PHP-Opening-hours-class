<?php

include_once( "OpeningHours.php" );


class OpeningHoursTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider hoursData
     */
    public function testSetHoursIgnoresUnknownHashes( $hours )
    {
        
        $hoursIn = $hours;
        $hoursIn[ 'Pit' ] = array('1:42', '15:11');
        
        $openingHours = new OpeningHours( $hoursIn );
        
        $this->assertEquals( $openingHours->hours, $hours );
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @dataProvider hoursData
     */
    public function testSetHoursFailsOnMissingDays( $hours )
    {

        $hoursIn = $hours;
        array_splice( $hoursIn, 3, 1 );
        
        $openingHours = new OpeningHours( $hoursIn );

    }
    
    /**
     * @dataProvider hoursData
     */
    public function testGetHoursForASpecificDate( $hoursIn ) {
        
        // Should be a saturday
        $date = new DateTime( '2000/07/01', new DateTimeZone( 'Europe/Copenhagen' ) );
        
        $openingHours = new OpeningHours( $hoursIn );
        
        $hours = $openingHours->getHours( $date );
         
        $this->assertEquals( $hours, $hoursIn[ 'Sat' ] );
    }
    
    /**
     * @dataProvider hoursData
     */
    public function testGetHoursWithoutADate( $hoursIn ) {
        

        $openingHours = new OpeningHours( $hoursIn );
        
        $hours = $openingHours->getHours();
        
        $today = new DateTime( 'today', new DateTimeZone( 'UTC' ) );
        $weekday = $today->format( 'D' );
         
        $this->assertEquals( $hours, $hoursIn[ $weekday ] );
    }
    
    /**
     * @dataProvider exceptionsData
     */
    public function testGetHoursWithException( $hours, $exceptions ) {
        
        $decTwentyThird = new DateTime( '2014/12/23', new DateTimeZone( 'UTC' ));
        $janFirst = new DateTime( '2014/1/1', new DateTimeZone( 'UTC' ));
        
        $openingHours = new OpeningHours( $hours );
        
        $openingHours->setExceptions( $exceptions );
        
        $hours = $openingHours->getHours( $decTwentyThird );
         
        $this->assertEquals( $hours, $exceptions[ '2014/12/23' ] );
        
        $hours = $openingHours->getHours( $janFirst );
        
        $this->assertEquals( $hours, $exceptions[ '2014/1/1' ] );
    }
    
    
    public function exceptionsData() {
        $hours = $this->hoursData();
        
        $exceptions = array(
        	'2014/1/1'   => array( null ),
            '2014/12/23' => array( '7:30', '20:00' )
        );
        
        return array(
            array(
            	$hours[ 0 ][ 0 ],
                $exceptions
            )
        );
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