<style>
    body{
        background-color: mediumaquamarine;
    }
</style>
<?php
if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']); 
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM user WHERE email=:email LIMIT 1';
    $qUser = $pdo->prepare($sql);
    $qUser->execute(array(':email'=>$email));
    $userRow = $qUser->fetch(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM user_detail WHERE id_user=:id_user LIMIT 1';
    $q = $pdo->prepare($sql);
    $q->execute(array(':id_user'=>$userRow['id_user']));
    $userInfo = $q->fetch(PDO::FETCH_ASSOC);

    if ($qUser->rowCount() > 0){
        if (password_verify($pass, $userRow['password'])){
            if(!empty($userInfo)){

                $_SESSION['id_lembaga'] = $userInfo['id_lembaga'];
                $_SESSION['user_name'] = $userInfo['nama_lengkap'];
                $_SESSION['user_addr'] = $userInfo['alamat'];
                $_SESSION['user_phone'] = $userInfo['nomor_hp'];
                $_SESSION['user_id_number'] = $userInfo['nomor_identitas'];
            }

            if($userRow['id_role'] == 1){
                $_SESSION['admin_session'] = $userRow['id_user'];
                $_SESSION['admin_name'] = "Super Admin";

                ?>
                <script type="text/javascript">
                    window.location.href = 'http://jobs.gravicodev.id/admin';
                </script>
            <?php
            }
            else if($userRow['id_role'] == 2 and $userRow['valid'] == 1 and $userRow['status'] == 1){
                $_SESSION['user_session'] = $userRow['id_user'];
                ?>
                <script type="text/javascript">
                window.location.href = 'http://jobs.gravicodev.id/manage';
                </script>
                <?php
            }
            else if($userRow['id_role'] == 2 and $userRow['valid'] == 0 and $userRow['status'] == 1){
                $_SESSION['user_session'] = $userRow['id_user'];
                echo '<div class="ui container" style="margin-top: 1em;">
                    <div class="ui error message">
                        <p>Login Gagal. Akun anda masih belum aktif. Tunggu 2x24 jam !</p>
                    </div>
                </div>';
            }
            else if($userRow['id_role'] == 2 and $userRow['valid'] == 1 and $userRow['status'] == 2){
                $_SESSION['user_session'] = $userRow['id_user'];
                echo '<div class="ui container" style="margin-top: 1em;">
                    <div class="ui error message">
                        <p>Login Gagal. Akun anda telah di Suspend. Hubungi admin !.</p>
                    </div>
                </div>';
            }
            else if($userRow['id_role'] == 2 and $userRow['valid'] == 1 and $userRow['status'] == 0){
                $_SESSION['user_session'] = $userRow['id_user'];
                echo '<div class="ui container" style="margin-top: 1em;">
                    <div class="ui error message">
                        <p>Login Gagal. Akun anda telah di BANNED.</p>
                    </div>
                </div>';
            }            
            return true;
        }
        else {
            echo '<div class="ui container" style="margin-top: 1em;">
                <div class="ui error message">
                    <p>Login Gagal</p>
                </div>
            </div>';
        }
    }
    else
    {
        echo '<div class="ui container" style="margin-top: 1em;">
                <div class="ui error message">
                    <p>Login Gagal</p>
                </div>
            </div>';
    } 
    Database::disconnect();
}
?>
<div class="ui stackable middle aligned center aligned grid" id="top-login-regis">
    <div class="column" id="content-login-regis">
        <a href="?p=home">
            <img class="ui centered medium image" src="assets/img/gravity.png">
        </a>

        <p class="ui header">Silahkan Masuk ke Dalam Akun Perusahaan Anda</p>
        </br>

        <form class="ui form" method="post">
            <div class="field">
                <input type="email" name="email" required placeholder="E-mail">
            </div>
            <div class="field">
                <input type="password" name="password" required placeholder="Password">
            </div>
            <div class="inline fields">
                <div class="field">
                    <div class="ui checkbox">
                        <input type="checkbox" tabindex="0" class="hidden">
                        <label>Ingat saya</label>
                    </div>
                </div>
            </div>
            <input class="ui fluid primary button" type="submit" name="login" value="Masuk">
            </br>
            <a href="#">Lupa Password?</a>
        </form>

        </br>
        <div class="ui horizontal divider">Atau</div>
        </br>
        <a href="http://jobs.gravicodev.id/handler/fbconfig.php" title="Signup with facebook">
        <button class="ui fluid facebook button"><i class="facebook icon"></i>Masuk dengan Facebook</button>
        </a>
        </br>
        </br>
        <p>Perusahaan anda belum terdaftar ? Request Akun sekarang juga ! <br><a href="?p=register">Daftar Sekarang</a></p>
    </div>
</div>