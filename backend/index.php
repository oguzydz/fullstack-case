<?php
//connect to mysql
$servername = "your_server_name";
$username = "your_username";
$password = "your_password";
$dbname = "your_db_name";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//validate form data
$fullName = validate_input($_POST["fullName"]);
$email = validate_input($_POST["email"]);
$phone = validate_input($_POST["phone"]);

//insert data into mysql
$sql = "INSERT INTO leads (fullName, email, phone) VALUES ('$fullName', '$email', '$phone')";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

function validate_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
