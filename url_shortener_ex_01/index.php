<!DOCTYPE html>
<?php

require_once __DIR__ . "/BijectiveFunction.php";
require_once __DIR__ . "/DBConnector.php";
  
$dbConnection  = DBConnector::get()->connect($serverName,$userName,$password,$dbName);
$urlModel      = new Model($dbConnection);
$shortenerAlgo = new BJFunction();
$lastInsertID  = $urlModel->getLastIndex();
if(empty($lastInsertID)) {
    $lastInsertID = 0;
}

// An attempt of a simple router for php
// Simply route all request that are not files
$whiteListedFiles = ["index","DBConnector","BijectiveFunction"];
if(isset($_SERVER["PATH_INFO"])) {
    $path = trim($_SERVER["PATH_INFO"]);
    $path = substr($path,1);
    if(!in_array($path,$whiteListedFiles)) {
        $decodedUrl = $shortenerAlgo->decode($path);
        $data = $urlModel->getUrl($decodedUrl);
        header("Location: ". $data->long_url,true,301);
        exit();
    }
}

if (!empty($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST["long_url"])) { 
        $lastInsertID = $lastInsertID + 1;
        $shortUrl = $shortenerAlgo->encode($lastInsertID);
        $urlModel->addUrl($_POST["long_url"],$shortUrl);
        echo "new url is $shortUrl";
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>shorty</title>
</head>

<body>
    <h2>Easy to use url shortener</h2>
    <form method="POST" action="/index.php">
        <input type="text" placeholder="Enter Long url" name="long_url" />
        <button>Submit</button>
    </form>
</body>

</html>