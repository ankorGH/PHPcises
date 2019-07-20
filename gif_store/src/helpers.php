<?php

require_once __DIR__ . "/../vendor/autoload.php";

const MAX_FILE_SIZE = 1000000000;
const ENV_PATH = __DIR__ . "/../";

$dotenv =  \Dotenv\Dotenv::create(ENV_PATH);
$dotenv->load();

/**
 * Configure cloudinary
 */
\Cloudinary::config([
    "cloud_name" => getenv("CLOUDINARY_CLOUD_NAME"), 
    "api_key" => getenv("CLOUDINARY_API_KEY"), 
    "api_secret" => getenv("CLOUDINARY_API_SECRET"), 
    "secure" => true
]);
  

/**
 * Make sure user input is safe for use
 * 
 * @param string userInput
 * @return string
 */
function sanitizeUserInput(string $userInput) : string {
    $userInput = trim($userInput);
    return htmlspecialchars($userInput);
}

/**
 * Return file name from file mime data
 * 
 * @param string filename
 * @return string
 */
function getFileName(string $filename) : string {
    $names = explode(".",$filename);
    array_pop($names);
    return implode("",$names); 
}

/**
 * Checks if the file is an image
 * 
 * @param string filename
 * @return bool
 */
function isImage(string $filename) : bool {
    return (bool) preg_match("/(png|gif|jpg|jpeg)/", pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Get Definition of error code
 * 
 * @param int errorCode
 * @return string
 */
function defineErrorCode(int $errorCode) : string {
    $errors = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    ];

    return $errors[$errorCode];
}