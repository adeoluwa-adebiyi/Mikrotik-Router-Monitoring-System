<?php
	require_once "credentials.php";
	require_once "Router.php";
	$db = new db();

	function getProbedRouterList(){
		$db = new db();
		$hostList = array();
		$prepString = "SELECT host from surveillance";
		$conn = new mysqli($db->db_host,$db->db_user,$db->db_pwd,$db->db_name);
		$result = $conn->query($prepString);
		if (is_null($result)) {
			echo "Result is empty";
		}
		while(($info = $result->fetch_assoc()) != NULL){
			array_push($hostList,$info['host']);
		}
		return $hostList;
	}

	function getProbedRouters($list){
		$db = new db();
		$routerList = array();
		$conn = new mysqli($db->db_host,$db->db_user,$db->db_pwd,$db->db_name);
		for($count = 0;$count < count($list);$count++) {
			$ip = $list[$count];
			//echo "IP => ".ip2long($list[$count]);
			//echo $list[$count];
			$prepString = "SELECT community from router where ip=INET_ATON('$ip')";
			$result = $conn->query($prepString);
			$info = $result->fetch_assoc();
			echo "Community ==> ".$info['community'];
			array_push($routerList, new Router($list[$count],$info['community']));
		}
		return $routerList;
	}

	//RUn for A very long time...
	for(;;){
		$ipList = getProbedRouterList();
		//echo var_dump($ipList);
		$routerList = getProbedRouters($ipList);
		foreach($routerList as $key => $value){
			$host = $value->getHost();
			$community = $value->getCommunity();
			system("php log.php $host $community");
		}
		sleep(300);
	}
?>