
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
                                <th>Company</th>
                                <th>Atur</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                              $pdo = Database::connect();
                              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              $sql = 'SELECT * FROM lowongan_kerja';
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

                                  $sql = 'SELECT nama_lembaga FROM lembaga WHERE id_lembaga=:id_lembaga';
                                  $q = $pdo->prepare($sql);
                                  $q->execute(array(':id_lembaga'=>$res['id_lembaga']));
                                  $lembaga_name = $q->fetchColumn();
                                ?>
                                  <td>
                                      <?=$lembaga_name?>
                                  </td>
                                  <td>
                                    <a class="ui teal button delete" onclick="dihapus(<?=$res['id_lowongan']?>,'<?=$res['judul_lowongan']?>')">Detail</a>
                                    <a class="ui yellow button warning" onclick="dihapus(<?=$res['id_lowongan']?>,'<?=$res['judul_lowongan']?>')">Peringatan</a>
                                    <a class="ui red button banned" onclick="dihapus(<?=$res['id_lowongan']?>,'<?=$res['judul_lowongan']?>')">Banned</a>
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

                          ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 
