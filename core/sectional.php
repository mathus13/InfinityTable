<?php
session_start();
function arrayPrint($array){
	foreach($array as $k=>$v){
		echo '<strong>'.$k.'</strong>: ';
		if(is_array($v)){
			echo 'Array(';
			echo '<blockquote>';
			arrayPrint($v);
			echo '</blockquote>)<br/>';
		}else{
			echo $v.'<br/>';
		}
	}
}
if(isset($_POST['function'])){
	$function = $_POST['function'];
	$type = array('doc'=>array('level 1','level 2','level 3','dispatch'),'sessions');
	$couch_dsn = "http://192.168.1.59:5984/";
	$couch_db = isset($_POST['db'])?$_POST['db']:"training";
	
	/**
	* include the library
	*/
	
	require_once "/var/www/core/couchOnPHP/couch.php";
	require_once "/var/www/core/couchOnPHP/couchClient.php";
	require_once "/var/www/core/couchOnPHP/couchDocument.php";
	
	/**
	* create the client
	*/
	$client = new couchClient($couch_dsn,$couch_db);
	
	try{
		switch($function){
			case 'getDocs':
				$output = $client->asArray()->getView('docs', 'docs_by_level');
				break;
			case 'getDoc':
				if(!isset($_POST['id'])){
					throw new Exception("No ID Sent To Request Document", 1);
				}
				$id = $_POST['id'];
				$output = $client->asArray()->getDoc($id);
				break;
			case 'addDoc':
				$doc = new stdClass();
				unset($_POST['function']);
				foreach($_POST as $k=>$v){
					$doc->$k = $v;
				}
				$output = $client->storeDoc($doc);
				break;
			case 'updateDoc':
				if(!isset($_POST['_id']) || !isset($_POST['_rev'])){
					throw new Exception("No ID Sent To Request Document", 1);
				}
				unset($_POST['function']);
				$doc = $client->getDoc($_POST['_id']);
				foreach($_POST as $k=>$v){
					if(strlen($v)>0){
						$doc->$k = $v;
					}
					
				}
				$output = $client->storeDoc($doc);
				break;
			case 'updateSkill':
				if(!isset($_POST['_id']) || !isset($_POST['_rev'])){
					throw new Exception("No ID Sent To Request Document", 1);
				}
				unset($_POST['function']);
				$doc = $client->getDoc($_POST['_id']);
				$doc->$_POST['field'] = array('time'=>$_POST['value'],'trainer'=>$_SESSION['name'],'date'=>date('Y-m-d H:i'));
				$output = $client->storeDoc($doc);
				break;
			case 'deleteDoc':
				if(!isset($_POST['id']) || !isset($_POST['rev_id'])){
					throw new Exception("No ID Sent To Request Document", 1);
				}
				$doc = new stdClass();
				$doc->_id = $_POST['id'];
				$doc->_rev = $_POST['rev_id'];
				$output = $client->deleteDoc($doc);
				break;
			case 'closeSession':
				if(!isset($_POST['_id']) || !isset($_POST['_rev'])){
					throw new Exception("No ID Sent To Request Document", 1);
				}
				unset($_POST['function']);
				$doc = $client->getDoc($_POST['_id']);
				$doc->closed = date('Y-m-d H:i');
				$doc->status = 'closed';
				$output = $client->storeDoc($doc);
				if($output->ok == true){
					$output = $client->asArray()->getDoc($_POST['_id']);
				}else{
					throw new Exception("error closing document", 1);
				}
				$body = '<h3>Training Report for '.$output['trainee'].'</h3><table>';
				$trainers = array();
				foreach($output as $k=>$v):
					if(is_array($v)){
						if(isset($v['trainer']) && isset($trainers[$v['trainer']])){
							//echo $trainers[$v['trainer']].' + '.$v['time'].'<br/>';
							$trainers[$v['trainer']] += $v['time'];
						}elseif(isset($v['trainer'])){
							$trainers[$v['trainer']] = $v['time'];
							//echo $trainers[$v['trainer']].' = '.$v['time'].'<br/>';
						}
						$body .= '<tr><td><span style="font-size:1.2em;font-weight:7;">'.$k.'</span></td><td>'.$v['time'].' minutes with '.$v['trainer'].'.<br/>
							Completed '.date('m/d/y H:i',strtotime($v['date'])).'</td></tr>';
					}else{
						$body .= '<tr><td><span style="font-size:1.2em;font-weight:7;">'.$k.'</span></td><td>'.$v.'</td></tr>';
					}
					
				endforeach;
				$body .= '<tr><th>Trainer</th><th>Minutes Training</th></tr>';
				foreach($trainers as $k=>$v){
					$body .= '<tr><td>'.$k.'</td><td>'.$v.'</td></tr>';
				}
				$body .='</table>';
				$recipient ='sbarratt@bpeinc.com';
				$subject = "Training Report ".$output['trainee']." -> Level ".$output['level'];
				$mailheaders = "From: tsr@bpeinc.com\n";
				$mailheaders .= "MIME-Version: 1.0\n";
				$mailheaders .= "Content-type: text/html; charset=iso-8859-1";
				if (mail($recipient, $subject, $body, $mailheaders)) {
				 $output = 'Congratulations, This training has been completed'; 
				} else {
				 $output = '<p>The form has failed. Please use IM or PInnacle Email</p>';  
				}
				break;
			case 'getSessions':
				$output = $client->asArray()->getView('session', 'sessions_by_trainer');
				break;
			case 'order':
				unset($_POST['function']);
				//arrayPrint($_POST);
				foreach($_POST['skill'] as $k=>$v){
					$doc = $client->getDoc($v);
					$doc->order=$k;
					$output[]=$client->storeDoc($doc);
				}
				break;
		}
		
	}catch (Exception $e) {
		var_dump($e);
		exit(1);
	}
	if(is_string($var)){
		echo stripslashes(nl2br($output));
	}else{
		echo json_encode($output);
	}
	
}
?>