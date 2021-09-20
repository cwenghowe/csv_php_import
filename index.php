

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
        echo "information saved!";
    }
?>
    <form enctype='multipart/form-data' action='' method='post'>
    
        <label>Upload Product CSV file Here</label>
        <br>
        <input size='100' type='file' name='filename'>
        </br>
        <input type='submit' name='submit' value='Upload Products'>

    </form>

    <?php

    function printNameListRow($i, $data1, $data2, $data3, $data4, $header=FALSE) {
        if($header){
            return print "<th>".$i."</th><th>".$data1."</th><th>".$data2."</th><th>".$data3."</th><th>".$data4."</th>";
        } else {
            $open = "<tr>";
            $end  = "</tr>";
            $init  = "<td>".$i."</td>";
            $form1 = "<input type='text' name='matric[]' value='".$data1."'></input>";
            $form2 = "<input type='text' name='name[]' value='".$data2."' size='50'></input>";
            $form3 = "<input type='text' name='email[]' value='".$data3."'></input>";
            $form4 = "<input type='text' name='group[]' value='".$data4."'></input>";
            return print $open.$init."<td>".$form1."</td><td>".$form2."</td><td>".$form3."</td><td>".$form4."</td>".$end;
        }
    }

    if (isset($_POST['submit'])) 
	{
		$handle = fopen($_FILES['filename']['tmp_name'], "r");
		$headers = fgetcsv($handle, 1000, ","); // remove header
        $student = 0;
        echo "<form method='post' action=''><table border=1>";
        printNameListRow("NO.",$headers[0],$headers[1],$headers[2],$headers[3],TRUE);
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
           printNameListRow($student+1,$data[0],$data[1],$data[2],$data[3]);
           $student++;
		}
        
        echo "</table>";
        echo "Total students in the list: ".$student."<br>";
        echo "<input type='submit' name='save' value='save'></input></form>";
        echo "<br>";
        fclose($handle);
    }
    ?>

</body>

</html>