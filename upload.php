<?php

if (isset($_POST['submit'])) 
	{
		$handle = fopen($_FILES['filename']['tmp_name'], "r");
		$headers = fgetcsv($handle, 1000, ",");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
			$data[0];
			$data[1];
		}
fclose($handle);
	}
?>