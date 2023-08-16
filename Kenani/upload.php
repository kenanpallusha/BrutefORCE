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
        size INT NOT NULL
    )";

    if (mysqli_query($conn, $create_table_query)) {
        // Table created successfully
    } else {
        // Error creating table
    }
}

// Initialize JSON response array
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded successfully
    if (isset($_FILES["uploaded_file"]) && $_FILES["uploaded_file"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["uploaded_file"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Read the content of the uploaded file into subdomains_content
        $subdomains_content = file_get_contents($_FILES["uploaded_file"]["tmp_name"]);

        if (isset($_POST["submit"])) {
            if (in_array($fileType, array("jpg", "jpeg", "png", "gif", "txt", "doc", "docx"))) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }
        // Check if $uploadOk is set to 0 by an error

        if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now insert the file details into the database
            $file_name = htmlspecialchars(basename($_FILES["uploaded_file"]["name"]));
            $file_type = $_FILES["uploaded_file"]["type"];
            $file_size = $_FILES["uploaded_file"]["size"];

            // Prepare the SQL query
            $sql = "INSERT INTO file (file, type, size) VALUES ('$file_name', '$file_type', $file_size)";

            // Execute the query
            if (mysqli_query($conn, $sql)) {
                // Do nothing here
            } else {
                // Do nothing here
            }
        } else {
            // Do nothing here
        }

        // Split the content into subdomains
        $subdomains = array_map('trim', explode("\n", $subdomains_content));
        $subdomains = array_filter($subdomains, 'strlen'); // Remove empty lines

        // Set success message in response
        $response["status"] = "success";
        $response["message"] = "File uploaded and processed successfully.";

        // Attach subdomains to the response
        $response["subdomains"] = $subdomains;
    } else {
        // Set error message in response
        $response["status"] = "error";
        $response["message"] = "File upload failed.";
    }
}

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);
?>