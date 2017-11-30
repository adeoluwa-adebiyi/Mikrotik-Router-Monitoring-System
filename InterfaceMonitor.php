<?php
	//error_reporting(FALSE);
	require_once "SNMP.php";
	require_once "credentials.php";
	class InterfaceMonitor{
		//Utitlity Object
		private $utility ='';

		//Unit Bits per Second
		private $units = 'bps';

		//Object Identification Number
		public $portListID='1.3.6.1.2.1.2.2.1.2';
		public $macAddressListID='1.3.6.1.2.1.2.2.1.6';
		public $portActiveListID='1.3.6.1.2.1.2.2.1.8';
		public $portBytesInputListID='1.3.6.1.2.1.31.1.1.1.6';
		public $portPacketsInputListID='1.3.6.1.2.1.31.1.1.1.7';
		public $portBytesOutputListID='1.3.6.1.2.1.31.1.1.1.10';
		public $portPacketsOutputListID='1.3.6.1.2.1.31.1.1.1.11';
		public $portErrorsInputID='1.3.6.1.2.1.2.2.1.14';
		public $portErrorsOutputID='1.3.6.1.2.1.2.2.1.20';


		public $portList='';
		public $macAddressList='';
		public $portActiveList='';
		public $portBytesInputList='';
		public $portPacketsInputList='';
		public $portBytesOutputList='';
		public $portPacketsOutputList='';
		public $portErrorsInput='';
		public $portErrorsOutput='';

		private $conn = '';
		
		public function __construct($SNMP_Connector){
			$this->utility = new Utility();
			$this->conn = $SNMP_Connector;
		}

		//Retrieve Information Concerning Interfaces on the Routers
		public function getPortList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->portListID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,$this->utility->cleanStringApostrophe($value));
			}
			$this->portList=$processedList;
		}

		//Get Interface ports MAC Addresses
		public function getMACAddressList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->macAddressListID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,bin2hex($this->utility->cleanStringApostrophe($value)));
			}
			$this->macAddressList=$processedList;
			
		}

		//Get Active Ports
		public function getPortActiveList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->portActiveListID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,$this->utility->cleanStringApostrophe($value));
			}
			$this->portActiveList=$processedList;
		}

		//Get Input Internet Speed into ports
		public function getPortBytesInputList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->portBytesInputListID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,$this->utility->cleanStringApostrophe($value));
			}
			$this->portBytesInputList=$processedList;
		}

		//Get Total Size of Packet Sent from Users
		public function getPortPacketsInputList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->portBytesInputListID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,$this->utility->cleanStringApostrophe($value));
			}
			$this->portPacketsInputList=$processedList;
		}

		//
		public function getPortBytesOutputList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->portBytesOutputListID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,$this->utility->cleanStringApostrophe($value));
			}
			$this->portBytesOutputList=$processedList;
		}

		public function getPortPacketsOutputList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->portPacketsOutputListID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,$this->utility->cleanStringApostrophe($value));
			}
			$this->portPacketsOutputList=$processedList;
		}

		public function getPortErrorsInputList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->portErrorsInputID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,$this->utility->cleanStringApostrophe($value));
			}
			$this->portErrorsInput=$processedList;
		}

		public function getPortErrorsOutputList(){
			$processedList = array();
			$list = $this->conn->getRouterProperty($this->portErrorsOutputID);
			foreach ($list as $key => $value) {
				# code...
				array_push($processedList,$this->utility->cleanStringApostrophe($value));
			}
			$this->portErrorsOutput=$processedList;
		}

		public function activeInterfacesTable(){
			//Set the portList attribute
			$this->getPortList();

			//Set the activeList attribute
			$this->getPortActiveList();

			//Set the MAC addtressList 
			$this->getMACAddressList();
			$panel = "<div class='panel panel-mod-black'>
						<div class='panel-heading'>
							<div class='panel-title'>Ports</div>
						</div>
						<table class='table table-striped'>
							<thead><th>#</th><th>Port</th><th>MAC address</th><th>Status</th></thead>
							<tbody>
								";
			for($counter=0;$counter < count($this->portList);$counter++){
				$id = $counter +1;
				$status="<span class='active'>active</span>";
				if(strcmp($this->portActiveList[$counter],"2") == 0){
					$status = "<span class='inactive'>inactive</span>";
				}
				$panel .= "<tr>"."<td>".$id."</td>"."<td>".$this->portList[$counter]."</td>"."<td>".$this->macAddressList[$counter]."</td>"."<td>".$status."</td>"."<tr>";
			}
			echo $panel."</tbody>"."</table>"."</div>";
		}

		public function errorAndPacketTable(){
			//Set the portList attribute
			$this->getPortList();

			$this->getPortPacketsOutputList();

			$this->getPortPacketsInputList();

			$this->getPortErrorsOutputList();

			$this->getPortErrorsInputList();

			$panel = "<div class='panel panel-mod-black'>
						<div class='panel-heading'>
							<div class='panel-title'>Packets Information</div>
						</div>
						<table class='table table-striped'>
							<thead><th>#</th><th>Port</th><th>Packets Out</th><th>Packets In</th><th>Packets Error Out</th><th>Packets Error In</th></thead>
							<tbody>
								";
			for($counter=0;$counter < count($this->portList);$counter++){
				$id = $counter +1;
				$status="<span class='active'>active</span>";
				if(strcmp($this->portActiveList[$counter],"2") == 0){
					$status = "<span class='inactive'>inactive</span>";
				}
				$panel .= "<tr>"."<td>".$id."</td>"."<td>".$this->portList[$counter]."</td>"."<td>".$this->portPacketsOutputList[$counter]."</td>"."<td>".$this->portPacketsInputList[$counter]."</td>"."<td>".$this->portErrorsOutput[$counter]."</td>"."<td>".$this->portErrorsInput[$counter]."</td>"."<tr>";
			}
			echo $panel."</tbody>"."</table>"."</div>";
		}
	}
?>
