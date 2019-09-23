<?php

namespace Illuminate\Tests\Integration\Routing\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\ExpiredSignatureException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Orchestra\Testbench\TestCase;

class ValidateSignatureTest extends TestCase
{
    private $middleware;
    private $callback;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/foo', function (Request $request) {
            //
        })->name('foo');

        $this->middleware = new ValidateSignature;

        $this->callback = function () {
            return 'valid';
        };
    }

    public function testItThrowsForExpiredSignature()
    {
        $this->expectException(ExpiredSignatureException::class);

        $url = URL::signedRoute('foo', [], now()->subDay());

        $request = Request::create($url, 'GET');

        $this->middleware->handle($request, $this->callback);
    }

    public function testItThrowsForInvalidSignature()
    {
        $this->expectException(InvalidSignatureException::class);

        $url = URL::signedRoute('foo', [], now()->subDay());

        $request = Request::create('/foo', 'GET');

        $this->middleware->handle($request, $this->callback);
    }

    public function testItHandlesValidSignature()
    {
        $url = URL::signedRoute('foo', [], now()->addDay());

        $request = Request::create($url, 'GET');

        $response = $this->middleware->handle($request, $this->callback);

        $this->assertEquals('valid', $response);
    }
}
