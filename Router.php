<?php
	class Router{
		private $host = '';
		private $community = '';
		public function __construct($host,$community){
			$this->host = $host; $this->community = $community;
		}

		public function getHost(){
			return $this->host;
		}

		public function getCommunity(){
			return $this->community;
		}
	}
?>