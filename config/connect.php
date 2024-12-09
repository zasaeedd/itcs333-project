<?php
// config/connect.php - Establish a database connection using PDO

// Include the configuration file that contains database credentials
require_once __DIR__ . '/config.php';

// Connects to the database using PDO and returns the connection instance.
function connect() {
    // Create the DSN (Data Source Name) for the database connection
    $dsn = "mysql:unix_socket=/opt/lampp/var/mysql/mysql.sock;dbname=" . DB_NAME . ";charset=utf8";

    try {
        // Initialize a new PDO instance with the DSN, username, and password
        $pdo = new PDO($dsn, DB_USER, DB_PASS);

        // Configure PDO to throw exceptions for errors
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Return the PDO instance to be used in other parts of the application
        return $pdo;

    } catch (PDOException $e) {
        // Handle any errors that occur during the connection process
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
