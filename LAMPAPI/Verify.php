<?php
	$inData = getRequestInfo();

	$searchCount = 0;
	$userName = $inData["userName"];

	$connection = new mysqli("localhost", "Admin", "AdminPassWord", "SmallProject");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		$stmt = $connection->prepare("select Login, from Users where (Login like ?)");
		$stmt->bind_param("s", $userName);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc())
		{
			$searchCount++;
		}

		//returns count of usernames that match input
		//if count >= 1, dont allow user to use inputted username
		return($searchCount);
		$stmt->close();
		$connection->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

  ?>
