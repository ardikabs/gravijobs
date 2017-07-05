<?php
   
   if(session_destroy()) {
     unset($_SESSION['user_session']);
     header("Location:http://jobs.gravicodev.id");
   }
?>