<?php 
include('config.php');
	
	class IOhandler{	
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

		public function insert($table, $fields = array(), $values = array()) {
		    $fields = '`' . implode ( '`,`', $fields ) . '`';
		    $values = "'" . implode ( "','", $values ) . "'";
		    $sql = "INSERT INTO {$table} ($fields) VALUES($values)";

		    if ($q=$this->DBcon->prepare ( $sql )) {
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
	}	

	
?>