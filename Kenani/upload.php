<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$database_name = 'bruteforce'; // New database name

// Create a connection to MySQL server
$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}

// Check if the database 'bruteforce' exists
$sql = "SHOW DATABASES LIKE '$database_name'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // The 'bruteforce' database does not exist, so create it
    $create_db_query = "CREATE DATABASE $database_name";

    if (mysqli_query($conn, $create_db_query)) {
        // Database created successfully
    } else {
        // Error creating database
    }
}

// Close the initial connection to the MySQL server
mysqli_close($conn);

// Connect to the newly created 'bruteforce' database
$conn = mysqli_connect($servername, $username, $password, $database_name);

if (!$conn) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}

// Check if the 'file' table already exists in the 'bruteforce' database
$table_name = 'file';
$sql = "SHOW TABLES LIKE '$table_name'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // The 'file' table does not exist, so create it
    $create_table_query = "CREATE TABLE $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        file VARCHAR(255) NOT NULL,
        type VARCHAR(50) NOT NULL,
        size INT NOT NULL,
        content LONGTEXT NOT NULL
    )";

    if (mysqli_query($conn, $create_table_query)) {
        // Table created successfully
    } else {
        // Error creating table
    }
}

// Initialize JSON response array
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["uploaded_content"])) {
    $subdomains_content = $_POST["uploaded_content"];
    
    // File content details
    $file_name = "uploaded_file.txt"; // Change this to the appropriate file name
    $file_type = "text/plain"; // Change this to the appropriate file type
    $file_size = strlen($subdomains_content);

    // Prepare the SQL query
    $sql = "INSERT INTO file (file, type, size, content) VALUES ('$file_name', '$file_type', $file_size, '$subdomains_content')";

    if (mysqli_query($conn, $sql)) {
        // Insert successful
        $response["status"] = "success";
        $response["message"] = "File content uploaded and stored successfully in the database.";
    } else {
        // Insert failed
        $response["status"] = "error";
        $response["message"] = "Failed to upload and store file content in the database.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tested_content"])) {
    $tested_websites_content = $_POST["tested_content"];

    // Connect to the MySQL server
    $conn = mysqli_connect($servername, $username, $password, $database_name);

    if (!$conn) {
        die('Could not connect to MySQL: ' . mysqli_connect_error());
    }

    // Insert the tested websites content into the database
    $insert_tested_websites_query = "INSERT INTO tested_websites (content) VALUES ('$tested_websites_content')";

    if (mysqli_query($conn, $insert_tested_websites_query)) {
        // Insert successful
        $response["status"] = "success";
        $response["message"] = "Tested websites content uploaded and stored successfully in the database.";
    } else {
        // Insert failed
        $response["status"] = "error";
        $response["message"] = "Failed to upload and store tested websites content in the database.";
    }

    // Close the MySQL connection
    mysqli_close($conn);

    // Send JSON response
    header("Content-Type: application/json");
    echo json_encode($response);
}


// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);
?>