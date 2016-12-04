<?php

use Refactoring\Example01\Api;
use Refactoring\Example01\HttpRequest;
use Refactoring\Example01\HttpRequest\CurlRequest;

class ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_pass_with_valid_params_which_for_list_method()
    {
        // Arrange
        $api = new Api();
        $expectedParams = [
            'method' => 'list',
            'type' => 'album',
            'indexes' => [
                1, 2, 3,
            ],
        ];

        // Act
        $api->checkParams($expectedParams);

        $reflectionClass = new ReflectionClass($api);
        $attribute = $reflectionClass->getProperty('params');
        $attribute->setAccessible(true);
        $actualParams = $attribute->getValue($api);

        // Assert
        $this->assertEquals($expectedParams, $actualParams);
    }

    /**
     * @test
     */
    public function it_should_pass_with_valid_params_which_for_upload_method()
    {
        // Arrange
        $api = new Api();
        $expectedParams = [
            'method' => 'update',
            'id' => 1,
            'act' => 'disable',
        ];

        // Act
        $api->checkParams($expectedParams);
        $reflectionClass = new ReflectionClass($api);
        $attribute = $reflectionClass->getProperty('params');
        $attribute->setAccessible(true);
        $actualParams = $attribute->getValue($api);

        // Assert
        $this->assertEquals($expectedParams, $actualParams);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing `method`
     */
    public function it_should_not_pass_without_method()
    {
        // Arrange
        $api = new Api();
        $params = [];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Method [help] is invalid
     */
    public function it_should_not_pass_with_invalid_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'help',
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing `id`
     */
    public function it_should_not_pass_without_id_which_for_upload_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'update',
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid id
     */
    public function it_should_not_pass_with_invalid_id_which_for_upload_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'update',
            'id' => 'a',
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing `act`
     */
    public function it_should_not_pass_without_act_which_for_upload_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'update',
            'id' => 1,
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Act [test] is invalid
     */
    public function it_should_not_pass_with_invalid_act_which_for_upload_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'update',
            'id' => 1,
            'act' => 'test',
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing `type`
     */
    public function it_should_not_pass_without_type_which_for_list_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'list',
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Type [people] is invalid
     */
    public function it_should_not_pass_with_invalid_type_which_for_list_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'list',
            'type' => 'people'
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing `indexes`
     */
    public function it_should_not_pass_without_indexes_which_for_list_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'list',
            'type' => 'album',
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid indexes
     */
    public function it_should_not_pass_with_invalid_indexes_which_for_list_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'list',
            'type' => 'album',
            'indexes' => 1,
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Indexes [a, b, c] contains illegal characters
     */
    public function it_should_not_pass_with_invalid_type_of_indexes_which_for_list_method()
    {
        // Arrange
        $api = new Api();
        $params = [
            'method' => 'list',
            'type' => 'album',
            'indexes' => ['a', 'b', 'c'],
        ];

        // Act
        $api->checkParams($params);
    }

    /**
     * @test
     * @group abc
     */
    public function it_should_upload_image_with_given_file()
    {
        $expectResult = '';
        $httpRequest = $this->createMock(HttpRequest::class);
        $httpRequest
            ->expects($this->once())
            ->method('execute')
            ->willReturn($expectResult);

        $api = new Api();
        $api->setHttpRequest($httpRequest);
        $api->checkParams([
            'method' => 'upload',
            'type' => 'album',
            'indexes' => ['101'],
            'file' => __DIR__ . '/fixtures/foo.jpg',
        ]);
        $actualResult = $api->execute();

        $this->assertEquals($expectResult, $actualResult);
    }

    /**
     * @test
     */
    public function it_should_get_curl_request()
    {
        $api = new Api();
        $actual = $api->getHttpRequest();
        $this->assertInstanceOf(CurlRequest::class, $actual);
    }

    /**
     * @test
     */
    public function it_should_update_image()
    {
        $expectResult = '';
        $httpRequest = $this->createMock(HttpRequest::class);
        $httpRequest
            ->expects($this->once())
            ->method('execute')
            ->willReturn($expectResult);

        $api = new Api();
        $api->setHttpRequest($httpRequest);
        $api->checkParams([
            'method' => 'update',
            'type' => 'album',
            'id' => '101',
            'dimension' => '100x100',
            'act' => 'enable',
        ]);
        $actualResult = $api->execute();

        $this->assertEquals($expectResult, $actualResult);
    }
}
