<?php


	function connect_db($name)
	{
		try
		{
			@ $con = mysqli_connect('localhost', 'root', 'OgfTt&TTtG?', $name);

			if (mysqli_connect_errno()) {
			   printf("Connect failed: %s\n", mysqli_connect_error());
			   exit();
			}
			
			$con->select_db($name);
			
			if (!$con->set_charset('utf8'))
			   printf("Error loading character set utf8: %s\n", $mysqli->error);
			
			//echo $con;
			
			return $con;
		}
		catch (Exception $e)
		{
			return -1;
		}
    }
    

?>