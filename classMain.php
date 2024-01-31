<?php

	Class dbObj{
		
		/* Database connection start */
		var $dbhost   = "localhost";
		var $username = "root";
		var $password = "";
		var $dbname   = "db_mysite";
		var $conn;

		function getConnstring() {
			$con = mysqli_connect($this->dbhost, $this->username, $this->password, $this->dbname) or 
			       die("Connection failed: " . mysqli_connect_error());

			/* check connection */
			if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			} else {
				$this->conn = $con;
			}
			return $this->conn;
		}

		function checkLogin($user,$pass) {
			$sql = "SELECT * FROM user WHERE username = '$user' AND password = '$pass'";
			$res = mysqli_query($this->conn,$sql);
			$row = mysqli_fetch_array($res,MYSQLI_ASSOC);
			$cnt = mysqli_num_rows($res);

			return $cnt;
		}
	}

?>