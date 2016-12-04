<?php

namespace HttpRequest;

use Refactoring\Example01\HttpRequest\CurlRequest;

class CurlRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testCurlReuqestForDevNull()
    {
        $curl = new CurlRequest();
        $curl->reset();
        $curl->setOption(CURLOPT_URL, '/dev/null');
        $this->assertFalse($curl->execute());
        $this->assertEquals('/dev/null', $curl->getInfo(CURLINFO_EFFECTIVE_URL));
        $this->assertNotEmpty($curl->error());
        $curl->close();
    }

    public function testFileCreate()
    {
        $curl = new CurlRequest();
        $this->assertInstanceOf(\CURLFile::class, $curl->fileCreate('/dev/null'));
        $curl->close();
    }

    public function testCurlReuqestReset()
    {
        $curl = new CurlRequest();
        $curl->setOption(CURLOPT_URL, '/dev/null');
        $curl->reset();
        $this->assertEquals('', $curl->getInfo(CURLINFO_EFFECTIVE_URL));
        $curl->close();
    }
}
