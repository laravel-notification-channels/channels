<?php

namespace NotificationChannels\Webex\Test;

use NotificationChannels\Webex\WebexMessageFile;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for WebexMessageFile.
 *
 * @backupStaticAttributes enabled
 */
class WebexMessageFileTest extends TestCase
{
    protected static $localFilepath = __DIR__.'/fixtures/file.txt';
    protected static $remoteFilepath = 'https://www.webex.com/content/dam/wbx/global/images/webex-favicon.png';

    /**
     * Checks and transforms a stream resource into a string, in-place.
     *
     * @param  resource|mixed  $item  some value
     * @return void
     */
    protected static function getResourceContents(&$item)
    {
        if (is_resource($item)) {
            $item = stream_get_contents($item);
        }
    }

    public function testPath()
    {
        $fileWithLocalPath = (new WebexMessageFile)->path(self::$localFilepath);
        self::assertEquals(self::$localFilepath, $fileWithLocalPath->path);

        $fileWithRemotePath = (new WebexMessageFile)->path(self::$remoteFilepath);
        self::assertEquals(self::$remoteFilepath, $fileWithRemotePath->path);
    }

    public function testName()
    {
        $fileWithNoUserProvidedName = (new WebexMessageFile);
        $this->assertEmpty($fileWithNoUserProvidedName->name);

        $fileWithUserProvidedName = (new WebexMessageFile)->name('user_provided_name');
        $this->assertEquals('user_provided_name', $fileWithUserProvidedName->name);
    }

    public function testType()
    {
        $fileWithNoUserProvidedType = (new WebexMessageFile);
        $this->assertEmpty($fileWithNoUserProvidedType->type);

        $fileWithUserProvidedType = (new WebexMessageFile)->type('user_provided_mime_type');
        $this->assertEquals('user_provided_mime_type', $fileWithUserProvidedType->type);
    }

    public function testToArray()
    {
        $expectedArr = [
            'name' => 'files',
            'contents' => stream_get_contents(fopen(self::$localFilepath, 'r')),
        ];
        $arr = (new WebexMessageFile)
            ->path(self::$localFilepath)
            ->toArray();
        array_walk_recursive($arr, [$this, 'getResourceContents']);
        $this->assertEquals($expectedArr, $arr);

        $expectedArr = [
            'name' => 'files',
            'contents' => stream_get_contents(fopen(self::$localFilepath, 'r')),
            'filename' => 'user_provided_name',
        ];
        $arr = (new WebexMessageFile)
            ->path(self::$localFilepath)
            ->name('user_provided_name')
            ->toArray();
        array_walk_recursive($arr, [$this, 'getResourceContents']);
        $this->assertEquals($expectedArr, $arr);

        $expectedArr = [
            'name' => 'files',
            'contents' => stream_get_contents(fopen(self::$localFilepath, 'r')),
            'headers' => ['Content-Type' => 'user_provided_mime_type'],
        ];
        $arr = (new WebexMessageFile)
            ->path(self::$localFilepath)
            ->type('user_provided_mime_type')
            ->toArray();
        array_walk_recursive($arr, [$this, 'getResourceContents']);
        $this->assertEquals($expectedArr, $arr);

        $expectedArr = [
            'name' => 'files',
            'contents' => stream_get_contents(fopen(self::$localFilepath, 'r')),
            'filename' => 'user_provided_name',
            'headers' => ['Content-Type' => 'user_provided_mime_type'],
        ];
        $arr = (new WebexMessageFile)
            ->path(self::$localFilepath)
            ->name('user_provided_name')
            ->type('user_provided_mime_type')
            ->toArray();
        array_walk_recursive($arr, [$this, 'getResourceContents']);
        $this->assertEquals($expectedArr, $arr);
    }
}
