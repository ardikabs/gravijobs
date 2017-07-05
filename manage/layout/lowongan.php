<?php
  if(isset($_POST['action'])){

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $id_user = $_SESSION['user_session'];

    if($_POST['action'] == "delete"){
      $sql = 'DELETE FROM detail_lowongan_kerja WHERE id_lowongan=:id_lowongan';
      $q = $pdo->prepare($sql);
      $q->execute(array(':id_lowongan'=>$_POST['lowonganId']));

      $sql = 'DELETE FROM lowongan_kerja WHERE id_lowongan=:id_lowongan';
      $q = $pdo->prepare($sql);
      $q->execute(array(':id_lowongan'=>$_POST['lowonganId']));

    }
    else{
        $judul = $_POST['judul'];
        $topik = $_POST['topik'];
        $jobdesc = $_POST['jobdesc'];
        $divisi = $_POST['divisi'];
        $tipe = $_POST['tipe'];
        $provinsi = $_POST['provinsi'];
        $kota = $_POST['kota'];
        $gaji = $_POST['gaji'];
        $kategori = $_POST['kategori'];
        $deadline = $_POST['deadline'];

        $sql = 'SELECT id_lembaga FROM user_detail WHERE id_user=:id_user';
        $q = $pdo->prepare($sql);
        $q->execute(array(':id_user'=>$id_user));
        $userData = $q->fetch(PDO::FETCH_ASSOC); 
        $id_lembaga = $userData['id_lembaga'];

      if($_POST['action'] == "add"){


        $sql = 'INSERT INTO lowongan_kerja (id_lembaga,judul_lowongan,deadline) values (?,?,?) ';
        $q = $pdo->prepare($sql);
        $q->execute(array($id_lembaga,$judul,$deadline));

        $id_lowongan = $pdo->lastInsertId();

        $sql = 'INSERT INTO detail_lowongan_kerja (id_lowongan,topik_lowongan,deskripsi_lowongan,divisi,lokasi_provinsi,lokasi_kota,tipe_lowongan,gaji,kategori_lowongan) values (?,?,?,?,?,?,?,?,?) ';
        $q = $pdo->prepare($sql);
        $q->execute(array($id_lowongan,$topik,$jobdesc,$divisi,$provinsi,$kota,$tipe,$gaji,$kategori));

      }
      else if($_POST['action'] == "reset"){
        $id_lowongan = $_POST['lowonganId'];
        $sql = 'UPDATE lowongan_kerja set id_lembaga=?,judul_lowongan=?,deadline=? WHERE id_lowongan=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($id_lembaga,$judul,$deadline,$id_lowongan));
      }
    }
      


    Database::disconnect();
  }
  else{
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql= 'SELECT id_lembaga FROM user_detail WHERE id_user=:id_user';
    $q = $pdo->prepare($sql);
    $q->execute(array(':id_user'=>$_SESSION['user_session']));
    $id_lembaga = $q->fetchColumn(); 
  }


?>
<link href="https://cdn.rawgit.com/mdehoog/Semantic-UI-Calendar/76959c6f7d33a527b49be76789e984a0a407350b/dist/calendar.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.rawgit.com/mdehoog/Semantic-UI-Calendar/76959c6f7d33a527b49be76789e984a0a407350b/dist/calendar.min.js"></script>
<?php
    if($id_lembaga == 9999){
      ?>
        <div class="ui container">
          <div class="ui message" style="margin-top: 1em;">
            <i class="close icon"></i>
            <div class="header">
              PERINGATAN !
            </div>
              <p>Mohon untuk melengkapi informasi perusahaan anda. Apabila 3x24 jam informasi belum terpenuhi, akun anda akan disuspend. Terima Kasih</p>
              <a href="#">Klik Disini Untuk Melengkapi</a>
          </div>
        </div>

    <?php
    }
