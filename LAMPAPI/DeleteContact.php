<?php
    $inData = getRequestInfo();

    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    $userID = $inData["userID"];

    $connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");

    if($connection->connect_error)
    {
        returnWithError($connection->connect_error);
    }
    else
    {
        $stmt = $connection->prepare("DELETE FROM Contacts WHERE firstName=? AND lastName=? AND email=? AND userID=?");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $userID);
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
