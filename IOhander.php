<?php 
	function autoinclude($class){
		include($class . ".php");
	}
	autoinclude("dbconfig");
	class IOhandler{	
		new connect;
		public function __construct(){
	 		include('dbconfig.php');
	 		$db = new connect();
        	$this->DBcon = $db->startConn();
		}
		public function getAll($table){
			$SQL = "SELECT * from $table";
			$q = $this->DBcon->query($SQL) or die("Failed");
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $r;
			}
			return $data;
		}
		public function getBy_id($id, $table){
			$SQL = "SELECT * from $table where _id = :id";
			$q = $this->DBcon->prepare($SQL);
			$q->execute(array(':id' => $id));
			$data = $q->FETCH_ASSOC();
			return $data;
		}
		public function insert($table, array $fields, array $values) {
		    $numFields = count($fields);
		    $numValues = count($values);
		    if($numFields === 0 or $numValues === 0)
		        throw new Exception("At least one field and value is required.");
		    if($numFields !== $numValues)
		        throw new Exception("Mismatched number of field and value arguments.");

		    $fields = '`' . implode('`,`', $fields) . '`';
		    $values = "'" . implode("','", $values) . "'";
		    $sql = "INSERT INTO {$table} ($fields) VALUES($values)";
			
			if ($q=$this->DBcon->prepare ( $sql )) {
		       // echo json_encode($sql);
		        if ($q->execute()) {
		            return true;
		        }
		    }
		    return false;
		}
		public function update($table,$values=array(),$where){
            $args=array();
			foreach($values as $field=>$value){
				$args[]=$field.'="'.$value.'"';
			}
			$spilt = implode(',',$args);
			$sql='UPDATE '.$table.' SET '.$spilt.' WHERE '.$where;
   			if($q=$this->DBcon->prepare($sql)){
   				if ($q->execute()) {
   					return true;
   				}
   			}
   			return false;
    	}
		public function deleteData($id, $table){
			$SQL = "DELETE from $table where _id = :id";
			$q = $this->DBcon->prepare($SQL);
			$q->execute(array(':id' => $id));
			return true;
		}
		public function startSession(){
			if (!isset($_SESSION['id'])) {
				session_start();
			}
			if (isset($_SESSION['id'])) {
				$sessid = $_SESSION['id'];
			}	
		}
		public function endSession(){
			if(!isset($_SESSION['id'])){
				session_start();
			}
	    	if(isset($_SESSION['id'])){
	    		session_destroy();  
			}
	    }
	    public function sendMail($values = array()){
	    	$values = '`' . implode ( '`,`', $values ) . '`';
		    $mail_status = mail($values);
		    if ($mail_status) { 
		        return true;    
		    }else{
		        return false;
		    }
	    }
	    public function checkTableExist($table){
	    	$sql = "'SHOW TABLES FROM '.$this->dbname.' LIKE '.$table.''";
	    	if($sql){
	        	if(mysql_num_rows($sql)==1){
	                return true;
	            }else{
	                return false;
	            }
	        }
	    }
	    public function validateInput($input){
			$input=preg_replace("#[^0-9a-z]#i","",$input);
	    }
	    
	    public function login($username, $password, $table, $dbtable, $orderparams){
		    $query = $DBcon->prepare("SELECT * FROM $table WHERE $dbtable='$username' ORDER BY $orderparams DESC limit 1");
		    $vee=$query->execute();
		    $row=$vee->fetch_array();
		    $count=$query->num_rows;
		    $getpw = $row['password'];
		    $verify = password_verify($password, $getpw);
		    if(($count)){
		        if ($verify) {
		           $_SESSION['userid'] = $row['_userid'];
		            echo "ok";
		        } else {
		            echo "incorrect password";
		        }
		    } else {
		         echo  "email not exist try logging in with your username";
		    }
			$DBcon->close();
		}
		public function GetClientMac(){
		    $macAddr=false;
		    $arp=`arp -n`;
		    $lines=explode("\n", $arp);

		    foreach($lines as $line){
		        $cols=preg_split('/\s+/', trim($line));

		        if ($cols[0]==$_SERVER['REMOTE_ADDR']){
		            $macAddr=$cols[2];
		        }
		    }
		    return $macAddr;
		}
		public function my_url(){
		    $url = (!empty($_SERVER['HTTPS'])) ?
		               "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] :
		               "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		    echo $url;
		}
		public function Search($queried){
			//where queried is the form name gotten from your class use
			$keys = explode(" ",$queried);
			$sql = "SELECT * FROM links WHERE name LIKE '%$queried%' ";
			foreach($keys as $k){
			    $sql .= " OR name LIKE '%$k%' ";
			}
			$result = mysql_query($sql);
			return $result;
		}
		public function uploadImg($table){
			$upload_image=$_FILES[" myimage "][ "name" ];
			$folder="images/";
			move_uploaded_file($_FILES[" myimage "][" tmp_name "], "$folder".$_FILES[" myimage "][" name "]);
			$insert_path="INSERT INTO $table VALUES($folder,$upload_image)";
			$var=mysql_query($inser_path);
		}
		public function check_match($param1, $param2){
			if($param1 == $param2)
				return true;
			else
				return false;
		}
		private function trow($message){
			echo json_encode($message);
		}
		public function push($code, $message){
			$response['status'] = $code;
			$response['message'] = $message;
			trow($response);
		}

		public function random_char($char){
			// where char stands for the string u want to randomize
			$char_length = 15;
			$cl = strlen($char);
			$randstr = '';
			for($i = 0; $ < $char_length; $i++ ){
				$randomize .= $char[rand(0, $cl - 1)]; 
			}
			return $randomize;
		}
		public function random_string($length, $ranges = array('0-9', 'a-z', 'A-Z')) {
			/*usage for randomize all small letters
				random_string($l, array('a-z'))
			*/
	        foreach ($ranges as $r) $s .= implode(range(array_shift($r = explode('-', $r)), $r[1]));
	        while (strlen($s) < $length) $s .= $s;
	        return substr(str_shuffle($s), 0, $length);
	    }
	}		
?>
