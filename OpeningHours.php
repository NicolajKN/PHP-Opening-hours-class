<?php

/**
 * Handles business hours for displaying on websites
 * in various ways.
 *
 * @author NicolajKN
 */
class OpeningHours 
{
    
    public  $hours = array();
    public  $exceptions = array();
    private $dayNames = array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' );
    private $timezone;
    
    private function getWeekDay( $date ) {
        return $date->format( 'D' );
    }
    
    private function getException( $date ) {
        
        // Date format to make string comparison against
        $compareFormat = 'YY/MM/DD';
        
        // Get the supplied date in the compare format
        $dateFormatted = $date->format( $compareFormat );
        
        foreach ( $this->exceptions as $exDate => $exHours ) {
            
            // Create a DateTime object from the exception date
            $exDateObj = new DateTime( $exDate, $this->timezone );
            
            // Create a comparable string of the exception DateTime object
            $exDateFormatted = $exDateObj->format( $compareFormat );
            
            // If there is a match, return
            if ( $dateFormatted == $exDateFormatted ) {
                return $exHours;
            }
        }
  
        // Fall back to false if nothing is found
        return false;
    }
    
    public function OpeningHours( $timezone = null ) {
        if ( $timezone == null ) {
            // Default to UTC
            $this->timezone = new DateTimeZone( 'UTC' );
        } else {
            $this->timezone = $timezone;
        }
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
        
        // Save the generated array
        $this->hours = $hoursArray;
        
    }
    
    public function setExceptions( $exceptions ) {
        // Simply save the exception array directly
        $this->exceptions = $exceptions;
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

