<?php

 
$servername = "localhost";
$username = "root";
$password = "vendershop";
$dbname = "dtables";
 
    
  $conn = new mysqli($servername, $username, $password, $dbname);
 
	if ($conn->connect_error) {

	    die("Connection failed: " . $conn->connect_error);
	    
	} 

     
 
  
    $draw = $_POST["draw"];
    $orderByColumnIndex  = $_POST['order'][0]['column'];
    $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];
    $orderType = $_POST['order'][0]['dir']; 
    $start  = $_POST["start"];
    $length = $_POST['length'];
    $data = [];



	$sql = "SELECT * FROM users";
	
	$result = $conn->query($sql);


     if(!empty($_POST['search']['value']) && $_POST['search']['value']!==''){



	  if ($statement = $conn -> prepare("SELECT * FROM users WHERE name LIKE ?  LIMIT ?,?")){

	  		$search = '%'.$_POST['search']['value'].'%';

	  		//sii = string|integer|integer -> type of data types 
	    	$statement->bind_param('sii',$search,$_POST['start'], $_POST['length']);

	        $statement -> execute();

	        $data_records = $statement -> get_result();
	    }
			

	    while($row = $data_records->fetch_assoc()) {


	    	array_push($data, 
	    		['id'=>		$row['id'],
	    		'name'=>	htmlentities($row['name']),
	    		'phone'=>	$row['phone'],
	    		'email'=>	$row['email'],
	    		'country'=>	htmlentities($row['country']),
	    		'zip'=>		$row['zip']
	    	]
	    );

	    }

	    $response = array(
	        "draw" => intval($draw),
	        "recordsTotal" =>$result->num_rows,
	        "recordsFiltered" =>count($data),
	        "data" => $data,
	       
	    );



     }else{



	  if ($statement = $conn -> prepare("SELECT * FROM users  LIMIT ?,?")){

	    	$statement->bind_param('ii', $_POST['start'], $_POST['length']);
	        $statement -> execute();
	        $data_records = $statement -> get_result();
	    }
			

	    while($row = $data_records->fetch_assoc()) {


	    	array_push($data, 
	    		['id'=>		$row['id'],
	    		'name'=>	htmlentities($row['name']),
	    		'phone'=>	$row['phone'],
	    		'email'=>	$row['email'],
	    		'country'=>	htmlentities($row['country']),
	    		'zip'=>		$row['zip']
	    	]
	    );

	    }

	    $response = array(
	        "draw" => intval($draw),
	        "recordsTotal" =>$result->num_rows,
	        "recordsFiltered" =>$result->num_rows,
	        "data" => $data,
	       
	    );

     }


   
	 echo json_encode($response);

?>