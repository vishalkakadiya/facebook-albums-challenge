<?php

class zipperTest extends PHPUnit_Framework_TestCase {

    protected $zipper;

    protected function setUp() {
        $this->zipper = new zipper();
    }

    public function testLoadZipFiles( $source = null ) {
        $actual = $this->zipper->LoadZipFiles( $source );
        $this->assertEquals( $actual, $actual );
    }

    public function testProcessZip( $foldercontent = null, $folder = null, $maxsize = 50000 ) {
        $actual = $this->zipper->ProcessZip( $foldercontent, $folder, $maxsize );
        $this->assertEquals( $actual, $actual );
    }

    public function testgetMemoryLimit() {
        $actual = $this->zipper->getMemoryLimit();
        $this->assertEquals( $actual, $actual );
    }

    public function testmake_zip( $album_download_directory = null ) {
        $actual = $this->zipper->make_zip( $album_download_directory );
        $this->assertEquals( $actual, $actual );
    }

    public function testget_zip( $album_download_directory = null ) {
        $actual = $this->zipper->get_zip( $album_download_directory );
        $this->assertEquals( $actual, $actual );
    }

    protected function tearDown() {
        unset($this->zipper);
    }

}

?>
