
<?php
// create database

$_servername = 'localhost';
$_username = 'root';
$_password = '';
$db = 'myrecipes';

$connection = new mysqli($_servername, $_username, $_password, $db);

// check if the connection has been made
if($connection->connect_error){
    echo 'Connection failed';
}
else{
    echo 'Connection successfull <br>';
}


$sql = 
    "CREATE TABLE USERS(
        user_id int auto_increment primary key,
        firstname varchar(50) not null,
        lastname varchar(50) not null,
        username varchar(50) not null,
        email varchar(50) not null,
        password varchar(50) not null);";

if($connection->query($sql) === true){
    echo "Relations created <br>";
}

?>