

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo import CSV/Excel</title>
</head>

<body>
<?php
    if(isset($_POST['save'])) {
        print "information saved!";
    }
?>
    <form enctype='multipart/form-data' action='' method='post'>
    
        <label>Upload Product CSV file Here</label>
        <br>
        <input size='50' type='file' name='filename'>
        </br>
        <input type='submit' name='submit' value='Upload Products'>

    </form>

    <?php

    function printNameListRow($data1, $data2, $header=FALSE) {
        if($header){
            return print "<th>".$data1."</th><th>".$data2."</th>";
        } else {
            $open = "<tr>";
            $end  = "</tr>";
            $form1 = "<input type='text' name='matric[]' value='".$data1."'></input>";
            $form2 = "<input type='text' name='name[]' value='".$data2."'></input>";
            return print $open."<td>".$form1."</td><td>".$form2."</td>".$end;
        }
    }

    if (isset($_POST['submit'])) 
	{
		$handle = fopen($_FILES['filename']['tmp_name'], "r");
		$headers = fgetcsv($handle, 1000, ","); // remove header
		print "<form method='post' action=''><table border=1>";
        printNameListRow($headers[0],$headers[1],TRUE);
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
           printNameListRow($data[0],$data[1]);
		}
        
        print "</table>";
        print "<input type='submit' name='save' value='save'></input></form>";
        print "<br>";
        fclose($handle);
    }
    ?>

</body>

</html>