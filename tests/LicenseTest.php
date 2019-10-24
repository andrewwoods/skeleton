<?php

use PHPUnit\Framework\TestCase;
use Skel\License;

class LicenseTest extends TestCase
{
    public function testGetSourceFilenameForMit()
    {
        $license = new License();

        $result = $license->getSourceFileName('mit');
        $this->assertEquals('licenses/mit.txt', $result);
    }

    public function testGetSourceFilenameForGpl2()
    {
        $license = new License();

        $result = $license->getSourceFileName('gpl-2');
        $this->assertEquals('licenses/gpl-2.0.txt', $result);
    }

    public function testGetSourceFilenameWithUnknownId()
    {
        $this->expectException(UnexpectedValueException::class);

        $license = new License();

        $result = $license->getSourceFileName('unknown');
    }
}
