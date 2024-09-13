<?php
	$inData = getRequestInfo();

	$id = 0;
	$firstName = "";
	$lastName = "";

	$connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");
   	
	if( $connection->connect_error )
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		$stmt = $connection->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $inData["username"], $inData["password"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{
			returnWithInfo( $row['firstName'], $row['lastName'], $row['ID'] );
		}
		else
		{
			returnWithError("No Records Found");
		}

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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>
