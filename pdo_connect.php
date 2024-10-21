<?php
require_once("db.config.inc.php"); // Make sure this file properly initializes $DB_LINK_PDO

class pdo_connect
{
    public function __construct()
    {
        // Check if the database connection is established
        global $DB_LINK_PDO;
        if ($DB_LINK_PDO === null) {
            throw new Exception("Database connection is not initialized.");
        }
    }

    public function getQueryAll($sql, $params = [])
    {
        global $DB_LINK_PDO;
        try {
            $query = $DB_LINK_PDO->prepare($sql);
            $query->execute($params);
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
            return array_values($data);
        } catch (PDOException $e) {
            echo "Database query failed: " . $e->getMessage();
            return false;
        }
    }

    public function getQuerySingle($sql, $params = [])
    {
        global $DB_LINK_PDO;
        try {
            $query = $DB_LINK_PDO->prepare($sql);
            $query->execute($params);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Database query failed: " . $e->getMessage();
            return false;
        }
    }

    public function countQuerySingle($sql, $params = [])
    {
        global $DB_LINK_PDO;
        try {
            $query = $DB_LINK_PDO->prepare($sql);
            $query->execute($params);
            return $query->rowCount();
        } catch (PDOException $e) {
            echo "Database query failed: " . $e->getMessage();
            return false;
        }
    }

    public function executeQueryData($sql, $params = [])
    {
        global $DB_LINK_PDO;

        try {
            if (preg_match('/^\s*(UPDATE|INSERT|DELETE)\s/i', $sql)) {
                $query = $DB_LINK_PDO->prepare($sql);
                return $query->execute($params);
            } else {
                throw new Exception("SQL statement is not an UPDATE, INSERT, or DELETE query.");
            }
        } catch (PDOException $e) {
            echo "Database query failed: " . $e->getMessage();
            return false;
        }
    }

    public function sanitizeText($input)
    {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    public function sanitizeNumber($input)
    {
        return trim(filter_var($input, FILTER_SANITIZE_NUMBER_INT));
    }

    public function sanitizeEmail($input)
    {
        return trim(filter_var($input, FILTER_SANITIZE_EMAIL));
    }

    public function validateInput($input, $type, $required = true)
    {
        $input = trim($input);
        if ($required && empty($input)) {
            return false;
        }

        switch ($type) {
            case 'text':
                return htmlspecialchars(strip_tags($input));
            case 'email':
                $input = filter_var($input, FILTER_SANITIZE_EMAIL);
                return filter_var($input, FILTER_VALIDATE_EMAIL) ? $input : false;
            case 'number':
                $input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
                return filter_var($input, FILTER_VALIDATE_INT) ? $input : false;
            case 'url':
                $input = filter_var($input, FILTER_SANITIZE_URL);
                return filter_var($input, FILTER_VALIDATE_URL) ? $input : false;
            case 'boolean':
                return filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            case 'password':
                return htmlspecialchars(trim($input));
            default:
                return $input;
        }
    }

    public function hashing($data)
    {
        $options = [
            'memory_cost' => 1 << 17,
            'time_cost' => 4,
            'threads' => 2,
        ];
        return password_hash($data, PASSWORD_ARGON2ID, $options);
    }
}
