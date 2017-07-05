<!DOCTYPE html>
<html>
<head>
	<!-- Standard Meta -->
	<meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="description" content="Layanan Pekerjaan Mahasisw" />
	
	<!-- Site Properties -->
	<title>Admin Manage</title>

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
    include ('../config/admin_session.php');

    if (!isset($_SESSION['admin_session'])){
       if(session_destroy()) {
         unset($_SESSION['admin_session']);
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
                        <a href="/gravijobs">
                          <img class="ui tiny image" src="../assets/img/gravity.png">
                          </a>
                    </div>
                    <div class="right item">
                        <?php
                          if(empty($_GET['p']) || $_GET['p'] == "request-account"){
                            ?>
                          <a class="item active" href="?p=request-account">Request Account</a>
                          <a class="item " href="?p=list-account">List Account</a>
                          <a class="item " href="?p=list-lowongan">Daftar Lowongan</a>
                          <a class="item " href="?p=sistem">Manajemen Sistem</a>

                        <?php
                          }
                          else if($_GET['p'] == "list-lowongan"){
                            ?>
                          <a class="item " href="?p=request-account">Request Account</a>
                          <a class="item " href="?p=list-account">List Account</a>
                          <a class="item active" href="?p=list-lowongan">Daftar Lowongan</a>
                          <a class="item " href="?p=sistem">Manajemen Sistem</a>

                        <?php
                          }
                          else if($_GET['p'] == "sistem"){
                            ?>
                          <a class="item " href="?p=request-account">Request Account</a>
                          <a class="item " href="?p=list-account">List Account</a>
                          <a class="item " href="?p=list-lowongan">Daftar Lowongan</a>
                          <a class="item active" href="?p=sistem">Manajemen Sistem</a>
                        <?php
                          }
                          else{
                          ?>
                          <a class="item " href="?p=request-account">Request Account</a>
                          <a class="item active " href="?p=list-account">List Account</a>
                          <a class="item " href="?p=list-lowongan">Daftar Lowongan</a>
                          <a class="item " href="?p=sistem">Manajemen Sistem</a>
                        <?php
                          }
                        ?>
                        
                        <div class="ui top right  dropdown" style="margin-left: 1.5em;">
                            <img class="ui avatar image" src="../assets/img/avatar/user.png">
                            <i class="dropdown icon"></i>
                            <div class="menu" style="right:0;left:auto;">
                                <a class="item">
                                    <img class="ui avatar image" src="../assets/img/avatar/user.png">
                                    
                                    <span><?=$_SESSION['admin_name']?></span>
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
                        include ('../config/admin_logout.php');
                    }
                    else{

                      if(in_array($p.'.php', $pages)){

                          include($pages_dir.'/'.$p.'.php');
                      }else {
                          echo 'Halaman tidak ditemukan! :(';
                      }

                    }



              }
              else {
                    include ('layout/request-account.php');
              }
        ?>   
    </div>


    <script>
        $('.ui.dropdown')
            .dropdown();
    </script>

</body>
    


</html>
