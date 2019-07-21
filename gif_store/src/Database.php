<?php

use Dotenv\Exception\ExceptionInterface;

class DatabaseConnection 
{
    private static $connection = null;
    
    public static function get()  {
        if(static::$connection === null) static::$connection = new static();
        return static::$connection;
    }
    
    public function connect() {
        try 
        {
            $connectionString = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
                getenv('DATABASE_HOST'),
                getenv('DATABASE_PORT'),
                getenv('DATABASE_NAME'),
                getenv('DATABASE_USER'),
                getenv('DATABASE_PASSWORD')
            );

            $pdo = new \PDO($connectionString);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } 
        catch(PDOException $e) {
            throw new Exception("Error connecting to database.  " . $e->getMessage());
        }
    }
}

class MediaModel 
{
    private static $conn;
    
    public function __construct() {
        try{
            static::$conn = DatabaseConnection::get()->connect();   
            self::create();
        }
        catch(PDOException $e) {
            throw new Exception("Error connecting to database" . $e->getMessage());
        }
    }
    
    public static function create() {
            $stmt = "CREATE TABLE IF NOT EXISTS media (
                id SERIAL PRIMARY KEY,
                name character varying(255),
                url character varying(255) NOT NULL UNIQUE
            );";
        try {
            static::$conn->exec($stmt);
        } 
        catch(PDOException $e) {
            throw new Exception("Error creating table" . $e->getMessage());
        }
    }

    public static function addMedia(string $url,string $name="") {
        $sql = "INSERT INTO media(name,url) VALUES(:name,:url)";
        
        try {
            $stmt = static::$conn->prepare($sql);
            $stmt->bindValue(":name",$name);
            $stmt->bindValue(":url",$url);
            $stmt->execute();
        }
        catch(PDOException $e) { 
            throw new Exception("Error inserting data " . $e->getMessage());
        }
    }

    public static function all() {
        $sql = "SELECT * FROM media";
        $stmt = static::$conn->query($sql,PDO::FETCH_ASSOC);
        $cols = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cols[] = [ 
                "name" => $row["name"],
                "url"  => $row["url"]
            ];
        }
        return $cols;
    }
}     