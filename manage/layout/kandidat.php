<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_pengajuan = $_POST['id_pengajuan'];

        if($_POST['action'] == 'accepted'){
            $sql = 'UPDATE pengajuan_cv set status = 1 WHERE id_pengajuan=:id_pengajuan ';
        }
        else if($_POST['action'] == 'rejected'){
            $sql = 'UPDATE pengajuan_cv set status = 2 WHERE id_pengajuan=:id_pengajuan ';
        }
        $q = $pdo->prepare($sql);
        $q->execute(array(':id_pengajuan'=>$id_pengajuan));
    }
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM user_detail WHERE id_user=:id_user';
    $q = $pdo->prepare($sql);
    $q->execute(array(':id_user'=>$_SESSION['user_session']));
    $row = $q->fetch(PDO::FETCH_ASSOC);

    $id_lembaga = $row['id_lembaga'];
?>
<div class="ui vertical stripe container">
    <div class="ui stackable grid container">
        <div class="row" style="margin-top:10vh">
            <h3 class="ui header">Kandidat Pelamar</h3>
            <div class="ui segment">
                <div class="ui vertical stripe center aligned container">
                    <h2>Daftar Kandidat Pelamar</h2>
                    <table class="ui teal table center aligned">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Kontak</th>
                                <th>Lowongan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = 'SELECT id_lowongan FROM lowongan_kerja WHERE id_lembaga=:id_lembaga';
                                $q = $pdo->prepare($sql);
                                $q->execute(array(':id_lembaga'=>$id_lembaga));
                                $result = $q->fetchAll(PDO::FETCH_ASSOC);
                                $arr = array();
                                foreach ($result as $res) {
                                  array_push($arr,$res['id_lowongan']);
                                }
                                $ids = join(',',$arr);
                                $filteredLoker = "SELECT * FROM pengajuan_cv WHERE id_lowongan in ($ids) AND status = 1";
                                $q = $pdo->prepare($filteredLoker);
                                $q->execute();
                                $result = $q->fetchAll();

                            foreach ($result as $res) {
                                $sqlloker = 'SELECT * FROM lowongan_kerja WHERE id_lowongan =:id_lowongan';
                                $q = $pdo->prepare($sqlloker);
                                $q->execute(array(':id_lowongan'=>$res['id_lowongan']));
                                $data = $q->fetch(PDO::FETCH_ASSOC);
                                
                            ?>
                            <tr>
                                <form id="form-<?=$res['id_pengajuan']?>" method='POST'>
                                    <td><?=$res['nama_lengkap']?></td>
                                    <td><?=$res['email']?></td>
                                    <td><?=$res['nomor_hp']?></td>
                                    <td><?=$data['judul_lowongan']?></td>
                                    <input type="hidden" name="id_pengajuan" value="<?=$res['id_pengajuan']?>">
                                    <input id="action-<?=$res['id_pengajuan']?>" type="hidden" name="action" value="">
                                </form>
                                    <td>
                                        

                                        <button class="ui teal button detail" onclick="showdetail('<?=$res['nama_lengkap']?>','<?=$res['email']?>','<?=$res['nomor_hp']?>','<?=$data['judul_lowongan']?>','<?=$res['file_cv']?>','<?=$res['file_identitas']?>','<?=$res['file_foto']?>')">Lihat Data</button>
                                        <button class="ui blue button" onclick="accepted(<?=$res['id_pengajuan']?>)">Download</button>
                                    </td>
                            </tr>
                            <?php
                            }
                            Database::disconnect();
                            ?>
                        </tbody>
                         <tfoot>
                            <tr>
                                <th colspan="5">
                                    <a class="ui right floated green button add-lowongan">Download Seluruh Data Kandidat</a>
                                </th>
                            </tr>
                        </tfoot>
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
                    <td>Lowongan Kerja</td>
                    <td><p id="loker"></p></td>
                </tr>
                <tr>
                    <td>File CV</td>
                    <td><a target=_blank id="file-cv"></a></td>
                </tr>
                <tr>
                    <td>File Foto</td>
                    <td><a target=_blank id="file-foto"></a></td>
                </tr>
                <tr>
                    <td>File Identitas</td>
                    <td><a target=_blank id="file-identitas"></a></td>
                </tr>
            </tbody>
        </table>
    </div>  
</div>

<script>
    $('.ui.modal.detail')
        .modal('attach events', '.ui.teal.button.detail', 'show')
        .modal('setting', 'transition', 'fade up');

    function showdetail(name,email,nomorhp,nomorid,filecv,fileft,fileid) {
        document.getElementById('nama-lengkap').innerHTML = name;
        document.getElementById('email').innerHTML = email;
        document.getElementById('nomor_hp').innerHTML = nomorhp;
        document.getElementById('loker').innerHTML = nomorid;
        document.getElementById('file-cv').innerHTML = "Download";
        document.getElementById('file-foto').innerHTML = "Download";
        document.getElementById('file-identitas').innerHTML = "Download";

        document.getElementById('file-cv').href = "../"+filecv;
        document.getElementById('file-foto').href = "../"+fileft;
        document.getElementById('file-identitas').href= "../"+fileid;

    }
    function accepted(id) {
        document.getElementById("action-" + id).value = "accepted"
        var form = document.getElementById("form-"+id);
        form.submit();
    }
    function rejected(id) {
        document.getElementById("action-" + id).value = "rejected"
        var form = document.getElementById("form-"+id);
        form.submit();
    }
</script>
