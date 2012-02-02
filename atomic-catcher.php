<?php
// connect to database
mysql_connect('localhost', 'databank', 'databankpr');
mysql_select_db('databank');

if(isset($_POST['action'])){
	$action = $_POST['action'];
}elseif(isset($_GET['action'])){
	$action = $_GET['action'];
}

switch($action){

	case 'upload':
		define ("FILEREPOSITORY","atom_snaps/");

		if (isset($_FILES['media'])) {
			
			if (is_uploaded_file($_FILES['media']['tmp_name'])) {
				
				// create dir if not available
				if (! is_dir(FILEREPOSITORY)) {
					mkdir(FILEREPOSITORY);
				}

				// get file extension
				$extension = end(explode(".", strtolower($_FILES['media']['name'])));

				// create a random filename
				$name = md5(time());

				// filename
				$filename = $name.".".$extension;

				// try to move temp file
				$result1 = move_uploaded_file($_FILES['media']['tmp_name'], FILEREPOSITORY.$filename);

				// store in database
				$result2 = mysql_query("INSERT INTO `atoms` 
										SET 
											`img` = '$filename', 
											`lat` = '".$_POST['latitude']."', 
											`lng` = '".$_POST['longitude']."',
											`status` = 'NEW'
										");

									// respond
									if ($result1 && $result2) 
										echo "File successfully uploaded.";
									else 
										echo "There was a problem uploading the file.";
			}

		}

		break;
	
	case 'fetch_categories':
		$result = mysql_query("SELECT `id`, `category` FROM `categories` WHERE `category` LIKE '%".$_GET['term']."%'");
		if(mysql_num_rows($result)>0){
			while($row = mysql_fetch_array($result)){
				$response[] = array( 'id' => $row['id'], 'label' => $row['category'], 'value' => $row['category']);
			}
			echo json_encode($response);
		}

		break;
}

?>