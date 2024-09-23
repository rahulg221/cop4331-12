<?php
    echo 'Hello world';

	$inData = getRequestInfo();
    $results = "";

    $newFirstName = $inData["newFirstName"];
    $newLastName = $inData["newLastName"];
    $newEmail = $inData["newEmail"];

    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $email = $inData["email"];
    $userid = $inData["userID"];

	$connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		$stmt = $connection->prepare("UPDATE Contacts SET firstName=?, lastName=?, Email=? WHERE firstName=? AND lastName=? AND Email=? AND UserID=?");
		$stmt->bind_param("sssssss", $newFirstName, $newLastName, $newEmail, $firstName, $lastName, $email, $userid);
		$stmt->execute();
		$stmt->close();
		$connection->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $err )
	{
        $retValue = '{"firstName":"","lastName":"","email":"","userID":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
        $retValue = $searchResults;
		sendResultInfoAsJson( $retValue );
	}
  ?>
