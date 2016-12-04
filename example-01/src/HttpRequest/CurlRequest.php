<?php

namespace Refactoring\Example01\HttpRequest;

use Refactoring\Example01\HttpRequest;

class CurlRequest implements HttpRequest
{
    /**
     * @var resource
     */
    private $handle;

    /**
     * CurlRequest constructor.
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->handle = curl_init($url);
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    public function setOption($name, $value)
    {
        return curl_setopt($this->handle, $name, $value);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return curl_exec($this->handle);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getInfo($name)
    {
        return curl_getinfo($this->handle, $name);
    }

    /**
     * @return void
     */
    public function close()
    {
        curl_close($this->handle);
    }

    /**
     * @return string
     */
    public function error()
    {
        return curl_error($this->handle);
    }

    /**
     * @return void
     */
    public function reset()
    {
        curl_reset($this->handle);
    }

    /**
     * @param $filename
     * @param string|null $mimeType
     * @param string|null $postName
     * @return \CURLFile
     */
    public function fileCreate($filename, $mimeType = null, $postName = null)
    {
        return curl_file_create($filename, $mimeType, $postName);
    }
}
