<?php 
        /** 
         * Charts 4 PHP 
         * 
         * @author Shani <support@chartphp.com> - http://www.chartphp.com 
         * @version 1.2.3 
         * @license: see license.txt included in package 
         */ 
          
        include("./chartphp_dist.php"); 

        $p = new chartphp(); 

        $conn = new mysqli("localhost","root","","mikrotik");
		$result = $conn->query("SELECT * from bandwidth where `date`=CURDATE() AND `port`='ether2'");
		$array = array();
		while(($info = $result->fetch_assoc())!=NULL){
			echo $info['data_in']."<br/>";
			array_push($array, array($info['time'],$info['data_in']/1000000));
		}

		$container = array($array);

        $p->data = $container;
        $p->chart_type = "area"; 

        // Common Options 
        $p->title = "Bandwidth Usage : Ether2 Port"; 
        $p->xlabel = "Time"; 
        $p->ylabel = "Bytes"; 
        $p->height = "40%";
        $p->width = "40%";
        $p->color="#ff0000";

        $out = $p->render('c1'); 
?> 
<!DOCTYPE html> 
<html> 
    <head> 
        <script src="/jquery.min.js"></script> 
        <script src="/chartphp.js"></script> 
        <link rel="stylesheet" href="/chartphp.css"> 
    </head> 
    <body> 
        <div> 
            <?php echo $out; ?> 
        </div> 
    </body> 
</html> 