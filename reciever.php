<?php
 ini_set('display_errors', '1');


 $hostname = "localhost";
 $username = "boincadm";
 $password = "cluster";
 $database = "gasad";
 
 mysql_connect($hostname, $username, $password) OR DIE("Connection to database error");
 mysql_select_db($database);
 
// $msg = $_FILES['message'];
/*****************************/

function get_userId_by_code($code) {
 	$query = "SELECT * FROM UserOfPickUpPoint WHERE id_code='".$code."';";
// echo $query;
 
 	$result = mysql_query($query);

 	if (!$result) {
    	die('Can\'t get user_id: ' . mysql_error());
	}

 	if(!mysql_num_rows($result)) {
 		//echo "\n get_userId_by_code: empty result";
 		return null;
 		//die();
 	}

 	else {
 		$row = mysql_fetch_array($result);
// echo "Client id is".$row['id'];
        //var_dump($row);
        return $row[0];
    } 	
}


function get_data_from_table($table_name) {
 	$query = "SELECT * FROM ".$table_name.";";
// echo $query;
 
 	$result = mysql_query($query);

 	if (!$result) {
    	die('Can\'t get data from table: ' . mysql_error());
	}

 	if(!mysql_num_rows($result)) {
 		echo "\n get_data_from_table: empty table $table_name";
 		return null;
 		//die();
 	}

 	else {
 		//$row = mysql_fetch_array($result);
// echo "Client id is".$row['id'];
 		$info = array();

        while($row=mysql_fetch_assoc($result)){
			$info[] = $row;
			}

        return $info;
    } 	
}


function get_pickUpPoint_info_by_userId($userId) {

	//$query = "SELECT PickUpPoint.name, PickUpPoint.address FROM PickUpPoint, User_PickUpPoint, UserOfPickUpPoint WHERE UserOfPickUpPoint.id='".$userId."'"." AND UserOfPickUpPoint.id=User_PickUpPoint.id_user AND User_PickUpPoint.id_pickuppoint=PickUpPoint.id;"; //
	$query = "SELECT PickUpPoint.name, PickUpPoint.city, PickUpPoint.street FROM PickUpPoint, User_PickUpPoint, UserOfPickUpPoint WHERE UserOfPickUpPoint.id='".$userId."' AND UserOfPickUpPoint.id=User_PickUpPoint.id_user AND User_PickUpPoint.id_pickuppoint=PickUpPoint.id;";

 //echo $query;
 	//$query = "SELECT * FROM User_PickUpPoint"; //WHERE id_user='".$userId."';"; //
 	//echo $query;

 	$result = mysql_query($query);

 	if (!$result) {
    	die('Can\'t get data from table: ' . mysql_error());
	}

 	if(!mysql_num_rows($result)) {
 		echo "\n get_pickUpPoint_info_by_userId: empty result";
 		return null;
 		//die();
 	}

 	else {
 		$user_info = array();

        while($row=mysql_fetch_assoc($result)){
			$user_info[] = $row;
			}

        return $user_info;
    } 		
}

function add_json_element_in_array($response_array, $json_object_tag, $json_object) {

	$response_array["$json_object_tag"] = "$json_object";
	return $response_array;
}
 



//echo json_encode($settings_array);
?>





