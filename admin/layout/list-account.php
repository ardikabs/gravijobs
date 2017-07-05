<?php
 if($_SERVER["REQUEST_METHOD"] == "POST"){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $id_user = $_POST['id_user'];

    if($_POST['action'] == 'activate'){
        $sql = 'UPDATE user set status=1 WHERE id_user=:id_user ';
    }
    else if($_POST['action'] == 'suspend'){
        $sql = 'UPDATE user set status=2 WHERE id_user=:id_user ';
    }
    else if($_POST['action'] == 'ban'){
        $sql = 'UPDATE user set status=0 WHERE id_user=:id_user ';
    }

    $q = $pdo->prepare($sql);
    $q->execute(array(':id_user'=>$id_user));
    Database::disconnect();
 }
?>
<div class="ui vertical stripe container">
    <div class="ui stackable grid container">
        <div class="row" style="margin-top:10vh">
            <h3 class="ui header">Manage List Account</h3>
            <div class="ui segment">
                <div class="ui vertical stripe center aligned container">
                    <h2>Daftar Akun </h2>
                    <table class="ui teal table center aligned">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT * FROM user WHERE valid = 1 AND id_role = 2';
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
                                    <?php
                                    if ($res['status'] == 0){
                                    ?>
                                    <td>Banned</td>
                                    <?php
                                    }
                                    else if($res['status'] == 1){
                                    ?>
                                    <td>Aktif</td>
                                    <?php
                                    }
                                    else{
                                    ?>  
                                    <td>Suspend</td>
                                    <?php
                                    }
                                    ?>
                                    <input name="action" type="hidden" value="" id='action-<?=$res['id_user']?>'>
                                    <input name="id_user" type="hidden" value="<?=$res['id_user']?>" >


                                </form>
                                    <td>
                                        <button class="ui teal button detail" onclick="setactivate(<?=$res['id_user']?>)">Activated</button>
                                        <button class="ui blue button " onclick="setsuspend(<?=$res['id_user']?>)">Suspended</button>
                                        <button class="ui red button " onclick="setbanned(<?=$res['id_user']?>)">Banned</button>
                                    </td>
                                   
                            </tr>
                        <?php
                                }
                            }
                            Database::disconnect();

                        ?>
                        </tbody>
                    </table>
                </div>                
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $('.ui.modal.detail')
        .modal('attach events', '.ui.teal.button.detail', 'show')
        .modal('setting', 'transition', 'fade up');


    function setactivate(id_user) {
        document.getElementById("action-" + id_user).value = "activate"
        var form = document.getElementById('form-'+id_user);
        form.submit();
    }
    function setsuspend(id_user) {
        document.getElementById("action-" + id_user).value = "suspend"
        var form = document.getElementById('form-'+id_user);
        form.submit();
    }
    function setbanned(id_user) {
        document.getElementById("action-" + id_user).value = "ban"
        var form = document.getElementById('form-'+id_user);
        form.submit();
    }
</script>
