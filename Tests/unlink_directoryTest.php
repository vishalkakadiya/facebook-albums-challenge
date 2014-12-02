
<?php

class unlink_directoryTest extends PHPUnit_Framework_TestCase {

    protected $unlink_directory;

    protected function setUp() {
        $this->unlink_directory = new unlink_directory();
    }

    function testremove_directory( $directory = null ) {
        $actual = $this->unlink_directory->remove_directory( $directory );
        $this->assertEquals( $actual, $actual );
    }

    protected function tearDown() {
        unset( $this->unlink_directory );
    }

}

?>
