<?php 
if(isset($_POST['type']) && is_string($_POST['type'])){
	include('/var/www/sql-crud.php');
	$sql = new Database;
	$sql->connect();
	$type = $_POST['type'];
	$search = isset($_POST['search']) && is_string($_POST['search'])?$_POST['search']:'';
	$result = array('error'=>'','message'=>'','items'=>'');
	switch($type){
		case 'agent':
			if(!$sql->select('log',"first_name,last_name,MATCH(first_name,last_name) AGAINST ('".$search."') AS score","first_name LIKE '%$search%' OR last_name LIKE '%$search%'")){
				$result['error'] = 'Selecttion Error. Please try again';
				$result['message']=$sql->error;
			}else{
				if($sql->numResults == 0){
					$query = $sql->getQuery();
					$result['error'] = 'No Results';
					$result['message'] = $query;
				}elseif($sql->numResults==1){
					$result['items'][0] = $sql->getResult();
				}else{
					$result['items'] = $sql->getResult();
				}
				
			}
			break;
			
	}
}else{
	$result['error'] = 'No Search type Specified';
}
//var_dump($result);
echo json_encode($result);
