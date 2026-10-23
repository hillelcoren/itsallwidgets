<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyIawExport;
use App\Support\IawExportSignature;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExportSignatureTest extends TestCase
{
    const KEY = '0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef';

    const TIMESTAMP = '1700000000';

    const NONCE = 'abcdefghijklmnopqrstuvwxyz012345';

    protected function setUp()
    {
        parent::setUp();

        config([
            'cache.default' => 'array',
            'services.iaw_export.key' => self::KEY,
            'services.iaw_export.host' => 'legacy.itsallwidgets.com',
        ]);
    }

    /**
     * The vectors are shared with the new app's LegacySignature test.
     *
     * @return void
     */
    public function testSignatureMatchesTheSharedVectors()
    {
        $get = IawExportSignature::canonical('get', '/iaw-export/tables/users', 'after_id=10', self::TIMESTAMP, self::NONCE, '');
        $post = IawExportSignature::canonical('POST', '/iaw-export/maintenance', '', self::TIMESTAMP, self::NONCE, '{"down":true,"lease":10}');

        $this->assertSame('06ecb2dd7669c1cdc34c6bd1a456c025116b38bcd46a1b04e3064170a70c527a', IawExportSignature::sign($get, self::KEY));
        $this->assertSame('dff6c5bd57a42198a93887cba880690db166b91673d5d7089e5a9df861231bd6', IawExportSignature::sign($post, self::KEY));
    }

    public function testCanonicalStringLayout()
    {
        $this->assertSame(
            "IAW1\nGET\n/iaw-export/manifest\n\n" . self::TIMESTAMP . "\n" . self::NONCE . "\n" . hash('sha256', ''),
            IawExportSignature::canonical('GET', '/iaw-export/manifest', '', self::TIMESTAMP, self::NONCE, '')
        );
    }

    public function testMissingHeadersAreA404()
    {
        $this->assertRejected(Request::create('/iaw-export/manifest', 'GET'));
    }

    public function testASignedRequestPassesOnceOnly()
    {
        $request = $this->signedRequest('GET', '/iaw-export/tables/users?after_id=10');

        $this->assertSame('passed', $this->runMiddleware($request));

        $this->assertRejected($request);
    }

    public function testAWrongSignatureIsA404()
    {
        $request = $this->signedRequest('GET', '/iaw-export/manifest');
        $request->headers->set('X-IAW-Signature', str_repeat('0', 64));

        $this->assertRejected($request);
    }

    public function testATamperedQueryIsA404()
    {
        $request = $this->signedRequest('GET', '/iaw-export/tables/users?after_id=10');
        $request->server->set('QUERY_STRING', 'after_id=0');

        $this->assertRejected($request);
    }

    public function testAStaleTimestampIsA404()
    {
        $this->assertRejected($this->signedRequest('GET', '/iaw-export/manifest', '', time() - 301));
    }

    public function testAnUnknownQueryParameterIsA404()
    {
        $this->assertRejected($this->signedRequest('GET', '/iaw-export/tables/users?after_id=10&debug=1'));
        $this->assertRejected($this->signedRequest('GET', '/iaw-export/tables/users?after_id=ten'));
    }

    public function testAShortKeyIsA404()
    {
        config(['services.iaw_export.key' => 'too-short']);

        $this->assertRejected($this->signedRequest('GET', '/iaw-export/manifest'));
    }

    public function testASignedPostWithABodyPasses()
    {
        $request = $this->signedRequest('POST', '/iaw-export/maintenance', '{"down":true,"lease":10}');

        $this->assertSame('passed', $this->runMiddleware($request));
    }

    public function testTheExportRouteIsA404WithoutASignature()
    {
        $this->get('https://legacy.itsallwidgets.com/iaw-export/manifest')->assertStatus(404);
    }

    public function testPlainHttpIsA404()
    {
        $this->assertRejected($this->signedRequest('GET', '/iaw-export/manifest', '', null, 'http'));

        $this->signedCall('POST', '/iaw-export/maintenance', '{"down":false,"lease":null}', 'http')->assertStatus(404);
    }

    public function testMaintenanceGoesDownAndComesBackUp()
    {
        $downFile = storage_path('framework/down');

        if (file_exists($downFile)) {
            $this->markTestSkipped('The app is already down for maintenance.');
        }

        try {
            $this->signedCall('POST', '/iaw-export/maintenance', '{"down":true,"lease":10}')
                ->assertStatus(200)
                ->assertJson(['down' => true]);

            $payload = json_decode(file_get_contents($downFile), true);
            $downTime = $payload['time'];

            $this->assertSame("We're moving to a new home. Back in a few minutes.", $payload['message']);
            $this->assertSame(120, $payload['retry']);
            $this->assertSame([], $payload['allowed']);
            $this->assertTrue($payload['iaw']);
            $this->assertEquals(time() + 600, $payload['iaw_until'], '', 5);

            $this->get('http://itsallwidgets.com/')
                ->assertStatus(503)
                ->assertSee('Back in a few minutes.');

            // The export keeps answering while down, and going down again
            // keeps the original time.
            $this->signedCall('POST', '/iaw-export/maintenance', '{"down":true,"lease":null}')
                ->assertStatus(200)
                ->assertJson(['down' => true, 'down_time' => $downTime]);

            $this->assertNull(json_decode(file_get_contents($downFile), true)['iaw_until']);

            $this->signedCall('POST', '/iaw-export/maintenance', '{"down":"yes"}')->assertStatus(422);
            $this->signedCall('POST', '/iaw-export/maintenance', '{"down":true,"lease":500}')->assertStatus(422);

            $this->signedCall('POST', '/iaw-export/maintenance', '{"down":false,"lease":null}')
                ->assertStatus(200)
                ->assertJson(['down' => false, 'down_time' => null]);

            $this->assertFileNotExists($downFile);
        } finally {
            @unlink($downFile);
        }
    }

    public function testAnExpiredLeaseBringsTheSiteBackUp()
    {
        $downFile = storage_path('framework/down');

        if (file_exists($downFile)) {
            $this->markTestSkipped('The app is already down for maintenance.');
        }

        try {
            file_put_contents($downFile, json_encode([
                'time' => time() - 3600,
                'message' => null,
                'retry' => 120,
                'allowed' => [],
                'iaw' => true,
                'iaw_until' => time() - 60,
            ]));

            $this->get('https://legacy.itsallwidgets.com/iaw-export/manifest')->assertStatus(404);

            $this->assertFileNotExists($downFile);
        } finally {
            @unlink($downFile);
        }
    }

    public function testAnExpiredLeaseOnAManualDownStaysDown()
    {
        $downFile = $this->freeDownFile();

        try {
            file_put_contents($downFile, json_encode([
                'time' => time() - 3600,
                'message' => null,
                'retry' => null,
                'allowed' => [],
                'iaw_until' => time() - 60,
            ]));

            $this->get('https://legacy.itsallwidgets.com/iaw-export/manifest')->assertStatus(404);

            $this->assertFileExists($downFile);
        } finally {
            @unlink($downFile);
        }
    }

    public function testAManualDownIsA409()
    {
        $downFile = $this->freeDownFile();

        try {
            // What artisan down writes.
            $manual = json_encode([
                'time' => time() - 60,
                'message' => null,
                'retry' => null,
                'allowed' => [],
            ]);

            file_put_contents($downFile, $manual);

            $this->signedCall('POST', '/iaw-export/maintenance', '{"down":true,"lease":10}')
                ->assertStatus(409)
                ->assertExactJson(['message' => 'down by hand']);

            $this->assertSame($manual, file_get_contents($downFile));

            $this->signedCall('POST', '/iaw-export/maintenance', '{"down":false,"lease":null}')
                ->assertStatus(409)
                ->assertExactJson(['message' => 'down by hand']);

            $this->assertSame($manual, file_get_contents($downFile));
        } finally {
            @unlink($downFile);
        }
    }

    public function testComingUpWhenAlreadyUpIsA200()
    {
        $downFile = $this->freeDownFile();

        $this->signedCall('POST', '/iaw-export/maintenance', '{"down":false,"lease":null}')
            ->assertStatus(200)
            ->assertJson(['down' => false, 'down_time' => null]);

        $this->assertFileNotExists($downFile);
    }

    public function testTheMaintenanceBypassIsOnlyOnTheLegacyHost()
    {
        $downFile = $this->freeDownFile();

        try {
            $this->signedCall('POST', '/iaw-export/maintenance', '{"down":true,"lease":10}')
                ->assertStatus(200)
                ->assertJson(['down' => true]);

            $this->get('https://itsallwidgets.com/iaw-export/manifest')->assertStatus(503);
            $this->get('https://legacy.itsallwidgets.com/')->assertStatus(503);

            // On the legacy host the export still answers (a 404 unsigned).
            $this->get('https://legacy.itsallwidgets.com/iaw-export/manifest')->assertStatus(404);
        } finally {
            @unlink($downFile);
        }
    }

    public function testTheMp3RouteRejectsOtherFiles()
    {
        $this->signedCall('GET', '/iaw-export/mp3/episode-1.wav')->assertStatus(404);
        $this->signedCall('GET', '/iaw-export/mp3/episode-999999.mp3')->assertStatus(404);
    }

    /**
     * Send a signed request through the whole app.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  string  $body
     * @param  string  $scheme
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function signedCall($method, $uri, $body = '', $scheme = 'https')
    {
        $signed = $this->signedRequest($method, $uri, $body, null, $scheme);

        return $this->call($method, $scheme . '://legacy.itsallwidgets.com' . $uri, [], [], [], [
            'HTTP_X_IAW_TIMESTAMP' => $signed->headers->get('X-IAW-Timestamp'),
            'HTTP_X_IAW_NONCE' => $signed->headers->get('X-IAW-Nonce'),
            'HTTP_X_IAW_SIGNATURE' => $signed->headers->get('X-IAW-Signature'),
            'CONTENT_TYPE' => 'application/json',
        ], $body);
    }

    /**
     * Build a request signed the way the new app signs it.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  string  $body
     * @param  int|null  $timestamp
     * @param  string  $scheme
     * @return \Illuminate\Http\Request
     */
    protected function signedRequest($method, $uri, $body = '', $timestamp = null, $scheme = 'https')
    {
        $request = Request::create($scheme . '://legacy.itsallwidgets.com' . $uri, $method, [], [], [], [], $body);
        $timestamp = (string) ($timestamp === null ? time() : $timestamp);
        $nonce = str_random(32);

        $canonical = IawExportSignature::canonical(
            $method,
            $request->getPathInfo(),
            (string) $request->server->get('QUERY_STRING', ''),
            $timestamp,
            $nonce,
            $body
        );

        $request->headers->set('X-IAW-Timestamp', $timestamp);
        $request->headers->set('X-IAW-Nonce', $nonce);
        $request->headers->set('X-IAW-Signature', IawExportSignature::sign($canonical, self::KEY));

        return $request;
    }

    /**
     * The down file's path, skipping the test when the app is already down.
     *
     * @return string
     */
    protected function freeDownFile()
    {
        $downFile = storage_path('framework/down');

        if (file_exists($downFile)) {
            $this->markTestSkipped('The app is already down for maintenance.');
        }

        return $downFile;
    }

    /**
     * Run the middleware and say whether the request got through.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function runMiddleware(Request $request)
    {
        return (new VerifyIawExport)->handle($request, function () {
            return 'passed';
        });
    }

    /**
     * Assert the middleware answers the request with a 404.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function assertRejected(Request $request)
    {
        try {
            $this->runMiddleware($request);
        } catch (NotFoundHttpException $exception) {
            $this->assertSame(404, $exception->getStatusCode());

            return;
        }

        $this->fail('The request should have been answered with a 404.');
    }
}
