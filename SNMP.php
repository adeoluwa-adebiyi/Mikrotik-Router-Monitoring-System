<?php
	include("Utility.php");

	class SNMPMonitor{
		private  $community;
		private  $host;
		private  $oid;
		private $utility;

		public function __construct($idhost,$idcommunity){
			$utility = new Utility;
			$this->host = $idhost; $this->community = $idcommunity;
			try{
				snmp_set_quick_print(TRUE);
			}
			catch(Exception $exception){
				echo $exception->getMessage();
			}
		}

		public function setHost($idhost){
			$this->host = $idhost;
		} 

		public function getHost(){
			return $this->host;
		}

		public function setCommunity($idcommunity){
			$this->community = $idcommunity;
		}

		public function setOID($id_oid){
			$this->oid = $id_oid;
		}

		public function getRouterProperty($id_oid){
			$this->setOID($id_oid);
			return snmpwalk($this->host,$this->community,$this->oid);
		}
	};
?>