<?php
	include "SNMP.php";
	class PhysicalCondition{
		private $conn;
		private $danger;
		/** Unsure **/
		private $cpuTemperatureFarenheit = '1.3.6.1.4.1.14988.1.1.3.10.0';
		private $boardVoltageVolts = '1.3.6.1.4.1.14988.1.1.3.8.0';
		private $boardPowerValueWatts = '1.3.6.1.4.1.14988.1.1.3.12.0';
		private $boardCurrentValuemA = '1.3.6.1.4.1.14988.1.1.3.13.0';
		private $processorFrequencyMHz = '1.3.6.1.4.1.14988.1.1.3.14.0';
		private $usedMemory = '1.3.6.1.2.1.25.2.3.1.6.65536';

		public function __construct($SNMP_Connector){
			$this->conn = $SNMP_Connector;
		}

		public function getCPUTemperature(){
			return $this->conn->getRouterProperty($this->cpuTemperatureFarenheit);
		}
		public function getBoardVoltageVolts(){
			return $this->conn->getRouterProperty($this->boardVoltageVolts);
		}
		public function getBoardPowerValue(){
			return $this->conn->getRouterProperty($this->boardPowerValueWatts);
		}
		public function getBoardCurrentValue(){
			return $this->conn->getRouterProperty($this->boardCurrentValuemA);
		}
		public function getProcessorFrequency(){
			return $this->conn->getRouterProperty($this->processorFrequencyMHz);
		}
		public function getUsedMemory(){
			return $this->conn->getRouterProperty($this->usedMemory);
		}

	}

?>


