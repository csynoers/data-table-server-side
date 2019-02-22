<?php
    // create connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "datatables";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
    // end create connection
    
    $records_total = 1000;

    $query= "SELECT * FROM tes";
    $query .= $_POST['length'] != -1 ? $_POST['start'].','.$_POST['length'] : null;
    $statement = $conn->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $records_filtered = $statement->rowCount();

    // create variabel for save data from push with foreach
    $data= []; 
    // ceate number
    $no = $_POST['start'];
    
    foreach($result as $row)
    {
        $data[] = [
            $no,
            $row['first_name'],
            $row['last_name'],
            'Action'
        ];
        $no++;
    }

    $output = array(
        // "draw"				=>	intval($_POST["draw"]),
        "draw"				=>	$_POST["draw"],
        "recordsTotal"		=> 	$records_filtered,
        "recordsFiltered"	=>	$records_filtered,
        "data"				=>	$data,
        "console_log"       => $result
    );
    echo json_encode($output);
    // print_r($_REQUEST);