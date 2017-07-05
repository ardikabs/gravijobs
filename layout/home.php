
<div class="ui teal inverted vertical segment" id="banner" style="min-height: 350px;padding-top:50px;">
    <div class="ui middle aligned stackable grid container">
        <div class="eight wide column">
            <div class="ui center aligned container">
                <h1 class="ui center aligned inverted header">
                Layanan Pencarian Kerja untuk Mahasiswa
                <h1 class="ui center aligned inverted header">Politeknik Elektronika Negeri Surabaya</h1>
                </br>
                <a class="ui large yellow inverted button" href="?p=lowongan">Cari Pekerjaan</a>
            </div>
        </div>
        <div class="eight wide column">
            <img class="ui small centered image" src="assets/img/gravity.png">
        </div>
    </div>
</div>
    


<div class="ui vertical stripe quote segment">
    <div class="ui equal width stackable internally celled grid">
      <div class="center aligned row">
        <div class="column">
          <h3>"Gravijobs"</h3>
          <p>Situs pertama kali dimana pencarian kerja langsung dari sumbernya</p>
        </div>
        <div class="column">
          <h3>"Saya Minum Yakult 3x Sehari"</h3>
          <p>
            <img src="assets/img/avatar.jpg" class="ui avatar image"> <b>Ardika</b> Bs - Chief Technology Officer Gravicode
          </p>
        </div>
      </div>
    </div>
</div>

<div class="ui vertical stripe info segment">
    <div class="ui centered page grid" style="padding-top:5vh;padding-bottom:5vh">
        <div class="fourteen wide column">
            <div class="ui three column center aligned stackable grid">
                <div class="column">
                    <img class="ui small centered image" src="assets/img/cloud-computing.png">
                    <h1 class="ui header">Mudah Diakses</h1>
                </div>
                <div class="column">
                    <img class="ui small centered image" src="assets/img/list.png">
                    <h1 class="ui header">Daftar Pekerjaan Terupdate</h1>
                </div>
                <div class="column">
                     <img class="ui small centered image" src="assets/img/scaleup.png">
                    <h1 class="ui header">Sumber Terpercaya</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ui vertical stripe segment">
    <div class="ui text container center aligned">
        <h1 class="ui header">Mitra Kerja</h1>
        <p>Banyak mitra kerja yang telah bergabung bersama kami</p>
    </div>

    <div class="ui container" style="padding-top:2vh">
        <div class="ui fourteen wide column">
            <div class="column">
                <div class="ui container center aligned">
                    <div class="ui tiny images">
                        <img class="ui image logo" src="assets/img/gravity.png">
                        <img class="ui image logo" src="assets/img/gravity.png">
                        <img class="ui image logo" src="assets/img/gravity.png">
                        <img class="ui image logo" src="assets/img/gravity.png">
                        <img class="ui image logo" src="assets/img/gravity.png">
                        <img class="ui image logo" src="assets/img/gravity.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="ui text container center aligned">
    <h1 class="ui header">Cari Pekerjaan Terdekat dengan Anda</h1>
    <p>Banyak Pekerjaan Tersedia disekitar anda</p>
</div>    
<div id="googleMap" ></div>            

<div class="ui vertical stripe segment">
<div class="ui divider"></div>
    <div class="ui container">
        <div class="segment ">
            <div class="ui centered text container">
                    <div class="ui center aligned header"></div>
            </div>

            <div class="ui equal width padded grid" >

                    <div class="column">
                        <div class="ui header">
                            <i class="grey server icon"></i>
                            <div class="content">1.234
                                <div class="sub header">Jumlah Pekerjaan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <div class="ui header">
                            <i class="grey dollar icon"></i>
                            <div class="content">123
                                <div class="sub header">Jumlah Mitra Terdaftar
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column">
                        <div class="ui header">
                            <i class="grey user icon"></i>
                            <div class="content">1.234
                                <div class="sub header">Jumlah Kandidat Diterima
                                </div>
                            </div>
                        </div>
                    </div>

            </div>


        </div>
    </div>
</div>

<script>
      function initMap() { 
        var surabaya = {lat: -7.276451, lng: 112.794166};
        var map = new google.maps.Map(document.getElementById('googleMap'), {
          zoom: 15,
          center: surabaya
        });
        var marker = new google.maps.Marker({
          position: surabaya,
          map: map
        });
      }
    </script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrUIokfRmfo0uia86H1NTfV9UfqQFKpTI&callback=initMap"></script>