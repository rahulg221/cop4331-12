<?php
    $inData = getRequestInfo();

    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    $userId = $inData["userId"];

    $connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");

    if($connection->connect_error)
    {
        returnWithError($connection->connect_error);
    }
    else
    {
        $stmt = $connection->prepare("INSERT into Contacts (firstName, lastName, Email, UserID) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $userId);
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