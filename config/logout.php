<?php
   
   if(session_destroy()) {
     session_unset();
     header("Location:http://jobs.gravicodev.id");
   }
?>