?>
<div class="ui vertical stripe container">
    <div class="ui stackable grid container">
        
        <div class="row" style="margin-top:10vh">
            <h3 class="ui header">Lowongan Kerja</h3>
            <div class="ui segment">
                <div class="ui vertical stripe center aligned container">
                    <h2>Daftar Lowongan Kerja</h2>
                    <table class="ui teal table">
                        <thead>
                            <tr>
                                <th>Judul Lowongan</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th>Atur</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                             
                              $sql = "SELECT * FROM lowongan_kerja WHERE id_lembaga=$id_lembaga";
                              $q = $pdo->prepare($sql);
                              $q->execute();
                              $result = $q->fetchAll();

                            $date = date('Y-m-d');
                            if(!empty($result)){
                             foreach ($result as $res) {
                          ?>
                            <tr>
                                <td><?=$res['judul_lowongan']?></td>
                                <?php 
                                if($date>$res['deadline']){
                                ?>
                                  <td>Berakhir</td>  
                                <?php
                                }else{
                                ?>
                                  <td>Aktif</td>  
                                <?php
                                    //aktif
                                  }
                                ?>
                                <td><?=$res['deadline']?></td>
                                <?php 
                                if($date>$res['deadline']){
                                ?>
                                  <td>
                                      <a class="ui teal button disabled atur" href="#">Atur Lowongan</a>
                                      <a class="ui red button delete" onclick="dihapus(<?=$res['id_lowongan']?>,'<?=$res['judul_lowongan']?>')">Hapus</a>
                                  </td>
                                <?php
                                }else{
                                  $sql = 'SELECT * FROM detail_lowongan_kerja WHERE id_lowongan=:id_lowongan';
                                  $q = $pdo->prepare($sql);
                                  $q->execute(array(':id_lowongan'=>$res['id_lowongan']));
                                  $data_detail = $q->fetch(PDO::FETCH_ASSOC);

                                  $sql = 'SELECT * FROM job_category WHERE kategori_alias=:kategori_lowongan';
                                  $q = $pdo->prepare($sql);
                                  $q->execute(array(':kategori_lowongan'=>$data_detail['kategori_lowongan']));
                                  $kategori_lowongan = $q->fetch(PDO::FETCH_ASSOC);

                                  $sql = 'SELECT * FROM job_type WHERE type_alias=:type_alias';
                                  $q = $pdo->prepare($sql);
                                  $q->execute(array(':type_alias'=>$data_detail['tipe_lowongan']));
                                  $tipe_lowongan = $q->fetch(PDO::FETCH_ASSOC);

                                  $sql = 'SELECT * FROM province WHERE id_province=:lokasi_provinsi';
                                  $q = $pdo->prepare($sql);
                                  $q->execute(array(':lokasi_provinsi'=>$data_detail['lokasi_provinsi']));
                                  $provinsi_lowongan = $q->fetch(PDO::FETCH_ASSOC);

                                  $sql = 'SELECT * FROM city WHERE id_city=:lokasi_kota';
                                  $q = $pdo->prepare($sql);
                                  $q->execute(array(':lokasi_kota'=>$data_detail['lokasi_kota']));
                                  $kota_lowongan = $q->fetch(PDO::FETCH_ASSOC);
                                ?>
                                  <td>
                                    <a class="ui teal button atur" onclick="diatur(<?=$res['id_lowongan']?>,'<?=$res['judul_lowongan']?>','<?=$data_detail['topik_lowongan']?>','<?=$data_detail['deskripsi_lowongan']?>','<?=$data_detail['divisi']?>',[<?=$provinsi_lowongan['id_province']?>,'<?=$provinsi_lowongan['province_name']?>'],[<?=$kota_lowongan['id_city']?>,'<?=$kota_lowongan['city_name']?>'],'<?=$data_detail['gaji']?>',['<?=$tipe_lowongan['type_alias']?>','<?=$tipe_lowongan['type_name']?>'],['<?=$kategori_lowongan['kategori_alias']?>','<?=$kategori_lowongan['kategori_name']?>'],'<?=$res['deadline']?>')">Atur Lowongan</a>

                                    <a class="ui red button delete" onclick="dihapus(<?=$res['id_lowongan']?>,'<?=$res['judul_lowongan']?>')">Hapus</a>
                                  </td>

                                <?php
                                    //aktif
                                  }
                                ?>
                            </tr>
                          <?php
                              }
                            }
                            else{ 
                          ?>
                            <tr>
                                <td colspan="4" class="ui center aligned">
                                    <p>Sepertinya Perusahaan Anda Belum Memberikan Lowongan Kerja</p>
                                </td>
                            </tr>
                          <?php
                            }
                              Database::disconnect();
                          ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">
                                    <?php
                                      if($id_lembaga == 9999){
                                        ?>
                                       <a class="ui right floated teal button add-lowongan disabled">Tambahkan Lowongan Kerja</a>
                                    <?php
                                      }
                                      else{
                                        ?>
                                      <a class="ui right floated teal button add-lowongan">Tambahkan Lowongan Kerja</a>
                                    <?php
                                      }
                                    ?>
                                    
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 

