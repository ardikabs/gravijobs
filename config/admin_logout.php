<?php
   
   if(session_destroy()) {
     unset($_SESSION['admin_session']);
     header("Location:http://jobs.gravicodev.id");
   }
?>