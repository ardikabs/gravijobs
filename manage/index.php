<!DOCTYPE html>
<html>
<head>
	<!-- Standard Meta -->
	<meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="description" content="Layanan Pekerjaan Mahasiswa" />
	
	<!-- Site Properties -->
	<title>Manage</title>

	<link rel="stylesheet" type="text/css" href="../assets/css/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/custom.css">		

	<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/semantic.min.js"></script>
	<script type="text/javascript" src="../assets/js/custom.js"></script>
  <script type="text/javascript" src="../assets/js/Chart.min.js"></script>

</head>

<body>
<?php
    include ('../config/dbconfig.php');
    include ('../config/session.php');

    if (!isset($_SESSION['user_session'])){
       if(session_destroy()) {
         unset($_SESSION['user_session']);
          ?>
            <script type="text/javascript">
            window.location.href = 'http://jobs.gravicodev.id/';
            </script>
          <?php
       }
    }

?>
    <div class="pusher">
        <!-- Menu -->
        <div class="ui teal inverted vertical masthead center aligned segment borderless" style="min-height: 50px">

            <div class="ui container">
                <div class="ui secondary inverted menu">
                    <div class="left item">
                        <a href="/">
                          <img class="ui tiny image" src="../assets/img/gravity.png">
                          </a>
                    </div>
                    <div class="right item">
                        <a class="item" href="?p=statistik">Statistik</a>
                        <a class="item" href="?p=lowongan">Lowongan Kerja</a>
                        <a class="item" href="?p=calon-kandidat">Calon Kandidat</a>
                        <a class="item" href="?p=kandidat">Kandidat</a>

                        <div class="ui top right  dropdown" style="margin-left: 1.5em;">
                            <img class="ui avatar image" src="../assets/img/avatar/user.png">
                            <i class="dropdown icon"></i>
                            <div class="menu" style="right:0;left:auto;">
                                <a class="item">
                                    <img class="ui avatar image" src="../assets/img/avatar/user.png">
                                    
                                    <span><?=$_SESSION['user_name']?></span>
                                </a>
                                <div class="divider"></div>
                                <a class="item" href="?p=settings">Settings</a>
                                <a class="item" href="?p=logout">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   

        <?php
            $pages_dir = 'layout';      


              if (!empty($_GET['p'])){
                    $pages = scandir($pages_dir, 0);
                    unset($pages[0], $pages[1]);
                      $p = $_GET['p'];

                    if($p == "logout"){
                        include ('../config/logout.php');
                    }
                    else{

                      if(in_array($p.'.php', $pages)){

                          include($pages_dir.'/'.$p.'.php');
                      }else {
                          include ('../handler/404.php');
                      }

                    }



              }
              else {
                    include ('layout/lowongan.php');
              }
        ?>   
    </div>


    <script>
        $('.ui.dropdown')
            .dropdown();
    </script>

</body>
    


</html>
