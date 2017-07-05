<?php
 if(isset($_POST['action'])){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($_POST['action'] == 'activate'){
        $id_user = $_POST['id_user'];
        $sql = 'UPDATE user set valid=1 WHERE id_user=:id_user';
        $q = $pdo->prepare($sql);
        $q->execute(array(':id_user'=>$id_user));
    }
    else if($_POST['action'] == 'delete'){
        $id_user = $_POST['id_user'];
        $id_lembaga = $_POST['id_lembaga'];
        $sqluser = 'DELETE FROM user WHERE id_user=:id_user';
        $sqldetailuser = 'DELETE FROM user_detail WHERE id_user=:id_user';
        $sqllembaga = 'DELETE FROM lembaga WHERE id_lembaga=:id_lembaga';
        $q = $pdo ->prepare($sqluser);
        $q->execute(array(':id_user'=>$id_user));
        $q = $pdo ->prepare($sqldetailuser);
        $q->execute(array(':id_user'=>$id_user));
        $q = $pdo ->prepare($sqllembaga);
        $q->execute(array(':id_lembaga'=>$id_lembaga));
    }
 }
?>
<div class="ui vertical stripe container">
    <div class="ui stackable grid container">
        <div class="row" style="margin-top:10vh">
            <h3 class="ui header">Manage Request Account</h3>
            <div class="ui segment">
                <div class="ui vertical stripe center aligned container">
                    <h2>Daftar Permintaan Akun </h2>
                    <table class="ui teal table center aligned">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Kontak</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT * FROM user WHERE valid = 0';
                              $q = $pdo->prepare($sql);
                              $q->execute();
                              $result = $q->fetchAll();

                              if(!empty($result)){


                                  foreach ($result as $res) {
                                 
                        ?>
                            <tr>
                                <form id='form-<?=$res['id_user']?>' method='POST'>
                                    <?php
                                        $sqluserinfo = 'SELECT * FROM user_detail WHERE id_user=:id_user LIMIT 1';
                                        $q = $pdo->prepare($sqluserinfo);
                                        $q->execute(array(':id_user'=>$res['id_user']));
                                        $user = $q->fetch(PDO::FETCH_ASSOC);

                                        $sqlcompany = 'SELECT * FROM lembaga WHERE id_lembaga=:id_lembaga LIMIT 1';
                                        $q = $pdo->prepare($sqlcompany);
                                        $q->execute(array(':id_lembaga'=>$user['id_lembaga']));
                                        $lembaga = $q->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <td><?=$user['nama_lengkap']?></td>
                                    <td><?=$res['email']?></td>
                                    <td><?=$lembaga['nama_lembaga']?></td>
                                    <td><?=$user['nomor_hp']?></td>
                                    <input name="action" type="hidden" value="" id='action-<?=$res['id_user']?>'>
                                    <input name="id_user" type="hidden" value="<?=$res['id_user']?>" >
                                    <input name="id_lembaga" type="hidden" value="<?=$user['id_lembaga']?>">


                                </form>
                                    <td>
                                        <button class="ui teal button detail" onclick="showdetail('<?=$user['nama_lengkap']?>','<?=$res['email']?>','<?=$user['nomor_hp']?>','<?=$user['nomor_identitas']?>','<?=$lembaga['nama_lembaga']?>','<?=$lembaga['alamat_lembaga']?>','<?=$lembaga['CP_lembaga']?>')">Detail</button>
                                        <button class="ui blue button " onclick="setactive(<?=$res['id_user']?>)">Terima</button>
                                        <button class="ui red button " onclick="setdelete(<?=$res['id_user']?>)">Tolak</button>
                                    </td>
                                   
                            </tr>
                        <?php
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>                
            </div>

        </div>
    </div>
</div>

<div class="ui modal detail">
    <i class="close icon"></i>
    <div class="header">
        Detail Akun
    </div>
   <div class="content">
        <table class="ui table">
            <tbody>
                <tr>
                    <td>Nama Lengkap</td>
                    <td><p id="nama-lengkap"></p></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><p id="email"></p></td>
                </tr>
                <tr>
                    <td>Nomor HP</td>
                    <td><p id="nomor_hp"></p></td>
                </tr>
                <tr>
                    <td>Nomor Identitas</td>
                    <td><p id="nomor_id"></p></td>
                </tr>
                <tr>
                    <td>Perusahaan</td>
                    <td><p id="nama-lembaga"></p></td>
                </tr>
                <tr>
                    <td>Alamat Perusahaan</td>
                    <td><p id="alamat-lembaga"></p></td>
                </tr>
                <tr>
                    <td>CP Perusahaan</td>
                    <td><p id="cp-lembaga"></p></td>
                </tr>
            </tbody>
        </table>
    </div>  
</div>

<script type="text/javascript">
    $('.ui.modal.detail')
        .modal('attach events', '.ui.teal.button.detail', 'show')
        .modal('setting', 'transition', 'fade up');


    function showdetail(name,email,nomorhp,nomorid,nlembaga,alembaga,cplembaga){
        document.getElementById('nama-lengkap').innerHTML = name;
        document.getElementById('email').innerHTML = email;
        document.getElementById('nomor_hp').innerHTML = nomorhp;
        document.getElementById('nomor_id').innerHTML = nomorid;
        document.getElementById('nama-lembaga').innerHTML = nlembaga;
        document.getElementById('alamat-lembaga').innerHTML = alembaga;
        document.getElementById('cp-lembaga').innerHTML = cplembaga;
    }

    function setactive(id_user) {
        document.getElementById("action-" + id_user).value = "activate"
        var form = document.getElementById('form-'+id_user);
        form.submit();
    }
    function setdelete(id_user) {
        document.getElementById("action-" + id_user).value = "delete"
        var form = document.getElementById('form-'+id_user);
        form.submit();
    }
</script>
