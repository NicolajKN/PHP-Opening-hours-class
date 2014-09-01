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
    private $dateFormat = 'Y/m/d';
    
    private function getWeekDay( $date ) {
        return $date->format( 'D' );
    }
    
    private function getException( $date ) {
        
        // Get the supplied date in the compare format
        $dateFormatted = $date->format( $this->dateFormat );
        
        foreach ( $this->exceptions as $exDate => $exHours ) {
            
            // Create a DateTime object from the exception date
            $exDateObj = new DateTime( $exDate, $this->timezone );
            
            // Create a comparable string of the exception DateTime object
            $exDateFormatted = $exDateObj->format( $this->dateFormat );
            
            // If there is a match, return
            if ( $dateFormatted == $exDateFormatted ) {
                return $exHours;
            }
        }
  
        // Fall back to none if nothing is found
        return false;
    }
    
    public function OpeningHours( $hours, $timezone = 'UTC' ) {
        
        // Set the default timezone
        $this->setTimeZone( $timezone );
        
        // Set the hours given
        $this->setHours( $hours );
    }
    
    public function setTimeZone ( $timezone ) {
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
        
        return $out;
    }
    
    public function getHoursArray( $dateFrom, $dateTo ) {
        
        $hours = array();
        
        // Ensure that $dateTo is included in the array
        $dateTo = $dateTo->modify( '+1 day' ); 
        
        // Iterate 1 day at the time
        $dateInterval = new DateInterval( 'P1D' );
        
        // Create a traversable date range
        $dateRange = new DatePeriod( $dateFrom, $dateInterval, $dateTo );
        
        foreach( $dateRange as $date ) {
            $dateString = $date->format( $this->dateFormat );
            $hours[ $dateString ] = $this->getHours( $date );
        }
        
        return $hours;
        
    }
    
    public function getNextOpeningTime( $dateFrom = null ) {
        
        // Fall back to today for the date
        if ( $dateFrom == null ) {
            $dateFrom = new DateTime( 'today', $this->timezone );
        }
        
        $outDate = $dateFrom;
        
        while ( $this->getHours( $outDate ) == 'closed' ) {
            $outDate = $outDate->modify( '+1 day' ); 
        }
        
        return $outDate;
        
    }
    
}

