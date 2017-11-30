<?php 
        include("./chartphp_dist.php"); 
        require_once 'credentials.php';
        require_once 'InterfaceMonitor.php';

        $interface_monitor = new InterfaceMonitor(new SNMPMonitor($_GET['host'],$_GET['community']));
        $interface_monitor->getPortList();
        $pic_array = array();

        for($count = 0;$count < count($interface_monitor->portList);$count++){

	        $p = new chartphp(); 

	        $sql = "SELECT * FROM bandwidth where `date`=CURDATE() and `host`='".$_GET['host']."' and port='".$interface_monitor->portList[$count]."'";
	        //echo $sql;
	        $db = new mysqli($db_host,$db_user,$db_pwd,$db_name);
	        $result = $db->query($sql);
	        //$bandwidth_usage_info = $result->fetch_assoc();
	        //echo var_dump($bandwidth_usage_info);

	        $input = array();
	        $output = array();
	        while(($bandwidth = $result->fetch_assoc())!=NULL) {
	        	# code...
	        	array_push($input, array($bandwidth['time'],(($bandwidth['data_in']/1800.0)*8.0)/1024.0));
	        	array_push($output, array($bandwidth['time'],(($bandwidth['data_out']/1800.0)*8.0)/1024.0));
	        }

	        $p->data = array($input,$output);

	        $p->chart_type = "line"; 
	        $p->color = "red,blue";

	        // Common Options 
	        $p->title = "Bandwidth Statistics"; 
	        $p->xlabel = "Time (5 mins interval)"; 
	        $p->ylabel = "kbps"; 

	        $out = $p->render('c1'); 
	        array_push($pic_array, $out);
	    }

?> 
<html>
	<head>
		<title><?php echo $_GET['router']?></title>
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script type="text/javascript" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
 crossorigin="anonymous"></script>
 <link rel="stylesheet" href="custom.css"/>
         <script src="/jquery.min.js"></script> 
        <script src="/chartphp.js"></script> 
        <link rel="stylesheet" href="/chartphp.css"> 
	</head>
	<body>
		<div id="app">
		<div class='navbar navbar-mod'>
			<div class="navbar-header">
				<div class="navbar-brand">Router Monitoring System</div>
			</div>
			<div class='navbar-right'>
				<img class="logo" src="mtlogo.png"></img>
			</div>
		</div>
		<div class='container container-no-padding'>

			<div class="row">
				<div class="col-md-3">
				</div>
				<div class="col-md-6">
					<?php echo '<h3>Router Identity</h3>'?>
				</div>
				<div class="col-md-3">
				</div>
			</div>
			<div class='row row-no-padding'>
				
				<div class='col-md-3' id="menu">
					<div class='panel panel-mod-black'>
						<div class='panel-heading'>
							<div class='panel-title'><strong>Menu</strong></div>
						</div>
						<div class="panel-body">
							<ul class='nav nav-pill'>
								<li class='nav-item'><a class='active' href="http://localhost/index.php">Community</a>
								</li>
								<li class='nav-item'><a href="http://localhost/add_community.php">Add Community</a>
								</li>
								<li class='nav-item'><a href='http://localhost/search.php'>Search</a>
								</li>

							</ul>
						</div>
					</div>
				</div>

				<div class='col-md-6'>
				<?php
				for($count=0;$count < count($pic_array);$count++){
					$graph_template = "<div class='panel panel-mod-black'>
						<div class='panel-heading'>
							<div class='panel-title'><strong>Summary</strong></div>
						</div>
						<div class='panel-body'>
							<div style='width:60%;height: 60%;min-width:500px;'> ".$pic_array[0]."</div> 
						</div>
					</div>";
					echo $graph_template;
				}
				?>
					<!--<div class='panel panel-mod-black'>
						<div class='panel-heading'>
							<div class='panel-title'><strong>Summary</strong></div>
						</div>
						<div class="panel-body">
							<div style="width:60%;height: 60%;min-width:500px;"> 
					            <?php echo $pic_array[0]; ?> 
					        </div> 
						</div>
					</div>-->
				</div>
				
				<div class='col-md-3'>
					<div class='panel panel-mod-black'>
						<div class='panel-heading'>
							<div class='panel-title'><strong>Information</strong></div>
						</div>
						<div class="panel-body">
							<ul class='nav nav-pill'>
						<?php
							error_reporting(0);
							require_once('credentials.php');
							$router = $_GET['router'];
							echo "<ul class='nav nav-pill'>";
							
							$conn = new mysqli($db_host,$db_user,$db_pwd,$db_name);
							$query = "SELECT ip FROM router where name='$router'";
							$result = $conn->query($query);
							
							$data = $result->fetch_assoc();
							$ip = $data['ip'];
							
							echo "<li class='nav-item'><a href='http://localhost/check_router.php?router=$router&community=".$_GET['community']."&host=".long2ip($ip)."'>Identity</a></li>";
							echo "<li class='nav-item'><a href='http://localhost/interface.php?router=$router&community=".$_GET['community']."&host=".long2ip($ip)."'>Interfaces</a></li>";
							echo "<li class='nav-item'><a class='active' href='http://localhost/bandwidth.php?router=$router&community=".$_GET['community']."&host=".long2ip($ip)."'>Bandwidth Usage</a></li>";
							echo "<li class='nav-item'><a href='http://localhost/health.php?router=$router&community=".$_GET['community']."&host=".long2ip($ip)."'>Health</a></li>";
						?>

						<script type="text/javascript">
							setTimeout(function(){
							   window.location.reload(1);
							}, 5000);
						</script>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</body>
</html>
