<?php
  
    function validatePhone($string) {
        $numbersOnly = preg_replace("[^0-9]", "", $string);
        $numberOfDigits = strlen($numbersOnly);
        if ($numberOfDigits <=12) {
            return true;
        } else {
            return false;
        }
    }

  if(isset($_POST['name'])){
    $id_lowongan = $_POST['lowongan'];
    $id_lembaga = $_POST['lembaga'];
    $fullname = $_POST['name'];
    $email = $_POST['email'];
    $nomor_hp = $_POST['nomor_hp'];
    $name = explode("@",$email)[0];

    $cvFile = $_FILES['cv']['name'];
    $cv_tmpDir = $_FILES['cv']['tmp_name'];
    $cvSize = $_FILES['cv']['size'];

    $fotoFile = $_FILES['foto']['name'];
    $foto_tmpDir = $_FILES['foto']['tmp_name'];
    $fotoSize = $_FILES['foto']['size'];

    $idFile = $_FILES['identitas']['name'];
    $id_tmpDir = $_FILES['identitas']['tmp_name'];
    $idSize = $_FILES['identitas']['size'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errMsgEmail = "Format Email tidak sesuai"; 

      ?>
       <div class="ui container" style="margin-top: 1em;">
            <div class="ui error message">
                <p><?=$errMsgEmail?></p>
            </div>
        </div>
    <?php
    }
    else if(!validatePhone($nomor_hp)){
        $errMsgPhone = "Format Nomor HP tidak sesuai"; 
    ?>
       <div class="ui container" style="margin-top: 1em;">
            <div class="ui error message">
                <p><?=$errMsgPhone?></p>
            </div>
        </div>
    <?php
    }
    else{
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = 'SELECT email FROM pengajuan_cv WHERE email=:email AND id_lowongan=:id_lowongan';
      $q = $pdo->prepare($sql);
      $q->execute(array(':email'=>$email,'id_lowongan'=>$id_lowongan));
      $row = $q->fetch(PDO::FETCH_ASSOC);

      if($row['email']==$email) {
          $errMsg = "Pengajuan CV GAGAL ! <br>Anda telah mengajukan CV pada lowongan kerja tersebut !";
          Database::disconnect();
          ?>
          <div class="ui container" style="margin-top: 1em;">
              <div class="ui error message">
                  <p><?=$errMsg?></p>
              </div>
          </div>
      <?php
      }
      else{
        if($cvFile){
          $upload_dir = 'assets/upload/cv/';
          $cvExt = strtolower(pathinfo($cvFile,PATHINFO_EXTENSION));
          $valid_extensions = array('pdf');
          $cvfilename = $name.'_'.$id_lowongan.".".$cvExt;

          if(in_array($cvExt, $valid_extensions)){
            if($cvSize < 1000000){
              move_uploaded_file($cv_tmpDir, $upload_dir.$cvfilename);
              $cvUrl = $upload_dir.$cvfilename;
            }
            else{
              $errMsg = true;
              $errMsgCV = "Ukuran File CV terlalu besar";
            }
          }
        }
        else{
          $errMsg= true;
          $errMsgCV = "Format File CV tidak sesuai";
        }

        if($fotoFile){
          $upload_dir = 'assets/upload/foto/';
          $fotoExt = strtolower(pathinfo($fotoFile,PATHINFO_EXTENSION));
          $valid_extensions = array('jpeg','jpg','png');
          $fotofilename = $name.'_'.$id_lowongan.".".$fotoExt;

          if(in_array($fotoExt, $valid_extensions)){
            if($fotoSize < 2000000){
              move_uploaded_file($foto_tmpDir, $upload_dir.$fotofilename);
              $fotoUrl = $upload_dir.$fotofilename;
            }
            else{
              $errMsg = true;
              $errMsgFoto = "Ukuran File Foto terlalu besar";
            }
          }
        }
        else{
          $errMsg = true;
          $errMsgFoto = "Format File Foto tidak sesuai";
        }

        if($idFile){
          $upload_dir = 'assets/upload/identitas/';
          $idExt = strtolower(pathinfo($idFile,PATHINFO_EXTENSION));
          $valid_extensions = array('jpeg','jpg','png');
          $idfilename = $name.'_'.$id_lowongan.".".$idExt;

          if(in_array($idExt, $valid_extensions)){
            if($idSize < 2000000){
              move_uploaded_file($id_tmpDir, $upload_dir.$idfilename);
              $idUrl = $upload_dir.$idfilename;
            }
            else{
              $errMsg = true;
              $errMsgId = "Ukuran File Identitas terlalu besar";

            }
          }
        }
        else{
          $errMsg = true;
          $errMsgId = "Format File Identitas tidak sesuai";

        }

        if(!isset($errMsg)){
         $pdo = Database::connect();
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = 'INSERT INTO pengajuan_cv  (id_lowongan,nama_lengkap,email,nomor_hp,file_cv,file_identitas,file_foto) VALUES (?,?,?,?,?,?,?)';
          $q = $pdo->prepare($sql);
          $q->execute(array($id_lowongan,$fullname,$email,$nomor_hp,$cvUrl,$idUrl,$fotoUrl));

          $sql = "UPDATE lowongan_kerja set total_cv = total_cv + 1 WHERE id_lowongan =$id_lowongan";
          $q = $pdo->prepare($sql);
          $q->execute();
          echo '<div class="ui container" style="margin-top: 1em;">
                  <div class="ui success message">
                      <p>Terima Kasih telah mengupload CV Anda. <br>Informasi akan dikirimkan melalui email setelah anda dinyatakan sesuai dengan persyaratan.</p>
                  </div>
              </div>';
        }
        else{
          if($errMsgCV){
        ?>
            <div class="ui container" style="margin-top: 1em;">
                <div class="ui error message">
                    <p><?php echo $errMsgCV; ?></p>
                </div>
            </div>
        <?php
          }
          if($errMsgFoto){
        ?>
            <div class="ui container" style="margin-top: 1em;">
                <div class="ui error message">
                    <p><?php echo $errMsgFoto; ?></p>
                </div>
            </div>
        <?php
          }
          if($errMsgId){
        ?>
            <div class="ui container" style="margin-top: 1em;">
                <div class="ui error message">
                    <p><?php echo $errMsgId; ?></p>
                </div>
            </div>
        <?php
          }
        }
      Database::disconnect();

      }  
    } 
    
  }



  if(!empty($_GET['q'])){
    $id_lowongan = $_GET['q'];
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM lowongan_kerja WHERE id_lowongan=:id_lowongan';
    $q = $pdo->prepare($sql);
    $q->execute(array(':id_lowongan'=>$id_lowongan));
    $data = $q->fetch(PDO::FETCH_ASSOC); 

    $sql = 'SELECT * FROM detail_lowongan_kerja WHERE id_lowongan=:id_lowongan';
    $q = $pdo->prepare($sql);
    $q->execute(array(':id_lowongan'=>$id_lowongan));
    $data_detail = $q->fetch(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM job_category WHERE kategori_alias=:kategori_lowongan';
    $q = $pdo->prepare($sql);
    $q->execute(array(':kategori_lowongan'=>$data_detail['kategori_lowongan']));
    $kategori_lowongan = $q->fetch(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM job_type WHERE type_alias=:type_alias';
    $q = $pdo->prepare($sql);
    $q->execute(array(':type_alias'=>$data_detail['tipe_lowongan']));
    $tipe_lowongan = $q->fetch(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM city WHERE id_city=:lokasi_kota';
    $q = $pdo->prepare($sql);
    $q->execute(array(':lokasi_kota'=>$data_detail['lokasi_kota']));
    $lokasi_lowongan = $q->fetch(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM lembaga WHERE id_lembaga=:id_lembaga';
    $q = $pdo->prepare($sql);
    $q->execute(array(':id_lembaga'=>$data['id_lembaga']));
    $info_lembaga = $q->fetch(PDO::FETCH_ASSOC);

    Database::disconnect();
?>

<!-- Kategori & List barang -->
<section class="category-top-section" style="margin-top:2vh">
  <!-- Breadcrumb -->
    <section class="breadcrumb-section" style="margin-bottom: 2vh">
        <div class="ui container">
            <div class="ui breadcrumb">
                <a class="section" href="?=home">Home</a>
                <div class="divider"> / </div>
                <a class="section">Kategori</a>
                <div class="divider"> / </div>
                <div class="active section"><?=$kategori_lowongan['kategori_name']?></div>
            </div>
        </div>
    </section>

    <div class="ui main container">
      <h1 class="ui header"><?=$data['judul_lowongan']?></h1>
      <a class="ui tag label"><i class="bookmark icon"></i><?=$tipe_lowongan['type_name']?></a>
      <a class="ui red tag label"><i class="marker icon"></i><?=$lokasi_lowongan['city_name']?></a>
      <a class="ui blue tag label"><i class="dollar icon"></i><?=$data_detail['gaji']?></a>
      <a class="ui black tag label">
        <i class="calendar icon"></i>
        20 Jan 2017</a>

      <img class="ui centered medium image" src="assets/img/gravity.png">
      
      <div class="fourteen wide centered column">
        <h1 class="ui icon header">TOPIK</h1>
        <p><?=$data_detail['topik_lowongan']?></p>

        <div class="nine wide column">
            <div class="ui padded segment square" id="description-pekerjaan">
                  <div class="ui middle aligned list">
                      <div class="item">
                          <div class="content">
                              <h2>Deskripsi</h2>
                          </div>
                      </div>
                  
                  </div>
                  <div class="ui top attached tabular three item menu">
                      <a class="item active" data-tab="sekilas">Sekilas</a>
                      <a class="item" data-tab="deskripsi">Deskripsi Job</a>
                      <a class="item" data-tab="informasi">Informasi</a>
                  </div>
                  <div class="ui bottom attached tab segment active" id="height-custom" data-tab="sekilas">
                      <h3><b><?=$info_lembaga['nama_lembaga']?></b></h3>
                      <?=$info_lembaga['deskripsi']?>
                  </div>

                  <div class="ui bottom attached tab segment" id="height-custom" data-tab="deskripsi">
                      <?=$data_detail['deskripsi_lowongan']?>
                  </div>

                  <div class="ui bottom attached tab segment" id="table-info-custom" data-tab="informasi">
                    <table class="ui striped table">
                      <tbody>
                        <tr>
                          <td class="collapsing">
                             <b>Divisi</b>
                          </td>
                          <td><?=$data_detail['divisi']?></td>
                        </tr>
                        <tr>
                          <td>
                             <b>Tipe Job</b>
                          </td>
                          <td><?=$tipe_lowongan['type_name']?></td>
                        </tr>
                        <tr>
                          <td>
                             <b>Gaji</b>
                          </td>
                          <td>Rp. <?=$data_detail['gaji']?></td>
                        </tr>
                        <tr>
                          <td>
                            <b>Deadline</b>
                          </td>
                          <td><?=$data['deadline']?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                 
                  <div class="ui stackable equal width grid">
                      <div class="column">
                          <button class="ui fluid primary submit-cv button ">Ajukan CV</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>

    </div>
</section>

<div class="ui modal add-cv">
  <i class="close icon"></i>
  <div class="header">
    Form Pengajuan CV
  </div>
  <div class="image content">
    <div class="ui medium image">
      <img src="assets/img/gravity.png">
    </div>
    <div class="description">

      <div class="ui header">Informasi</div>
      <form class="ui form" id="pengajuan-CV" method="post" enctype="multipart/form-data">
      <table class="ui table add-cv">
        <tbody>
          <tr>
            <td class="collapsing">
               <b>Lowongan Kerja</b>
            </td>
            <td><?=$data['judul_lowongan']?></td>
          </tr>
          <tr>
            <td>
               <b>Tipe Job</b>
            </td>
            <td><?=$tipe_lowongan['type_name']?></td>
          </tr>
          <tr>
            <td>
               <b>Nama Lengkap</b>
            </td>
            <td>
              <div class="field">
              <input class="ui input" type="text" required name="name" placeholder="Nama Lengkap" style="text-transform: capitalize;" >
              </div>
            </td>
          </tr>
          <tr>
            <td>
               <b>Email</b>
            </td>
            <td>
              <div class="field">
              <input class="ui input" type="email" required name="email" placeholder="email@domain.com" >
              </div>
            </td>
          </tr>
          <tr>
            <td>
               <b>Nomor HP</b>
            </td>
            <td>
              <div class="field">
              <input class="ui input" type="text" required name="nomor_hp" placeholder="08xxxx" >
              </div>
            </td>
          </tr>
          <tr>
            <td>
               <b>Unggah CV</b>
            </td>
            <td>
              <input type="file" required name="cv" accept="application/pdf">
            </td>
          </tr>
          <tr>
            <td>
              <b>Unggah Foto</b>
            </td>
            <td>
              <input type="file" required name="foto" accept="image/*">
            </td>
          </tr>
          <tr>
            <td>
              <b>Unggah KTM</b>
            </td>
            <td>
              <input type="file" required name="identitas" accept="image/*">
            </td>
          </tr>
        </tbody>
      </table>
      <input name="lembaga" type="hidden" value="<?=$data['id_lembaga']?>">
      <input name="lowongan" type="hidden" value="<?=$data['id_lowongan']?>">

      </form>
      
    </div>
  </div>
  <div class="actions">
    <div class="ui black deny button">
      Batal
    </div>
    <div class="ui positive right labeled icon button" onclick="submitcv()">
      Kirim
      <i class="checkmark icon"></i>
    </div>
  </div>
</div>
<?php
}
else{
  include 'handler/404.php';

}
?>
<script type="text/javascript">
$('.ui.dropdown')
  .dropdown()
;

$('.menu .item').tab();
$('.ui.modal.add-cv')
  .modal({closable:false})
  .modal('attach events','.ui.submit-cv.button','show')
  .modal('setting','transition','fade up')
;

function submitcv(){
    var form = document.getElementById('pengajuan-CV');
    form.submit();
}
</script>