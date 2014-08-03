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
    
    public function OpeningHours() {
        
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
    
}

