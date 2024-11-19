<?php
// local development server connection
// $servername = 'localhost';
// $username = "root";
// $password = "";
// $db = "recipe_sharing";


// remote database connection
$servername = 'localhost';
$username = "daisy.tsenesa";
$password = "dkt11.py";
$db = "webtech_fall2024_daisy_tsenesa";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
    echo "Connected successfully";
}
echo "lorem adhfuhbuunhjyhq4ygbrtvtb";
?>