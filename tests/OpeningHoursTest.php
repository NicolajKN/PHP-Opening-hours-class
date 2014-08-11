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
    public function testGetHoursForASpecificClosedDate( $hoursIn ) {
        
        // Should be a saturday
        $date = new DateTime( '2000/07/02', new DateTimeZone( 'Europe/Copenhagen' ) );
        
        $openingHours = new OpeningHours( $hoursIn );
        
        $hours = $openingHours->getHours( $date );
         
        $this->assertEquals( $hours, 'closed' );
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
        $janFirst       = new DateTime( '2014/01/01', new DateTimeZone( 'UTC' ));
        
        $openingHours = new OpeningHours( $hours );
        
        $openingHours->setExceptions( $exceptions );
        
        $hours = $openingHours->getHours( $decTwentyThird );
         
        $this->assertEquals( $hours, $exceptions[ '2014/12/23' ] );
        
        $hours = $openingHours->getHours( $janFirst );
        
        $this->assertEquals( $hours, $exceptions[ '2014/01/01' ] );
    }

   
    /**
     * @dataProvider exceptionsData
     */
    public function testGetHoursArray( $hours, $exceptions ) {
        
        $dateFrom = new DateTime( '2014/12/22', new DateTimeZone( 'UTC' ) );
        $dateTo   = new DateTime( '2015/01/01', new DateTimeZone( 'UTC' ) );
        
        $expectedArray = array(
        	'2014/12/22' => $hours[ 'Mon' ] ,
        	'2014/12/23' => array( '7:30', '20:00' ),
        	'2014/12/24' => $hours[ 'Wed' ],
        	'2014/12/25' => $hours[ 'Thu' ],
        	'2014/12/26' => $hours[ 'Fri' ],
        	'2014/12/27' => $hours[ 'Sat' ],
        	'2014/12/28' => $hours[ 'Sun' ],
        	'2014/12/29' => $hours[ 'Mon' ],
        	'2014/12/30' => $hours[ 'Tue' ],
        	'2014/12/31' => $hours[ 'Wed' ],
        	'2015/01/01' => $hours[ 'Thu' ]
        );
        
        $openingHours = new OpeningHours( $hours );
        
        $openingHours->setExceptions( $exceptions );
        
        $hoursArray = $openingHours->getHoursArray( $dateFrom, $dateTo );
         
        $this->assertEquals( $hoursArray, $expectedArray );
        
    }
    
    /**
     * @dataProvider exceptionsData
     */
    public function testGetHoursArrayInvertedDates( $hours, $exceptions ) {
        
        $dateFrom = new DateTime( '2015/12/22', new DateTimeZone( 'UTC' ) );
        $dateTo   = new DateTime( '2015/01/01', new DateTimeZone( 'UTC' ) );
        
        $openingHours = new OpeningHours( $hours );
        
        $hoursArray = $openingHours->getHoursArray( $dateFrom, $dateTo );
         
    }
    
    
    public function exceptionsData() {
        $hours = $this->hoursData();
        
        $exceptions = array(
        	'2014/01/01' => 'closed',
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
                    'Sun' => 'closed'
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
                    'Sun' => 'closed'
                )
            )
    	);
        
        return $hours;
    }
    
}