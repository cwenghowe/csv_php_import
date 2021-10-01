<?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
?>

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
            $form1 = "<input type='text' name='matric[]' value='".$data1."' size='50'></input>";
            $form2 = "<input type='text' name='name[]' value='".$data2."' ></input>";
            $form3 = "<input type='text' name='email[]' value='".$data3."' size='40'></input>";
            $form4 = "<input type='text' name='group[]' value='".$data4."'></input>";
            return print $open.$init."<td>".$form1."</td><td>".$form2."</td><td>".$form3."</td><td>".$form4."</td>".$end;
        }
    }

    function printFormHeader() {
        return print "<form method='post' action=''><table border=1>";
    }

    function printFormFooter($count) {
        return print "</table>Total students in the list: ".$count."<br><br><input type='submit' name='save' value='save'></input></form><br>";
    }

    if (isset($_POST['submit'])) 
	{
        $ext = "";
        if(isset($_FILES)&$_FILES['filename']['name']!="") {
            $tmp = explode('.',$_FILES['filename']['name']);
		    $ext = end($tmp);
            echo "Uploaded file type: ".strtoupper($ext);
        }
        if($ext=='csv') {
            $handle = fopen($_FILES['filename']['tmp_name'], "r");
            $headers = fgetcsv($handle, 1000, ","); // remove header
            $student = 0;
            printFormHeader();
            printNameListRow("NO.",$headers[0],$headers[1],$headers[2],$headers[3],TRUE);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
            {
                printNameListRow($student+1,$data[0],$data[1],$data[2],$data[3]);
                $student++;
            }
            printFormFooter($student);
            fclose($handle);
        } 
        else if($ext="xlsx") {
            $spreadsheet = new Spreadsheet();

            $inputFileType = 'Xlsx';
            $inputFileName = $_FILES['filename']['tmp_name'];

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);

            $worksheetData = $reader->listWorksheetInfo($inputFileName);

            $data = array();
            foreach ($worksheetData as $worksheet) {
                $sheetName = $worksheet['worksheetName'];
                $reader->setLoadSheetsOnly($sheetName);
                $spreadsheet = $reader->load($inputFileName);

                $worksheet = $spreadsheet->getActiveSheet();
                $data = $worksheet->toArray();
            }
            printFormHeader();
            $student = -1;
            foreach ($data as $row) {
                if($student==-1) {
                    printNameListRow("No.",$row[0],$row[1],$row[2],$row[3],true);
                } else {
                    printNameListRow($student+1,$row[0],$row[1],$row[2],$row[3]);
                    
                }
                $student++;
            }      
            printFormFooter($student);
        }
        else if($ext=="") {
            echo "To use this app, please upload student list in CSV format!";
        } 
        else {
            echo "<br>Sorry, the current type of ".strtoupper($ext)." file is not supported at the moment! Please use CSV file only";
        }
    }
    ?>

</body>

</html>