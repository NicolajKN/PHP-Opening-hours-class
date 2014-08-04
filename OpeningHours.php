<?php

/**
 * Handles business hours for displaying on websites
 * in various ways.
 *
 * @author NicolajKN
 */
class OpeningHours 
{
    
    public $hours = array();
    public $exceptions = array();
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
    
    public function setExceptions( $exceptions ) {
        $this->exceptions = $exceptions;
    }
    
    private function getWeekDay( $date ) {
        return $date->format( 'D' );
    }
    
    public function getException( $date ) {
        
        $compareFormat = 'YY/MM/DD';
        
        $dateFormatted = $date->format( $compareFormat );
        
        foreach ( $this->exceptions as $exDate => $exHours ) {
            $exDateObj = new DateTime( $exDate, $this->timezone );
            $exDateFormatted = $exDateObj->format( $compareFormat );
            
            if ( $dateFormatted == $exDateFormatted ) {
                return $exHours;
            }
        }
        
        return false;
    }
    
    public function getHours( $date = null ) {
        
        // Fall back to today for the date
        if ( $date == null ) {
            $date = new DateTime( 'today', $this->timezone );
        }
        
        // Get the exception for the date
        $exception = $this->getException( $date );
        
        if ( ! $exception ) {
            // Normal opening hours are retrieved by the weekday
            $dayOfWeek = $this->getWeekDay( $date );
        
        	return $this->hours[ $dayOfWeek ]; 
        } else {
            // Exception hours are returned directly
            return $exception;
        }
        

    }
    
}

