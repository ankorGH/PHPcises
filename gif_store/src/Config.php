<?php

require_once __DIR__ . "/../vendor/autoload.php";

const ENV_PATH = __DIR__ . "/../";
$dotenv =  \Dotenv\Dotenv::create(ENV_PATH);
$dotenv->load();

return $Config  = [
    
    /**
     * CLOUDINARY CREDENTIALS
     */

    "CLOUDINARY_CLOUD_NAME" => getenv("CLOUDINARY_CLOUD_NAME"),
    "CLOUDINARY_API_SECRET" => getenv("CLOUDINARY_API_SECRET"),
    "CLOUDINARY_API_KEY"    => getenv("CLOUDINARY_API_KEY"),
    
    /**
     * DATABASE CREDENTIALS
     */
    
    "DATABASE_HOST" => getenv("DATABASE_HOST"),
    "DATABASE_PORT" => getenv("DATABASE_PORT"),
    "DATABASE_NAME" => getenv("DATABASE_NAME"),
    "DATABASE_USER" => getenv("DATABASE_USER"),
    "DATABASE_PASSWORD" => getenv("DATABASE_PASSWORD"),
];