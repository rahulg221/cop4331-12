<?php
	$inData = getRequestInfo();

	$firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $userName = $inData["username"];
    $passWord = $inData["password"];
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