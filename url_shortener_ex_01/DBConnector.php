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
    
    /*
    *  Add url (long form and short form)
    *
    *  @param string $longUrl
    *  @param string $shortUrl
    *  @return mixed
    */
    public function addUrl(string $longUrl, string $shortUrl) 
    {
        $stmt = $this->connection->prepare("INSERT INTO urls (long_url,short_url) VALUES (:longUrl,:shortUrl)");
        $stmt->bindParam(":longUrl",$longUrl);
        $stmt->bindParam(":shortUrl",$shortUrl);
        $stmt->execute();
    }
    
    /*
    *  Get url (long form)
    *
    *  @param int $id
    *  @return mixed
    */
    public function getUrl(int $id) 
    {
        $stmt = $this->connection->prepare("SELECT * FROM urls WHERE id=:id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    /*
    *  Get last index of table
    *
    *  @return int
    */
    public function getLastIndex() : int
    {
        $stmt = $this->connection->prepare("SELECT MAX(id) FROM urls;");
        $stmt->execute();
        $maxId = "MAX(id)";
        return (int) $stmt->fetchObject()->$maxId;
    }
}