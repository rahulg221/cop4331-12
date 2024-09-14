<?php
	$inData = getRequestInfo();

	$searchResults = "";
	$searchCount = 0;

	$connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		$stmt = $connection->prepare("select firstName, lastName, email from Contacts where (firstName like ? or lastName like ? or email like ?) and UserID=?");
		$contactName = "%" . $inData["search"] . "%";
		$stmt->bind_param("ssss", $contactName, $contactName, $contactName, $inData["userId"]);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '{"firstName":"' . $row["firstName"] . '","lastName":"' . $row["lastName"] . '","email":"' . $row["email"] . '"}';
		}

		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
		}
		else
		{
			returnWithInfo( $searchResults );
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

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}


  ?>
