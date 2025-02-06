<?php 
namespace Config;
require __DIR__ . "/../vendor/autoload.php";

use PDO;
use PDOException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
class Database {
    private $pdo;
    private static $instance = null;
    private function __construct()
    {
        $dsn = $_ENV["DB_DSN"];
        $username = $_ENV["DB_USERNAME"];
        $password = $_ENV["DB_PASSWORD"];
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "seccuss";
        } catch(PDOException $e) {
            echo "failed to connect: " . $e->getMessage();
        }
    }
    public static function instance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}