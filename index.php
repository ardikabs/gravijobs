<!DOCTYPE html>
<html>
<head>
	<!-- Standard Meta -->
	<meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="description" content="Layanan cloud DDS Telkom" />
	
	<!-- Site Properties -->
	<title>GRAVIJOBS</title>

	<link rel="stylesheet" type="text/css" href="assets/css/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/custom.css">		

	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/semantic.min.js"></script>
	<script type="text/javascript" src="assets/js/custom.js"></script>
</head>

<body>
<div class="pusher">
	

<?php 
    include ('config/dbconfig.php');
    include ('config/session.php');
      $pages_dir = 'layout';		

      if (!empty($_GET['p'])){
            $pages = scandir($pages_dir, 0);
            unset($pages[0], $pages[1]);
	          $p = $_GET['p'];

            if($p == "logout"){
              include ('config/logout.php');
            }
            else{
              if($p != "login" && $p!= "register"){
                  include ('static/header.php');     
              }


              if(in_array($p.'.php', $pages)){

                  include($pages_dir.'/'.$p.'.php');
              }else {
                    include ('handler/404.php');
              }

              if($p != "login" && $p!= "register"){
               include ('static/footer.php');         
              }              
            }

      }

      else {
    			include ('static/header.php');			
          include($pages_dir.'/home.php');
          include ('static/footer.php');        	

      }
?>

</div>

</body>
</html>