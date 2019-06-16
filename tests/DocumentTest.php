<?php

use PHPUnit\Framework\TestCase;
use Skel\Document;

class DocumentTest extends TestCase
{
    public function testGetSourceFilename()
    {
        $document = new Document();

        $result = $document->getSourceFileName('changelog');
        $this->assertEquals('CHANGELOG.md', $result);

        $result = $document->getSourceFileName('contributing');
        $this->assertEquals('CONTRIBUTING.md', $result);

        $result = $document->getSourceFileName('license-mit');
        $this->assertEquals('licenses/mit.txt', $result);

        $result = $document->getSourceFileName('readme');
        $this->assertEquals('README.md', $result);
    }

    public function testGetSourceFilenameWithUnknownId()
    {
        $this->expectException(UnexpectedValueException::class);

        $document = new Document();

        $result = $document->getSourceFileName('unknown');
    }
}
