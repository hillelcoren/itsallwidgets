<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Support\Export\BusyMarkers;
use Illuminate\Support\Facades\Log;

class BusyMarkersTest extends TestCase
{
    const COMMAND = 'itsallwidgets:load_ratings';

    /**
     * A storage folder of the test's own, so no real marker is touched.
     *
     * @var string
     */
    protected $storage;

    protected function setUp()
    {
        parent::setUp();

        $this->storage = sys_get_temp_dir() . '/iaw-busy-test-' . str_random(8);
        mkdir($this->storage . '/framework', 0755, true);

        $this->app->useStoragePath($this->storage);
    }

    protected function tearDown()
    {
        $this->removeDirectory($this->storage);

        parent::tearDown();
    }

    public function testAMarkerOfThisProcessIsLive()
    {
        BusyMarkers::write(self::COMMAND);

        $path = BusyMarkers::path(self::COMMAND, getmypid());
        $marker = json_decode(file_get_contents($path), true);

        $this->assertSame($this->storage . '/framework/iaw-busy/itsallwidgets-load_ratings.' . getmypid(), $path);
        $this->assertSame(self::COMMAND, $marker['command']);
        $this->assertSame(getmypid(), $marker['pid']);
        $this->assertSame('cli', $marker['sapi']);
        $this->assertEquals(time(), $marker['at'], '', 5);

        if (file_exists('/proc/self')) {
            $this->assertRegExp('/^\d+$/', $marker['start']);
        }

        $this->assertSame([self::COMMAND], BusyMarkers::live());
        $this->assertFileExists($path);

        BusyMarkers::clear(self::COMMAND);

        $this->assertFileNotExists($path);
        $this->assertSame([], BusyMarkers::live());
    }

    public function testAMarkerOfAGoneProcessIsDeleted()
    {
        $path = $this->writeMarker(999999, ['start' => '1']);

        $this->assertSame([], BusyMarkers::live());
        $this->assertFileNotExists($path);
    }

    public function testAMarkerOfAReusedPidIsDeleted()
    {
        if (! file_exists('/proc/self')) {
            $this->markTestSkipped('Start times need /proc.');
        }

        $path = $this->writeMarker(getmypid(), ['start' => '1']);

        $this->assertSame([], BusyMarkers::live());
        $this->assertFileNotExists($path);
    }

    public function testAStaleMarkerIsDeleted()
    {
        $path = $this->writeMarker(getmypid(), ['at' => time() - BusyMarkers::CLI_SECONDS - 60]);

        $this->assertSame([], BusyMarkers::live());
        $this->assertFileNotExists($path);
    }

    public function testAWebMarkerGoesStaleSooner()
    {
        $path = $this->writeMarker(getmypid(), ['sapi' => 'fpm-fcgi', 'at' => time() - 60]);

        $this->assertSame([self::COMMAND], BusyMarkers::live());

        $this->writeMarker(getmypid(), ['sapi' => 'fpm-fcgi', 'at' => time() - BusyMarkers::WEB_SECONDS - 60]);

        $this->assertSame([], BusyMarkers::live());
        $this->assertFileNotExists($path);
    }

    public function testAnUnreadableMarkerIsDeleted()
    {
        mkdir($this->storage . '/framework/iaw-busy');
        $path = BusyMarkers::path(self::COMMAND, getmypid());
        file_put_contents($path, self::COMMAND);

        $this->assertSame([], BusyMarkers::live());
        $this->assertFileNotExists($path);
    }

    public function testAMarkerThatCantBeWrittenDoesNotThrow()
    {
        // A file where the folder should be: even root can't write there.
        rmdir($this->storage . '/framework');
        touch($this->storage . '/framework');

        Log::shouldReceive('warning')->once();

        BusyMarkers::write(self::COMMAND);

        $this->assertSame([], BusyMarkers::live());
    }

    public function testACommandSkippedWhileDownLeavesNoMarker()
    {
        file_put_contents($this->storage . '/framework/down', json_encode([
            'time' => time(),
            'message' => null,
            'retry' => null,
            'allowed' => [],
        ]));

        $this->artisan(self::COMMAND);

        $this->assertFileNotExists(BusyMarkers::path(self::COMMAND, getmypid()));
        $this->assertSame([], BusyMarkers::live());
    }

    /**
     * Write a marker the way BusyMarkers does, with some fields changed.
     *
     * @param  int  $pid
     * @param  array  $changes
     * @return string
     */
    protected function writeMarker($pid, array $changes)
    {
        BusyMarkers::write(self::COMMAND);

        $own = BusyMarkers::path(self::COMMAND, getmypid());
        $marker = array_merge(json_decode(file_get_contents($own), true), ['pid' => $pid], $changes);

        unlink($own);

        $path = BusyMarkers::path(self::COMMAND, $pid);
        file_put_contents($path, json_encode($marker));

        return $path;
    }

    /**
     * Remove a folder and everything in it.
     *
     * @param  string  $directory
     * @return void
     */
    protected function removeDirectory($directory)
    {
        if (! is_dir($directory)) {
            @unlink($directory);

            return;
        }

        foreach (scandir($directory) as $name) {
            if ($name !== '.' && $name !== '..') {
                $this->removeDirectory($directory . '/' . $name);
            }
        }

        rmdir($directory);
    }
}
