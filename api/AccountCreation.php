<?php
  $inData = getRequestInfo();

  $firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $userName = $inData["userName"];
  $passWord = $inData["passWord"];


  $connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");

  if($connection->connect_error)
  {
    returnWithError($connection->connect_error);
  }
  else
  {
    $stmt = $connection->prepare("INSERT into Users (FirstName, LastName, Login, Password) VALUES(?, ?, ?, ?)");
    $stmt->bind_param("ss", $firstName, $lastName, $userName, $passWord);
    $stmt->execute();
    $stmt->close();
    $connection->close();
    returnWithError("");
  }

  function sendResultInfoAsJson($obj)
  {
    header('Content-type: application/json');
    echo $obj;
  }

  function getRequestInfo()
  {
    return json_decode(file_get_contents('php://input'), true);
  }

  function returnWithError($err)
  {
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
  }


?>
