<?php

// Diagnostic test
if (isset($_GET['test_php'])) {
    echo "PHP is working! Version: " . phpversion();
    exit;
}

// Forward Vercel requests to normal Laravel index.php
require __DIR__ . '/../public/index.php';