<div class="ui modal add-lowongan">
    <i class="close icon"></i>
    <div class="header">
        Tambahkan Lowongan Kerja
    </div>
    <div class="content">
        <form id="add-lowongan-form" name="add" class="ui form" method='POST'>
            <input name="action" type="hidden" value="" id='add-action'>
            <div class="field">
                <label>Judul Lowongan Kerja</label>
                <div class="ui left icon input">
                    <i class="bookmark icon"></i>
                    <input type="text" name="judul" placeholder="Judul Lowongan Kerja">
                </div>
            </div>
            <div class="field">
                <label>Topik Lowongan Kerja</label>
                <div class="ui left icon input">
                    <i class="book icon"></i>
                    <input type="text" name="topik" placeholder="Topik Lowongan Kerja">
                </div>
            </div>
            <div class="field">
                <label>Deskripsi</label>
                <div class="ui left icon input">
                    <textarea type="text" name="jobdesc" placeholder="Deskripsi Pekerjaan"></textarea>
                </div>
            </div>
            <div class="field">
                <label>Divisi Pekerjaan</label>
                <div class="ui left icon input">
                    <i class="text file code outline icon"></i>
                    <input type="text" name="divisi" placeholder="Divisi Pekerjaan">
                </div>
            </div>
            <div class="field">
                <label>Lokasi Provinsi</label>
                <div class="ui selection dropdown">
                      <i class="text marker icon"></i>
                      <input type="hidden" name="provinsi">
                      <i class="dropdown icon"></i>

                      <div class="default text">Lokasi Provinsi</div>
                      <div class="menu">
                          <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT id_province,province_name FROM province';
                              $q = $pdo->prepare($sql);
                              $q->execute();
                              $result = $q->fetchAll();

                              foreach ($result as $res) {
                            ?>
                            <div class="item" data-value="<?=$res['id_province']?>"><?=$res['province_name']?></div>
                          <?php
                              }

                              Database::disconnect();
                          ?>
                        </div>
              </div>
            </div>
            <div class="field">
                <label>Lokasi Kota/Kabupaten</label>
                <div class="ui selection dropdown">
                      <i class="text marker icon"></i>
                      <input type="hidden" name="kota">
                      <i class="dropdown icon"></i>

                      <div class="default text">Lokasi Kota/Kabupaten</div>
                      <div class="menu">
                          <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT id_city,city_name FROM city';
                              $q = $pdo->prepare($sql);
                              $q->execute();
                              $result = $q->fetchAll();

                              foreach ($result as $res) {
                            ?>
                            <div class="item" data-value="<?=$res['id_city']?>"><?=$res['city_name']?></div>
                          <?php
                              }

                              Database::disconnect();
                          ?>
                        </div>
              </div>
            </div>
            <div class="field">
                <label>Gaji</label>
                <div class="ui left icon input">
                    <i class="text dollar icon"></i>
                    <input type="text" name="gaji" placeholder="Gaji">
                </div>
            </div>
            <div class="field">
                <label>Tipe Pekerjaan</label>
                <div class="ui selection dropdown">
                      <i class="text tag icon"></i>
                      <input type="hidden" name="tipe">
                      <i class="dropdown icon"></i>

                      <div class="default text">Tipe Pekerjaan</div>
                      <div class="menu">
                          <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT type_alias, type_name FROM job_type';
                              $q = $pdo->prepare($sql);
                              $q->execute();
                              $result = $q->fetchAll();

                              foreach ($result as $res) {
                            ?>
                            <div class="item" data-value="<?=$res['type_alias']?>"><?=$res['type_name']?></div>
                          <?php
                              }

                              Database::disconnect();
                          ?>
                        </div>
              </div>
            </div>
            <div class="field">
                <label>Kategori</label>
                  <div class="ui selection dropdown">
                      <i class="text tags icon"></i>
                      <input type="hidden" name="kategori">
                      <i class="dropdown icon"></i>

                      <div class="default text">Kategori Pekerjaan</div>
                      <div class="menu">
                        <?php
                            $pdo = Database::connect();
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = 'SELECT kategori_alias,kategori_name FROM job_category';
                            $q = $pdo->prepare($sql);
                            $q->execute();
                            $result = $q->fetchAll();

                            foreach ($result as $res) {
                          ?>
                          <div class="item kategori" data-value="<?=$res['kategori_alias']?>"><?=$res['kategori_name']?></div>
                        <?php
                            }
                            Database::disconnect();
                        ?>
                      <div class="item kategori" data-value="other">Lainnya</div>
                      </div>
                  </div>
            </div>
            <div class="field">
                <label>Deadline</label>
                <div class="ui calendar" id="deadline">
                  <div class="ui left icon input">
                      <i class="text calendar icon"></i>
                      <input type="text" name="deadline" placeholder="Deadline">
                  </div>
                </div>
            </div>
        </form>
    </div>
    <div class="actions">
        <div class="ui red cancel button">
            Batal
        </div>
        <div class="ui teal right labeled icon button setlowongan" onclick="setLowongan()">
            Tambahkan
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>

