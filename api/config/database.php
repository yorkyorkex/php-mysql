<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'course_management';
    private $username = 'root'; // Change this to your MySQL username
    private $password = '';     // Change this to your MySQL password
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Try PDO first
            if (extension_loaded('pdo_mysql')) {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } else {
                // Fallback to mysqli
                $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
                if ($this->conn->connect_error) {
                    throw new Exception("Connection failed: " . $this->conn->connect_error);
                }
            }
        } catch(Exception $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>