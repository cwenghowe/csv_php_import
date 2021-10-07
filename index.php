<?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    if(isset($_POST['save'])) {
        print_r($_POST['name']);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"></link>
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css"></link>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css"></link>
    <title>Demo import CSV/Excel</title>
</head>

<body>
<?php
    if(isset($_POST['save'])) {
        echo "information saved!";
    }
?>
<form>
<table id="example2" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Matric</th>
                <th>Email</th>
                <th>Group</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><input type="text" name='name[]' value='Lim Ai Jia'></input></td>
                <td><input type="text" name='matric[]' value='A012'></input></td>
                <td><input type="text" name='email[]' value='A012@graduate.utm.my'></input></td>
                <td><input type="text" name='group[]' value='1'></input></td>
            </tr>
            <tr>
                <td>2</td>
                <td>FAISAL BIN RESTU</td>
                <td>A013</td>
                <td>A013@graduate.utm.my</td>
                <td>1</td>
            </tr>
            <tr>
                <td>3</td>
                <td>MUHAMMAD MAXLAN BIN ANUAR</td>
                <td>A014</td>
                <td>A014@graduate.utm.my</td>
                <td>1</td>
            </tr>
            <tr>
                <td>4</td>
                <td>MUHAMAD ZAMIE BIN MOHD HELME</td>
                <td>A015</td>
                <td>A015@graduate.utm.my</td>
                <td>1</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Matric</th>
                <th>Email</th>
                <th>Group</th>
            </tr>
        </tfoot>
    </table>
</form>
    <form enctype='multipart/form-data' action='' method='post'>
    
        <label>Upload Product CSV file Here</label>
        <br>
        <input size='100' type='file' name='filename'>
        </br>
        <input type='submit' name='submit' value='Upload Products'>

    </form>

    <?php

    function printNameListRow($i, $data, $header=FALSE) {
        if($header){
            return print "<thead><th>".$i."</th><th>".$data[0]."</th><th>".$data[1]."</th><th>".$data[2]."</th><th>".$data[3]."</th></thead>";
        } else {
            $open = "<tr>";
            $end  = "</tr>";
            $init  = "<td>".$i."</td>";
            $form1 = "<input type='text' name='name[]' value='".$data[0]."' size='50'></input>";
            $form2 = "<input type='text' name='matric[]' value='".$data[1]."' ></input>";
            $form3 = "<input type='text' name='email[]' value='".$data[2]."' size='40'></input>";
            $form4 = "<input type='text' name='group[]' value='".$data[3]."'></input>";
            // $form1 = $data[0];
            // $form2 = $data[1];
            // $form3 = $data[2];
            // $form4 = $data[3];
            return print $open.$init."<td>".$form1."</td><td>".$form2."</td><td>".$form3."</td><td>".$form4."</td>".$end;
        }
    }

    function printFormHeader() {
        $str = "<form method='post' action=''>";
        $str = $str."<table border=1 id='example' class='display nowrap' style='width:100%'>";
        return print $str;
    }

    function printFormFooter($count) {
        $str = "</table>";
        $str = $str."Total students in the list: ".$count."<br><br><input type='submit' name='save' value='save'></input></form><br>"; 
        return print $str;
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
            printNameListRow("No.",$headers,TRUE);
            print "<tbody>";
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
            {
                // printNameListRow($student+1,$data[0],$data[1],$data[2],$data[3]);
                printNameListRow($student+1, $data);
                $student++;
            }
            print "</tbody>";
            printFormFooter($student);
            fclose($handle);
        } 
        else if($ext="xlsx" || $ext='xls') {
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
                    // printNameListRow("No.",$row[0],$row[1],$row[2],$row[3],true);
                    printNameListRow("No.",$row,true);
                    print "<tbody>";
                } else {
                    // printNameListRow($student+1,$row[0],$row[1],$row[2],$row[3]);
                    printNameListRow($student+1,$row);
                    
                }
                $student++;
            }      
            print "</tbody>";
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

<script>
$(document).ready(function() {
    var table = $('#example').DataTable( {
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    } );
} );

$(document).ready(function() {
    var table = $('#example2').DataTable( {
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    } );
} );
</script>
</body>


</html>