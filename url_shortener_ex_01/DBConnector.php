<?php
$serverName = "localhost";
$userName   = "root";
$password   = "ajana";
$dbName     = "url_shortener";

class DBConnector {
    private static $conn = null;

    public function connect(string $serverName, string $userName, string $password, string $dbName)
    {
        try {
            $conn = new PDO("mysql:host=$serverName;dbname=$dbName",$userName,$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch (PDOException $e) {
            echo "Connection failed " . $e->getMessage();
        }
    }
    
    public static function get() {
        if(static::$conn === null) {
            static::$conn = new static();
        }
        return static::$conn;
    }
}

class Model {
    private $connection = null;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    
    public function addUrl(string $longUrl, string $shortUrl) 
    {
        $stmt = $this->connection->prepare("INSERT INTO urls (long_url,short_url) VALUES (:longUrl,:shortUrl)");
        $stmt->bindParam(":longUrl",$longUrl);
        $stmt->bindParam(":shortUrl",$shortUrl);
        $stmt->execute();
    }
    
    public function getUrl(int $id) 
    {
        $stmt = $this->connection->prepare("SELECT * FROM urls WHERE id=:id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    public function getLastIndex()
    {
        $stmt = $this->connection->prepare("SELECT LAST_INSERT_ID();");
        $stmt->execute();
        $lastIndex =  $stmt->fetchObject();
        $index = "LAST_INSERT_ID()";
        return $lastIndex->$index;
    }
}