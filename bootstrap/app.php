<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
        $exceptions->render(function (\Throwable $e) {
            echo "<div style='background:#fee;color:#900;padding:20px;border:2px solid #900;font-family:sans-serif;'>";
            echo "<h2>Original Laravel Boot Exception Caught:</h2>";
            echo "<b>Message:</b> " . $e->getMessage() . "<br><br>";
            echo "<b>File:</b> " . $e->getFile() . " on line " . $e->getLine() . "<br><br>";
            echo "<b>Stack Trace:</b><pre style='background:#fff;padding:10px;border:1px solid #ccc;overflow:auto;'>" . $e->getTraceAsString() . "</pre>";
            echo "</div>";
            exit;
        });
    })->create();

if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
    $app->useStoragePath('/tmp');
    $storageDirs = [
        '/tmp/framework/views',
        '/tmp/framework/sessions',
        '/tmp/framework/cache',
        '/tmp/logs'
    ];
    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

return $app;

