<?php
   
   if(session_destroy()) {
     unset($_SESSION['admin_session']);
      ?>
        <script type="text/javascript">
        window.location.href = 'http://localhost:8080/gravijobs';
        </script>
      <?php
   }
?>