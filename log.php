<?php
	require_once "credentials.php";
	require_once "InterfaceMonitor.php";
	
	function logBandwidthData($host,$community){
		$interface = new InterfaceMonitor(new SNMPMonitor($host,$community));
		$db = new mysqli('localhost','root','') or die("Unable to connect to database");
		$db->select_db('mikrotik');
		
		$interface->getPortList();
		$interface->getPortBytesInputList();
		$interface->getPortBytesOutputList();
		for($count=0;$count < count($interface->portList);$count++)
		{
			$name = $interface->portList[$count];
			//$prevReq = $db->query("SELECT data_in from bandwidth where port=$name order by `time` DESC");
			//$last = $prevReq->fetch_row();
			$data_out = $interface->portBytesOutputList[$count];
			$data_in = $interface->portBytesInputList[$count];
			$portname = $interface->portList[$count];
			$time = date('H:i:s');
			$result = $date = date('Y-m-d');

			//Get the Delta in Data Usage
			$last_in ='';$last_out='';
			$last_sql = "SELECT * FROM `bandwidth` WHERE `host`='$host' and `port`='$portname' ORDER BY time DESC LIMIT 1";
			$res = $db->query($last_sql);
			$bandwidth = $res->fetch_assoc();

			if($bandwidth == ''){
				$last_in = 0; $last_out = 0;
			}
			else{
				$last_in = $bandwidth['data_in']; $last_out = $bandwidth['data_out'];
			}

			$data_in = abs($data_in - $last_in); $data_out = abs($data_out - $last_out);
			//

			$prepString = "INSERT INTO bandwidth(`host`,`data_in`,`data_out`,`time`,`date`,`port`) VALUES(".$host.",".$data_in.",".$data_out.",".$time.",".$date.",".$portname.")";
			$db->query("INSERT INTO bandwidth(`host`,`data_in`,`data_out`,`time`,`date`,`port`) VALUES('$host','$data_in','$data_out','$time','$date','$portname')");
			$db->commit();
		}
	}
	//echo "In Log.pHp : argv1 : ".$argv[1]."  "."argv[2] : ".$argv[2]; 
	logBandwidthData($argv[1],$argv[2]);
?>