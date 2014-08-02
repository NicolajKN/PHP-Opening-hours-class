<?php

include_once( "OpeningHours.php" );

class OpeningHoursTest extends PHPUnit_Framework_TestCase
{
    // ...

    public function testTest()
    {
        
        
        // Arrange
        $a = new OpeningHours();
        
        $this->assertInstanceOf( 'OpeningHours', $a );

        // Act
        //$b = $a->negate();

        // Assert
        //$this->assertEquals(-1, $b->getAmount());
    }

    // ...
}