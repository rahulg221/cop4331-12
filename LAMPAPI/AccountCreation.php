<?php
  $inData = getRequestInfo();

  $firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $userName = $inData["userName"];
  $passWord = $inData["passWord"];
  $email = $inData["email"];


  $connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");

  if($connection->connect_error)
  {
    returnWithError($connection->connect_error);
  }
  else
  {
    $stmt = $connection->prepare("INSERT into Users (FirstName, LastName, Login, Password, Email) VALUES(?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $userName, $passWord, $email);
    $stmt->execute();
    $id = mysqli_insert_id($connection);
    sendResultInfoAsJson('{"id":' . $id . '}');

    /*
    if($stmt->execute()){
      echo 'Account created';
      $id = mysqli_insert_id($connection);
      echo 'ID retrieved';
      returnWithInfo('{"id":' . $id . '}');
    } else{
      returnWithError("Cannot create account");
    }
    */
    
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