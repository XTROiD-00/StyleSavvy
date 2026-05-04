<?php

include("DBConn.php");

// This code ought to Drop tblUser if it exists
$sqlDrop = "DROP TABLE IF EXISTS tblUser";

if(mysqli_query($conn, $sqlDrop)){
    echo "tblUser deleted successfully if it existed.<br>";
}

// This code ought to Recreate tblUser
$sqlCreate = "CREATE TABLE tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,

     /* added username */
     username VARCHAR(100) NOT NULL UNIQUE,

    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    
    /* added pending status column */
    status VARCHAR(20) DEFAULT 'pending',

    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if(mysqli_query($conn, $sqlCreate)){ 
    echo "tblUser created successfully.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

// This code ought to Open userData.txt
$file = fopen("userData.txt", "r");

if($file){
// This code ought to Read each line
while(($line = fgets($file)) !== false){

    $data = explode(",", trim($line));
    $name = $data[0];

    $username = $data[1];
    $status = $data[4];

    $email = $data[2];
    $password = $data[3];

    $sqlInsert = "INSERT INTO tblUser(full_name, username, email, password_hash, status)
                  VALUES('$name','$username','$email','$password','$status')";

  if(mysqli_query($conn, $sqlInsert)){
            echo "Inserted: $name <br>";
        } else {
            echo "Error inserting $name: " . mysqli_error($conn) . "<br>";
        }

}

fclose($file);

echo "Data loaded successfully.";

} else {
    echo "Error opening userData.txt<br>";
}

mysqli_close($conn);

?>