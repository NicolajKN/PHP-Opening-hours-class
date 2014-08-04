<?php

/**
 * Handles business hours for displaying on websites
 * in variours ways.
 *
 * @author NicolajKN
 */
class OpeningHours 
{
    
    public $hours;
    private $dayNames = array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' );
    private $timezone;
    
    
    public function OpeningHours( $timezone = 'UTC' ) {
        $this->timezone = new DateTimeZone( $timezone );
    }
    
    public function setHours( $hours ) 
    {
        
        $hoursArray = array();
        
        // Set hours array in preferred order
        // and only save relevant data
        forEach( $this->dayNames as $day ) {
            
            // Throw exception if $hours does not contain	
            // the current $day
            if ( !array_key_exists( $day, $hours ) ) {
                throw new InvalidArgumentException( 'One or more days are missing from the array', 10 );
        	}
            
            $hoursArray[ $day ] = $hours[ $day ];
        }
        
        $this->hours = $hoursArray;
        
    }
    
    private function getWeekDay( $date ) {
        return $date->format( 'D' );
    }
    
    public function getHours( $date = null ) {
        if ( $date == null ) {
            $date = new DateTime( 'today', $this->timezone );
        }
        
        $dayOfWeek = $this->getWeekDay( $date );
        
        return $this->hours[ $dayOfWeek ];
    }
    
}

