<?php

namespace Refactoring\Example01;

interface HttpRequest
{
    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function setOption($name, $value);

    /**
     * @return mixed
     */
    public function execute();

    /**
     * @param $name
     * @return mixed
     */
    public function getInfo($name);

    /**
     * @return mixed
     */
    public function close();

    /**
     * @return mixed
     */
    public function error();

    /**
     * @return mixed
     */
    public function reset();

    /**
     * @param $filename
     * @param null $mimeType
     * @param null $postName
     * @return mixed
     */
    public function fileCreate($filename, $mimeType = null, $postName = null);
}
