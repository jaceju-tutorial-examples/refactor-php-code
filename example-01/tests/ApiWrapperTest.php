<?php

use Refactoring\Example01\ApiWrapper;
use Refactoring\Example01\Db;

class ApiWrapperTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $db = Db::getInstance();
        $db->createImageTable();
        $db->insertImageData([
            [
                'id' => 101,
                'type' => 'album',
            ], [
                'id' => 55,
                'type' => 'article',
            ]
        ]);
    }

    protected function tearDown()
    {
        $db = Db::getInstance();
        $db->dropImageTable();
    }

    public function testGetAlbumImageInfoSuccess()
    {
        $albumId = 101;
        $expectedImageInfo = [
            101 => [
                'id' => 101,
                'path' => 'album/101/150x150.jpg',
            ],
        ];

        $wrapper = new ApiWrapper();
        $actualInfo = $wrapper->getAlbumImageInfo([$albumId], ['dimension' => '150x150']);
        $this->assertEquals($expectedImageInfo, $actualInfo);
    }

    public function testGetAlbumImageInfoSuccessWithoutParams()
    {
        $albumId = 101;
        $expectedImageInfo = [
            101 => [
                'id' => 101,
                'path' => 'album/101/100x100.jpg',
            ],
        ];

        $wrapper = new ApiWrapper();
        $actualInfo = $wrapper->getAlbumImageInfo([$albumId]);
        $this->assertEquals($expectedImageInfo, $actualInfo);
    }

    public function testGetAlbumImageInfoWithoutIndexes()
    {
        $wrapper = new ApiWrapper();
        $actualInfo = $wrapper->getAlbumImageInfo([]);
        $this->assertFalse($actualInfo);
    }

    public function testGetAlbumImageInfoWithWrongType()
    {
        $albumId = 101;
        $wrapper = new ApiWrapper();
        $actualInfo = $wrapper->getAlbumImageInfo([$albumId], ['type' => 'abc']);
        $this->assertFalse($actualInfo);
    }

    public function testGetAlbumImagePathsSuccess()
    {
        $albumId = 101;
        $expectedImagePaths = [
            101 => 'album/101/150x150.jpg',
        ];

        $wrapper = new ApiWrapper();
        $actualPaths = $wrapper->getAlbumImagePaths([$albumId], ['dimension' => '150x150']);
        $this->assertEquals($expectedImagePaths, $actualPaths);
    }

    public function testGetArticleImageInfoSuccess()
    {
        $articleId = 55;
        $expectedImageInfo = [
            55 => [
                'id' => 55,
                'path' => 'article/55/150x150.jpg',
            ],
        ];

        $wrapper = new ApiWrapper();
        $actualInfo = $wrapper->getArticleImageInfo([$articleId], ['dimension' => '150x150']);
        $this->assertEquals($expectedImageInfo, $actualInfo);
    }

    public function testGetArticleImagePathsSuccess()
    {
        $articleId = 55;
        $expectedImagePaths = [
            55 => 'article/55/150x150.jpg',
        ];

        $wrapper = new ApiWrapper();
        $actualPaths = $wrapper->getArticleImagePaths([$articleId], ['dimension' => '150x150']);
        $this->assertEquals($expectedImagePaths, $actualPaths);
    }
}
