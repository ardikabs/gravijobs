<!-- Kategori & List barang -->
<?php
  $pdo = Database::connect();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  if(isset($_GET['k'])){
   
    $sqlproses = 'SELECT id_lowongan FROM detail_lowongan_kerja WHERE kategori_lowongan = "'.$_GET['k'].'"';
    $q = $pdo->prepare($sqlproses);
    $q->execute();
    $result_from_get= $q->fetchAll(PDO::FETCH_ASSOC);
    $arr = array();
    foreach ($result_from_get as $res) {
      array_push($arr,$res['id_lowongan']);
    }
    $ids = join(',',$arr);
    $sqllowongan = "SELECT * FROM lowongan_kerja WHERE id_lowongan in ($ids)";
    $activekategori = $_GET['k'];
    
  }
  else{
    $result_from_get= "init";
    $sqllowongan = "SELECT * FROM lowongan_kerja";
  }
?>
<section class="category-top-section" style="margin-top:5vh">
    <div class="ui container">
        <div class="search-section" style="margin-bottom: 3vh">
            <div class="ui container">
                <div class="ui segment">
                    <div class="ui fluid action input">
                      <input type="text" id="mysearchbtn" placeholder="Search..." >
                      <button class="ui icon button" type="submit" onclick="mybtn()">
                        <i class="search icon"></i>
                      </button>
                    </div>
                </div>
            </div> 
        </div>
    
        <div class="ui stackable grid">
            <div class="three wide column">
                <div class="category-section">
                    <div class="ui vertical borderless menu square" id="category-lowongan">

                        <div class="item">
                            <div class="ui header">
                                Kategori
                            </div>
                        </div>
                        <?php
                            $pdo = Database::connect();
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = 'SELECT kategori_alias,kategori_name FROM job_category';
                            $q = $pdo->prepare($sql);
                            $q->execute();
                            $result = $q->fetchAll();

                            foreach ($result as $res) {
                              if(!empty($activekategori) && $activekategori == $res['kategori_alias']){
                        ?>
                                <a class="item kategori active" href="?p=lowongan&k=<?=$res['kategori_alias']?>"><?=$res['kategori_name']?></a>
                        <?php 
                              }
                              else{
                          ?>
                                <a class="item kategori" href="?p=lowongan&k=<?=$res['kategori_alias']?>"><?=$res['kategori_name']?></a>
                        <?php
                              }
                            }

                            Database::disconnect();
                        ?>
                    </div>
                </div>
                <div class="ui divider"></div>
                <div class="area-section">
                    <div class="ui vertical borderless menu square" id="area-lowongan">

                        <div class="item">
                            <div class="ui header">
                                Filter 
                            </div>
                        </div>
                        <div class="ui fluid multiple search selection dropdown wilayah">
                           <input type="hidden" name="kota">
                          <i class="dropdown icon"></i>
                          <div class="default text">Pilih Wilayah</div>
                          
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

                          <div class="ui fluid multiple search selection dropdown jenis">
                          <input type="hidden" name="jenis-pekerjaan">
                          <i class="dropdown icon"></i>
                          <div class="default text">Pilih Jenis Pekerjaan</div>
                          
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
                    
                </div>
            </div>

            <div class="thirteen wide column" id="ourcontainer">
                <div class="title-product-section">
                <section class="ui very padded segment square">
                    <div class="ui container">
                        <div class="ui divided items">
                        <?php
                          if(!empty($result_from_get)){
                              $q = $pdo->prepare($sqllowongan);
                              $q->execute();
                              $result = $q->fetchAll();

                            foreach ($result as $res) {
                            ?>
                                  <div class="item">
                                    <div class="image">
                                      <img src="assets/img/gravity.png">
                                    </div>
                                    
                                    <div class="content">
                                      <a class="header" href="?p=lowongan-detail&q=<?=$res['id_lowongan']?>"><?=$res['judul_lowongan']?></a>
                                      <div class="meta">Posted <?=date('d-m-Y',strtotime($res['created_at']))?></div>  
                                      <?php
                                        $sql = 'SELECT * FROM detail_lowongan_kerja WHERE id_lowongan=:id_lowongan';
                                        $q = $pdo->prepare($sql);
                                        $q->execute(array(':id_lowongan'=>$res['id_lowongan']));
                                        $result = $q->fetch(PDO::FETCH_ASSOC);

                                      ?>
                                      <div class="description"><?=$result['deskripsi_lowongan']?></div>
                                      <div class="extra">
                                        <div class="ui label">
                                          <i class="dollar icon"></i>
                                          <?=$result['gaji']?>
                                        </div>
                                        <div class="ui label">
                                          <i class="calendar icon"></i>
                                          <?=$res['deadline']?>
                                        </div>
                                        <div class="ui divider" ></div>
                                        <a href="?p=lowongan-detail&q=<?=$res['id_lowongan']?>">
                                            <div class="ui right floated primary button">
                                              Buka
                                              <i class="right chevron icon"></i>
                                            </div>
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                          <?php 
                            }
                          }
                          else{
                          ?>

                            <div class="item">                                    
                              <div class="ui text center aligned container">
                                  Tidak ada job tersedia
                              </div>   
                            </div>
                          <?php
                          }
                          ?>


                        </div>
                    </div>
                </section>

                </div>
            </div>

        </div>
    </div>
</section>
<?php
Database::disconnect();
?>

<script type="text/javascript">
$('.ui.dropdown')
  .dropdown()
;

var wilayah ="";
var tipe ="";
var search="";

function mybtn(){
  search=document.getElementById("mysearchbtn").value;
  $.ajax({
    url:'handler/searchlowongan.php',
    type:'post',
    data: {
      search:search
    },
    success:function(data){
        $('#ourcontainer').empty();
        $('#ourcontainer').html(data);
        $('#ourcontainer').hide();
        $('#ourcontainer').show('slow');
    }
  });
}

$(".wilayah").dropdown({
  onChange:function(val){
    wilayah = val;
    $.ajax({
      url:'handler/searchlowongan.php',
      type:'post',
      data: {
        search:search,
        filterWilayah:wilayah,
        filterTipe:tipe
      },
      success:function(data){
          $('#ourcontainer').empty();
          $('#ourcontainer').html(data);
          $('#ourcontainer').hide();
          $('#ourcontainer').show('slow');
        }
    });
    
  }
});

$(".dropdown.jenis").dropdown({
  onChange:function(val){
    tipe = val;
    $.ajax({
      url:'handler/searchlowongan.php',
      type:'post',
      data: {
        search:search,
        filterWilayah:wilayah,
        filterTipe:tipe
      },
      success:function(data){
          $('#ourcontainer').empty();
          $('#ourcontainer').html(data);
          $('#ourcontainer').hide();
          $('#ourcontainer').show('slow');
        }
    });
  }
});
</script>