<?php

// Diagnostic test
if (isset($_GET['test_php'])) {
    echo "PHP is working! Version: " . phpversion();
    exit;
}

// Enable error reporting to screen for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Shutdown function to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<pre style='background:#fee;color:#900;padding:20px;border:1px solid #900;'>";
        echo "<b>Fatal Error Caught:</b><br>";
        echo "Message: " . $error['message'] . "<br>";
        echo "File: " . $error['file'] . "<br>";
        echo "Line: " . $error['line'] . "<br>";
        echo "</pre>";
    }
});

try {
    // Forward Vercel requests to normal Laravel index.php
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<pre style='background:#fee;color:#900;padding:20px;border:1px solid #900;'>";
    echo "<b>Exception Caught:</b><br>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "Trace:<br>" . $e->getTraceAsString() . "<br>";
    echo "</pre>";
}