<div class="ui modal set-lowongan">
    <i class="close icon"></i>
    <div class="header">
        Atur Lowongan Kerja
    </div>
    <div class="content">
        <form id="reset-lowongan-form" class="ui form" method='POST'>
            <input name="lowonganId" type="hidden" value="" id='lowonganId'>
            <input name="action" type="hidden" value="" id='reset-action'>
            <div class="field">
                <label>Judul Lowongan Kerja</label>
                <div class="ui left icon input">
                    <i class="bookmark icon"></i>
                    <input type="text" name="judul" id="judul-reset" value="">
                </div>
            </div>
            <div class="field">
                <label>Topik Lowongan Kerja</label>
                <div class="ui left icon input">
                    <i class="book icon"></i>
                    <input type="text" name="topik" id="topik-reset">
                </div>
            </div>
            <div class="field">
                <label>Deskripsi</label>
                <div class="ui left icon input">
                    <textarea type="text" name="jobdesc" id="deskripsi-reset"></textarea>
                </div>
            </div>
            <div class="field">
                <label>Divisi Pekerjaan</label>
                <div class="ui left icon input">
                    <i class="text file code outline icon"></i>
                    <input type="text" name="divisi" id="divisi-reset">
                </div>
            </div>
            <div class="field">
                <label>Lokasi Provinsi</label>
                <div class="ui selection dropdown provinsi">
                      <i class="text marker icon"></i>
                      <input type="hidden" name="provinsi" id="provinsi-reset">
                      <i class="dropdown icon"></i>

                      <div class="text">Lokasi Provinsi</div>
                      <div class="menu">
                          <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT id_province,province_name FROM province';
                              $q = $pdo->prepare($sql);
                              $q->execute();
                              $result = $q->fetchAll();

                              foreach ($result as $res) {
                            ?>
                            <div class="item" data-value="<?=$res['id_province']?>"><?=$res['province_name']?></div>
                          <?php
                              }

                              Database::disconnect();
                          ?>
                        </div>
              </div>
            </div>
            <div class="field">
                <label>Lokasi Kota/Kabupaten</label>
                <div class="ui selection dropdown kota">
                      <i class="text marker icon"></i>
                      <input type="hidden" name="kota" id="kota-reset">
                      <i class="dropdown icon"></i>

                      <div class="default text">Lokasi Kota/Kabupaten</div>
                      <div class="menu">
                          <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT id_city,city_name FROM city';
                              $q = $pdo->prepare($sql);
                              $q->execute();
                              $result = $q->fetchAll();

                              foreach ($result as $res) {
                            ?>
                            <div class="item" data-value="<?=$res['id_city']?>"><?=$res['city_name']?></div>
                          <?php
                              }

                              Database::disconnect();
                          ?>
                        </div>
              </div>
            </div>
            <div class="field">
                <label>Gaji</label>
                <div class="ui left icon input">
                    <i class="text dollar icon"></i>
                    <input type="text" name="gaji" id="gaji-reset">
                </div>
            </div>
            <div class="field">
                <label>Tipe Pekerjaan</label>
                <div class="ui selection dropdown tipe">
                      <i class="text tag icon"></i>
                      <input type="hidden" name="tipe" id="tipe-reset">
                      <i class="dropdown icon"></i>

                      <div class="text">Tipe Pekerjaan</div>
                      <div class="menu">
                          <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT type_alias, type_name FROM job_type';
                              $q = $pdo->prepare($sql);
                              $q->execute();
                              $result = $q->fetchAll();

                              foreach ($result as $res) {
                            ?>
                            <div class="item" data-value="<?=$res['type_alias']?>"><?=$res['type_name']?></div>
                          <?php
                              }

                              Database::disconnect();
                          ?>
                        </div>
              </div>
            </div>
            <div class="field">
                <label>Kategori</label>
                  <div class="ui selection dropdown kategori">
                      <i class="text tags icon"></i>
                      <input type="hidden" name="kategori" id="kategori-reset">
                      <i class="dropdown icon"></i>

                      <div class="default text">Kategori Pekerjaan</div>
                      <div class="menu">
                        <?php
                            $pdo = Database::connect();
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = 'SELECT kategori_alias,kategori_name FROM job_category';
                            $q = $pdo->prepare($sql);
                            $q->execute();
                            $result = $q->fetchAll();

                            foreach ($result as $res) {
                          ?>
                          <div class="item kategori" data-value="<?=$res['kategori_alias']?>"><?=$res['kategori_name']?></div>
                        <?php
                            }
                            Database::disconnect();
                        ?>
                      <div class="item kategori" data-value="other">Lainnya</div>
                      </div>
                  </div>
            </div>
            <div class="field">
                <label>Deadline</label>
                <div class="ui calendar" id="newdeadline">
                  <div class="ui left icon input">
                      <i class="text calendar icon"></i>
                      <input type="text" name="deadline"  id="deadline-reset">
                  </div>
                </div>
            </div>
        </form>
    </div>
    <div class="actions">
        <div class="ui red cancel button">
            Batal
        </div>
        <div class="ui teal right labeled icon button resetLowongan" onclick="resetLowongan()">
            Atur
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>

