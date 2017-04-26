<?php 
	class connect{
		private $dbhost = "";
		private $dbuser = "";
		private $dbpass = "";
		private $dbname = "";

		public function startConn(){
			$this->DBcon = null;
			try{
				$this->DBcon = new PDO("mysql:host=".$this->dbhost.";dbname=".$this->dbname, $this->dbuser, $this->dbpass);
			}catch(Exception $e){
				echo "error connecting:";
			}
			return $this->DBcon;
		}
	} 
?>
