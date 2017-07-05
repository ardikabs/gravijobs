<style>
    body{
        background-color: mediumaquamarine;
    }
</style>
<?php
if(isset($_POST['register']))
{
   $email = trim($_POST['email']);
   $pass = trim($_POST['password']); 
   $confpass = trim($_POST['conf-password']);
   $provider = "email";

   $name = trim($_POST['name']);
   $alamat = trim($_POST['alamat']);
   $nohp = trim($_POST['nomor_hp']);
   $noid = trim($_POST['nomor_id']);

   $nlembaga = trim($_POST['nama_lembaga']);
   $alembaga = trim($_POST['alamat_lembaga']);
   $dlembaga = trim($_POST['deskripsi']);
   $cplembaga = trim($_POST['cp_lembaga']);
   $llembaga= trim($_POST['logo_lembaga']);
 
   if($name=="") {
      $error[] = "Nama Lengkap harus diisi !"; 
   }
   else if($email=="") {
      $error[] = "Email harus diisi !"; 
   }
   else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error[] = 'Masukkan format email yang benar !';
   }
   else if($pass=="") {
      $error[] = "Password harus diisi !";
   }
   else if(strlen($pass) < 6){
      $error[] = "Password harus terdiri minimal 6 karakter !"; 
   }
   else if(strcmp($pass, $confpass)){
      $error[] = "Password harus sama !";
   }
   else
   {
      try
      {
          $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = 'SELECT email FROM user WHERE email=:email';
          $q = $pdo->prepare($sql);
          $q->execute(array(':email'=>$email));
          $row = $q->fetch(PDO::FETCH_ASSOC);
    
         if($row['email']==$email) {
            $error[] = "Maaf ! Email anda telah terdaftar !";
            Database::disconnect();
         }
         else
         {
             Database::disconnect();
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $role = 2; //Customer
            $new_password = password_hash($pass, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO user (id_role, email, password,provider,status,valid) VALUES(:role, :email, :pass,:provider,1,0)';
            $q = $pdo->prepare($sql);
            $q->execute(array(':role'=>$role, ':email'=>$email, ':pass'=>$new_password, ':provider'=>$provider));
            $id_user = $pdo->lastInsertId();

            $sql = 'INSERT INTO lembaga (nama_lembaga,deskripsi,alamat_lembaga,cp_lembaga,logo_lembaga) VALUES (:nama_lembaga,:deskripsi,:alamat_lembaga,:cp_lembaga,:logo_lembaga)';
            $q = $pdo->prepare($sql);
            $q->execute(array(':nama_lembaga'=>$nlembaga,':deskripsi'=>$dlembaga,':alamat_lembaga'=>$alembaga,':cp_lembaga'=>$cplembaga,':logo_lembaga'=>$llembaga));
            $id_lembaga = $pdo->lastInsertId();

            $sql = 'INSERT INTO user_detail (id_user,id_lembaga,nama_lengkap,alamat,nomor_hp,nomor_identitas) VALUES(:id_user,:id_lembaga,:nama_lengkap,:alamat,:nomor_hp,:nomor_identitas)';
            $q = $pdo->prepare($sql);
            $q->execute(array(':id_user'=>$id_user,':id_lembaga'=>$id_lembaga,':nama_lengkap'=>$name,':alamat'=>$alamat,':nomor_hp'=>$nohp,':nomor_identitas'=>$noid));
            $success = "Registration Success";
            ?>
                <div class="ui container" style="margin-top: 1em;">
                    <div class="ui success message">
                        <p><?php echo $success; ?></p>
                    </div>
                </div>
            <?php
            Database::disconnect();
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }
  } 
}

?>
<div class="ui stackable middle aligned center aligned grid" id="top-login-regis">
    
    <div class="column" id="content-login-regis">
        <a href="?p=home">
            <img class="ui centered medium image" src="assets/img/gravity.png">
        </a>

        <p class="ui header">Daftarkan Perusahaan Anda Sekarang</p>
        </br>


        <form class="ui form" method="post">
           <?php
              if(isset($error))
              {
                  foreach($error as $error)
                  {
                      ?>
                      <div class="ui error message" style="display:block;">
                          <div class="header">Something when wrong</div>
                          <p><?php echo $error; ?></p>
                      </div>
                      <?php
                  }
              }
              else if(isset($success))            
              {
                  ?>
                  <div class="ui success message">
                      <div class="header"><?php echo $success; ?></div>
                      <p><a href='index.php'>login</a> here.</p>
                  </div>
                  <?php
              }

            ?> 
            <div class="ui horizontal divider">Informasi Akun</div>
            <div class="field">
                <input type="email" name="email" required placeholder="E-mail">
            </div>
            <div class="field">
                <input type="password" name="password" required placeholder="Password">
            </div>
            <div class="field">
                <input type="password" name="conf-password" required placeholder="Konfirmasi Password">
            </div>

            <div class="ui horizontal divider">Informasi CP</div>

            <div class="field">
                <input type="text" name="name" required placeholder="Nama Lengkap">
            </div>
            <div class="field">
                <input type="text" name="alamat" placeholder="Alamat">
            </div>
            <div class="field">
                <input type="text" name="nomor_hp" required placeholder="Nomor HP">
            </div>
            <div class="field">
                <input type="text" name="nomor_id" required placeholder="Nomor Identitas (KTP)">
            </div>
            

            <div class="ui horizontal divider">Informasi Perusahaan</div>

            <div class="field">
                <input type="text" name="nama_lembaga" required placeholder="Nama Lembaga">
            </div>
            <div class="field">
                <input type="text" name="deskripsi" placeholder="Deskripsi">
            </div>
            <div class="field">
                <input type="text" name="alamat_lembaga" required placeholder="Alamat Lembaga">
            </div>
            <div class="field">
                <input type="text" name="cp_lembaga" required placeholder="CP Lembaga">
            </div>
            <div class="field">
                <input type="text" name="logo_lembaga" placeholder="Logo Lembaga">
            </div>

            <div class="field">
                <p class="ui centered aligned container">Dengan mendaftarkan perusahaan anda, anda telah menyetujui Aturan Penggunaan dan Kebijakan Privasi dari Gravijobs.</p>
            </div>
            <input class="ui fluid primary button" type="submit" name="register" value="Daftar">
        </form>
        </br>
        <div class="ui horizontal divider">Atau</div>
        </br>
        <a href="http://localhost:8080/gravijobs/handler/fbconfig.php">
        <button class="ui fluid facebook button"><i class="facebook icon"></i>Daftar dengan Facebook</button>
        </a>
        </br>
        </br>
        <p>Sudah punya akun? <a href="?p=login">Silakan Login</a></p>
    </div>
</div>


<script>
    $('.ui.radio.checkbox').checkbox();
</script>