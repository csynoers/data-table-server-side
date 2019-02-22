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
    
    $records_total  = 1000;

    # default query
    $query          = "SELECT * FROM tes WHERE 1=1 ";

    # set column field database for datatable orderable
    $column_order   = [
        'id',
        'first_name',
        'last_name',
    ];
    
    # set column field database for datatable searchable 
    $column_search  = [
        'first_name',
        'last_name',
    ]; 
	
    # generate like condition for search
    $like           = '';
    $i              = 0;
    foreach ($column_search as $item)
    { # loop column
        if ($_POST['search']['value']) { # if datatable send POST for search
            $like .= $i==0 ? ' AND (' : null ;
            $like .= $item.' LIKE \'%'.str_replace("'", "\'", $_POST['search']['value']).'%\'';
            $like .= count($column_search) - 1 == $i ? ') ' : ' OR ';
        }else{ # if datatable not send POST for search
            $like .='';
        }
        $i++;
    }
    $query .= $like;
    # end generate like condition for search

    # sorting
    $query .= isset($_POST["order"])? 'ORDER BY '.$column_order[$_POST['order'][0]['column']].' '.$_POST['order']['0']['dir'] : 'ORDER BY id '; 
    # end sorting

    # limit
    $limit = $_POST['length'] != -1 ? $_POST['start'].','.$_POST['length'] : null;
    # end limit

    # collect data from tables (PDO)
    $statement = $conn->prepare("$query LIMIT $limit" );
    $statement->execute();
    $result = $statement->fetchAll();
    $records_filtered = $statement->rowCount();

    # create variabel for save data from push with foreach
    $data= []; 
    # ceate number
    $no = ($_REQUEST["start"]==0) ? 1 : $_REQUEST["start"] ;
    
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
        "recordsTotal"		=> 	$records_total,
        "recordsFiltered"	=>	$records_total,
        "data"				=>	$data,
        "console_log"       => $query
    );
    echo json_encode($output);