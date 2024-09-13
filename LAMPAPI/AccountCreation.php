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
    $stmt->bind_param("ssss", $firstName, $lastName, $userName, $passWord);

    if($stmt->execute()){
      echo 'executed';
    } else{
      echo 'dont work';
    }
    
    $stmt->close();
    $connection->close();
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