<div class="ui deletelowongan modal">
    <i class="close icon"></i>
    <div class="header">
        Delete Lowongan Kerja
    </div>
    <div class="content">
        <h3 class="ui header" id="judul-delete"></h3>
        <p>Lowongan Kerja dengan judul diatas akan dihapus dan tidak dapat ditampilkan kembali.</p>

    </div>
    <div class="actions">
        <form class="ui form deleteuser" method="POST" action="">
            <input type="hidden" name="lowonganId" id="lowonganId-delete">
            <input type="hidden" name="action" value="delete">
            <button class="ui red cancel button">Batal</button>
            <button class="ui teal ok button deactive" type="submit" name="deactivate" value="deactivate">Ya</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    $('.ui.modal.add-lowongan')
        .modal({closable:false})
        .modal('attach events', '.ui.teal.button.add-lowongan', 'show')
        .modal('setting', 'transition', 'fade up');

   $('.ui.modal.set-lowongan')    
        .modal({closable:false})
        .modal('attach events', '.ui.teal.button.atur', 'show')
        .modal('setting', 'transition', 'fade up');

  $('.ui.modal.deletelowongan')    
        .modal({closable:false})
        .modal('attach events', '.ui.red.button.delete', 'show')
        .modal('setting', 'transition', 'fade up');
    
  $('.message .close')
    .on('click', function() {
      $(this)
        .closest('.message')
        .transition('fade')
      ;
    })
  ;
    function setLowongan(){
      var form = document.getElementById("add-lowongan-form");
      document.getElementById("add-action").value = "add";
      form.submit();
    }

    function resetLowongan(){
      var form = document.getElementById('reset-lowongan-form');
      document.getElementById("reset-action").value = "reset";
      form.submit();
    }

    $('#deadline').calendar({
      type:'date',
      monthFirst: false,
      formatter:{
        date: function(date, settings){
          if(!date) return '';
          var day = date.getDate() + '';
          if (day.length < 2) {
              day = '0' + day;
          }
          var month = (date.getMonth() + 1) + '';
          if (month.length < 2) {
              month = '0' + month;
          }
          var year = date.getFullYear();
          return year+'-'+month+'-'+day;
        }
      },
    });

    $('#newdeadline').calendar({
      type:'date',
      monthFirst: false,
      formatter:{
        date: function(date, settings){
          if(!date) return '';
          var day = date.getDate() + '';
          if (day.length < 2) {
              day = '0' + day;
          }
          var month = (date.getMonth() + 1) + '';
          if (month.length < 2) {
              month = '0' + month;
          }
          var year = date.getFullYear();
          return year+'-'+month+'-'+day;
        }
      },
    });

    function diatur(id,judul,topik,deskripsi,divisi,provinsi,kota,gaji,tipe,kategori,deadline) {
      document.getElementById('lowonganId').value = id;
      document.getElementById('judul-reset').value = judul;
      document.getElementById('topik-reset').value = topik;
      document.getElementById('deskripsi-reset').value = deskripsi;
      document.getElementById('divisi-reset').value = divisi;
      $(".ui.dropdown.provinsi").dropdown("set selected",provinsi[1]);
      $(".ui.dropdown.kota").dropdown("set selected",kota[1]);
      document.getElementById('gaji-reset').value = gaji;
      $(".ui.dropdown.tipe").dropdown("set selected",tipe[1]);
      $(".ui.dropdown.kategori").dropdown("set selected",kategori[1]);
      document.getElementById('deadline-reset').value = deadline;
    }

    function dihapus(id,judul){
      document.getElementById('judul-delete').innerHTML = judul;
      document.getElementById('lowonganId-delete').value = id;

    }

</script>