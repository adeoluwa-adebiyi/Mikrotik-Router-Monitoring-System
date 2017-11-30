<?php
	class Utility{
		public function cleanStringApostrophe($string){
			return str_replace("\"","",$string);
		}
	}
?>