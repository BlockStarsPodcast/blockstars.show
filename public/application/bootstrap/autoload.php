<?php
defined('C5_EXECUTE') or die('Access Denied.');

# Load in the composer vendor files
require_once __DIR__ . "/../../../vendor/autoload.php";

# Add the vendor directory to the include path
ini_set('include_path', __DIR__ . "/../../../vendor" . PATH_SEPARATOR . get_include_path());

// Add vlucas/phpdotenv
$env = new \Dotenv\Dotenv(dirname(__DIR__, 3));

try {
    $env->overload();
    $env->required('C5_ENVIRONMENT');
} catch (\Exception $e) {
    echo "<strong>Environment configuration error</strong><br>";
    echo $e->getMessage();
    echo "<br>Make sure to copy .env.dist and apply all required settings.";
    exit;
}
