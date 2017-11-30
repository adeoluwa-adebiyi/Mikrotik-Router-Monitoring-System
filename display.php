<?php
	require_once "InterfaceMonitor.php";
	$host = "192.168.2.234";

	$interface = new InterfaceMonitor(new SNMPMonitor($host,"ITNH_REMOTE"));
	for(;;){
		$interface->getPortBytesInputList();
		$interface->getPortbytesOutputList();
		$data_in = $interface->portBytesInputList[1];
		$data_out = $interface->portBytesOutputList[2];

		echo "Data In : ".$data_in."\n";
		echo "Data Out : ".$data_out."\n";
	}
?>