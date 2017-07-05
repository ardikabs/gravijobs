
<?php
	include "../config/dbconfig.php";
	$pdo = Database::connect();
 	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if($_POST['search'] == ""){
		$sqlproses = 'SELECT * FROM lowongan_kerja';
		$result = "reload";
	}else if($_POST['search'] != ""){
      	$sqlproses = 'SELECT * FROM lowongan_kerja WHERE judul_lowongan like "%'.$_POST['search'].'%"';
		$result = "searching";
	}
	if(isset($_POST['filterWilayah']) || isset($_POST['filterTipe'])){
		if($_POST['filterWilayah'] == "" && $_POST['filterTipe'] == ""){
			$sqlproses = 'SELECT * FROM lowongan_kerja';
			$result	= "reload";
		}
		else if($_POST['filterWilayah'] != "" && $_POST['filterTipe'] != "" ){
			$sqlfilter = 'SELECT id_lowongan FROM detail_lowongan_kerja	WHERE lokasi_provinsi like "'.$_POST['filterWilayah'].'" AND tipe_lowongan like "'.$_POST['filterTipe'].'" ';
		}
		else if($_POST['filterWilayah'] != "" && $_POST['filterTipe'] == "" ){
			if(strlen($_POST['filterWilayah'])>1){
				$arr = explode(',',$_POST['filterWilayah']);
				$var = join(',',$arr);
				$sqlfilter = "SELECT id_lowongan FROM detail_lowongan_kerja	WHERE lokasi_provinsi in ($var) ";
			}
			else{
				$sqlfilter = 'SELECT id_lowongan FROM detail_lowongan_kerja	WHERE lokasi_provinsi like "'.$_POST['filterWilayah'].'" ';
			}
		}
		else if($_POST['filterWilayah'] == "" && $_POST['filterTipe'] != "" ){
			if(strlen($_POST['filterTipe'])>2){
				$arr = explode(',',$_POST['filterTipe']);
				$var = join(',',$arr);
			}
			// $sqlfilter = 'SELECT id_lowongan FROM detail_lowongan_kerja	WHERE tipe_lowongan like "'.$_POST['filterTipe'].'" ';
			$sqlfilter = 'SELECT id_lowongan FROM detail_lowongan_kerja	WHERE tipe_lowongan in ("pt") ';

		}

		if(!empty($sqlfilter)){
			$q = $pdo->prepare($sqlfilter);
		    $q->execute();
		    $result= $q->fetchAll(PDO::FETCH_ASSOC);
		    $arr = array();
		    foreach ($result as $res) {
		      array_push($arr,$res['id_lowongan']);
		    }
		    $ids = join(',',$arr);
		    $sqlproses = "SELECT * FROM lowongan_kerja WHERE id_lowongan in ($ids)";
		}	
	}
			
?>
<div class="title-product-section">
<section class="ui very padded segment square">
    <div class="ui container">
        <div class="ui divided items">
            <?php
	          if(!empty($result)){ 
	              $q = $pdo->prepare($sqlproses);
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
	                      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
            Database::disconnect();
        ?>


        </div>
    </div>
</section>

</div>