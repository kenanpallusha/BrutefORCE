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
        echo "Database 'bruteforce' created successfully.";
    } else {
        echo "Error creating database: " . mysqli_error($conn);
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
        echo "Table 'file' created successfully.";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));




// Check if file is a valid image or text/doc file
if (isset($_POST["submit"])) {
    if (in_array($fileType, array("jpg", "jpeg", "png", "gif", "txt", "doc", "docx"))) {
        echo "File is a valid type.";
        $uploadOk = 1;
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, GIF, TXT, DOC, and DOCX files are allowed.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // File uploaded successfully, now insert the file details into the database
        $file_name = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
        $file_type = $_FILES["fileToUpload"]["type"];
        $file_size = $_FILES["fileToUpload"]["size"];

        // Prepare the SQL query
        $sql = "INSERT INTO file (file, type, size) VALUES ('$file_name', '$file_type', $file_size)";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            echo "The file $file_name has been uploaded and its details have been saved to the database.";
        } else {
            echo "Sorry, there was an error uploading your file and saving its details to the database: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
