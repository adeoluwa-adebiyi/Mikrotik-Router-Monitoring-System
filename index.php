<html>
	<head>
		<title>Home</title>
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script type="text/javascript" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
 crossorigin="anonymous"></script>
	</head>
 <link rel="stylesheet" href="custom.css"/>
	<body>
	<div id="app">
		<div class='navbar navbar-mod'>
			<div class="navbar-header">
				<div class="navbar-brand navbar-brand-mod">Router Monitoring System</div>
			</div>
		</div>
		<div class='container container-no-padding'>
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

				<div class='col-md-8'>
					<div class='panel panel-mod-black'>
						<div class='panel-heading'>
							<div class='panel-title'><strong>Community</strong></div>
						</div>
							<table class='table table-default'>
								<thead>
									<tr><td><strong>Identity</strong></td></tr>
								</thead>
								<tbody>
								<?php
									include('./credentials.php');
									//session_start();
									$conn = new mysqli($db_host,$db_user,$db_pwd) or die('Unable to connect to database');
									$conn->select_db('mikrotik');
									$result = $conn->query('SELECT name from community');
									
									$name_table='';
									/**$data = $result->fetch_assoc();

									if(is_null($data)){
										echo "<div class='panel-body'>No community yet!</div>";
									}else{**/
										while(($data = $result->fetch_assoc())!=NULL){
											foreach ($data as $key => $value) {
												$link = "<a class='nav-item' href=http://localhost/check_community.php?community=$value>".$value."</a>";
												$name_table .='<tr><td>'.$link.'</td></tr>';
											}
										}
										echo $name_table;
									//}
								?>
								</tbody>
							</table>
					</div>
				</div>
			</div>
		</div>
		</div>
	</body>
</html>
