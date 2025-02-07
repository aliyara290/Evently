<?php
namespace App\Core;
use Config\Database;
use PDO;
use PDOException;

class Models
{
    private static $pdo;

    public function __construct()
    {
        self::$pdo = Database::getInstance();
    }

    public static function create($table, $data)
    {
        $columns = implode(",", array_keys($data));
        $values = implode(",", array_map(fn($col) => ":$col", array_keys($data)));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        try {
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute($data);
            return true;
        } catch (PDOException $error) {
            error_log("Failed to insert data: " . $error->getMessage());
            return false;
        }
    }


    public static function update($table, $data, $condition, $cond_value)
    {
        $columns = implode(",", array_map(fn($col) => "$col = :$col", array_keys($data)));
        $sql = "UPDATE $table SET $columns WHERE $condition = :condition_value";

        try {
            $stmt = self::$pdo->prepare($sql);
            $data["condition_value"] = $cond_value;
            $stmt->execute($data);
            return true;
        } catch (PDOException $error) {
            echo "Failed to update data: " . $error->getMessage();
            return false;
        }
    }

    public static function delete($table, $condition, $cond_value)
    {
        $sql = "DELETE FROM $table WHERE $condition = :id";
        try {
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute(["id" => $cond_value]);
            return true;
        } catch (PDOException $error) {
            echo "Failed to delete data: " . $error->getMessage();
            return false;
        }
    }

    public static function readByCondition($table, $condition, $cond_value)
    {
        $sql = "SELECT * FROM $table WHERE $condition = :id";
        try {
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute(["id" => $cond_value]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo "Failed to fetch data: " . $error->getMessage();
            return false;
        }
    }

    public static function readAll($table)
    {
        $sql = "select * from $table";
        try {
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $error) {
            echo "Failed to fetch data: " . $error->getMessage();
            return false;
        }
    }

    public static function count($table)
    {
        $sql = "SELECT COUNT(*) AS total FROM $table";
        try {
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $error) {
            echo "Failed to count data: " . $error->getMessage();
            return 0;
        }
    }
}
new Models();
