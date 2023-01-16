<?php
// allow cors policy from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json; charset=utf-8');

//connect to mysql
$servername = "eu-cdbr-west-03.cleardb.net";
$username = "b84061d244cdae";
$password = "a953ff66";
$dbname = "heroku_0c92e04d832ad4b";



$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if leads table exists
$sql = "SHOW TABLES LIKE 'leads'";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) != 1) {
  //create leads table
  $createTableSql = "CREATE TABLE leads (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        fullName VARCHAR(30) NOT NULL,
        email VARCHAR(50) NOT NULL,
        phone VARCHAR(20) NOT NULL
        )";
  if ($conn->query($createTableSql) !== TRUE) {
    echo "Error creating table: " . $conn->error;
  }
}

$json = file_get_contents('php://input');

if ($json) {
  $json = json_decode($json, true);

  //validate form data
  $fullName = validate_input($json["fullName"]);
  $email = validate_input($json["email"]);
  $phone = validate_input($json["phone"]);

  //insert data into mysql
  $sql = "INSERT INTO leads (fullName, email, phone) VALUES ('$fullName', '$email', '$phone')";

  if ($conn->query($sql) === TRUE) {
    $response = array(
      "status" => "success",
      "message" => "New record created successfully"
    );
    echo json_encode($response);
  } else {
    $response = array(
      "status" => "error",
      "message" => "Error: " . $sql . "<br>" . $conn->error
    );
    echo json_encode($response);
  }
}


$conn->close();


function validate_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
