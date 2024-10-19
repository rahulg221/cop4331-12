<?php
    $inData = getRequestInfo();
    $userId = $inData["userId"];

    $contacts = [];
    $connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");

    if ($connection->connect_error)
    {
        returnWithError($connection->connect_error);
    }
    else
    {
        $stmt = $connection->prepare("SELECT firstName, lastName, Email FROM Contacts WHERE UserID=?");
        $stmt->bind_param("s", $userId);


        if ($stmt === false) {
            returnWithError($connection->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $contacts[] = [
                'firstName' => $row['firstName'],
                'lastName' => $row['lastName'],
                'email' => $row['Email'],
            ];
        }

        if (count($contacts) == 0) {
            returnWithError("No Records Found");
        } else {
            returnWithInfo($contacts);
        }

        $stmt->close();
        $connection->close();
    }

    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err)
    {
        $retValue = '{"contacts":[], "error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    function returnWithInfo($contacts)
    {
        $retValue = json_encode(['contacts' => $contacts, 'error' => '']);
        sendResultInfoAsJson($retValue);
    }
?>