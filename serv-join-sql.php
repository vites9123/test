<?php 
class Database {
    private static $instance = null;
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
        	    self::$instance = new self('localhost', 'root', '', 'test');
        }
        return self::$instance;
    }

    public function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        $this->setConnectionCharset();
    }

    public function setConnectionCharset() {
        $this->connection->set_charset("utf8");
    }

    public function execute_query($query) {
        if ($this->connection->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    public function getConnection() {
        return $this->connection;
    }

    public function close() {
        $this->connection->close();
    }

    public function sanitize_input($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}

?>
