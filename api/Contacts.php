<?php

  $inData = getRequestInfo();

  //Admin AdminPassWord
  // the strings for the json packages are placeholders and may need to be changed 
  $firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $email = $inData["email"];

  //          local account with privileges, username and password, database
  $connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");

  if($connection->connect_error)
  {
    returnWithError($connection->connect_error);
  }
  else
  {
    $stmt = $connection->prepare("INSERT into Contacts (firstName, lastName, Email) VALUES(?, ?, ?)");
    $stmt->bind_param("ss", $firstName, $lastName, $email);
    $stmt->execute();
    $stmt->close();
    $connection->close();
    returnWithError("");
  }

  function getRequestInfo()
  {
    return json_decode(file_get_contents('php://input'), true);
  }

  function sendResultInfoAsJson($obj)
  {
    header('Content-type: application/json');
    echo $obj;
  }

  function returnWithError($err)
  {
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
  }

?>
