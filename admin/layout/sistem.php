<div class="ui vertical stripe computes container" style="padding-top:5vh">
    <div class="ui stackable grid container">
        <div class="column">
            <h3 class="ui header">Manajemen Sistem</h3>
        </div>
    </div>

    <div class="ui stackable grid container">
        </br>
        <h2>Tinjauan Saat Ini</h2>
        <div class="ui stackable grid container">
            <div class="four wide column">
                <table class="ui teal table">
                    <tbody>
                        <tr>
                            <td>Jumlah Mitra Terdaftar</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>Jumlah Mitra Pending</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>Jumlah Lowongan Kerja</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>Jumlah Lowongan Kerja <br>Tidak Valid</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="twelve wide column">
                <table class="ui teal fixed table">
                    <thead class="ui center aligned">
                        <tr>
                            <th>Mitra</th>
                            <th>Lowongan Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <canvas id="mitra_chart"></canvas>
                            </td>
                            <td>
                                <canvas id="lowongan_chart"></canvas>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

         <div class="ui stackable grid container">
            <div class="four wide column">
                <table class="ui teal table">
                    <tbody>
                        <tr>
                            <td>Jumlah CV valid</td>
                            <td>4</td>
                        </tr>
                         <tr>
                            <td>Jumlah CV tidak valid</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>Jumlah Kandidat Pegawai Diterima</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>Jumlah Kandidat Pegawai Tidak Diterima</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="twelve wide column">
                <table class="ui teal fixed table">
                    <thead class="ui center aligned">
                        <tr>
                            <th>CV Valid</th>
                            <th>Kandidat Pegawai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <canvas id="cv_chart"></canvas>
                            </td>
                            <td>
                                <canvas id="pegawai_chart"></canvas>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        </br>
        <div class="row">
            <h2>Lowongan Kerja yang Tidak Valid</h2>
            <table class="ui teal fixed table center aligned">
                <thead>
                    <th>Judul</th>
                    <th>Deadline</th>
                    <th>Lokasi</th>
                    <th>Kategori</th>
                    <th>Company</th>
                    <th>Action</th>
                </thead>
                <tbody>
                        <tr>
                            <td>Peternakan</td>
                            <td>03 Juni 2014</td>
                            <td>Jawa Timur</td>
                            <td>Data Entry</td>                  
                            <td>Sari Sapi</td>
                            <td>
                                <form id="form-deactivate-{{exists[0]}}" method='POST'>
                                <input type="hidden" id="action-deactivate" name="action" value="">
                                <input type="hidden" id="flav-id" name="flav-id" value="">
                                <button class="ui blue button" >Peringatkan</button>
                                </form>
                        
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
        </br>
        <div class="row">
            <h2>Daftar Akun Company yang ditangguhkan</h2>
            <table class="ui teal fixed table center aligned">
                <thead>
                    <th>Company</th>
                    <th>CP</th>
                    <th>Email</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr>
                        <td>Sari Roti</td>
                        <td>Rahmat</td>
                        <td>sariroti@company.id</td>

                        <td>
                            <button class="ui teal button network">Lihat Detail</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="ui modal">
    <i class="close icon"></i>
    <div class="header">
        Set default flavor
    </div>
    <div class="content">
        <form id="flavor-default" class="ui form" method="POST">
            <input type="hidden" id="modal-id" name="flavor-id" value="">
            <input type="hidden" id="modal-vcpu" name="flavor-vcpu" value="">
            <input type="hidden" id="modal-ram" name="flavor-ram" value="">
            <input type="hidden" id="modal-disk" name="flavor-disk" value="">
            <input type="hidden" id="modal-action" name="action" value="">
            <div class="field">
                <label>Name Flavor Alias</label>
                <input type="text" name="flavor-alias" placeholder="Nama Alias">
            </div>
        </form>
    </div>
    <div class="actions">
            <button class="ui red cancel button" oncliick="process('cancel')">
                Cancel
            </button>
            <button class="ui teal ok right labeled icon button" onclick="process('create')">
                Create
                <i class="checkmark icon"></i>
            </button>
    </div>
</div>

<script>
    $('.menu.computes .item')
        .tab()
        ;

    $('.ui.modal')
        .modal('attach events', '.ui.teal.button.setas', 'show')
        .modal('setting', 'transition', 'fade up');

    var mitra_pie = document.getElementById("mitra_chart").getContext("2d");
    var mitra_chart = new Chart(mitra_pie, {
        type: 'pie',
        data: {
            labels: [
                "Mitra Terdaftar",
                "Mitra Pending"
            ],
            datasets: [
                {
                    label: "%",
                    data: [2, 6],
                    backgroundColor: [
                        "#00B5AD",
                        "#A0A0A0"
                    ]
                }
            ]
        },
        options: {
            animation: {
                animateScale: true
            },
            responsive: true
        }
    });

    var lowongan_pie = document.getElementById("lowongan_chart").getContext("2d");
    var lowongan_chart = new Chart(lowongan_pie, {
        type: 'pie',
        data: {
            labels: [
                "Valid",
                "Tidak Valid"
            ],
            datasets: [
                {
                    label: 'GB',
                    data: [12, 20],
                    backgroundColor: [
                        "#00B5AD",
                        "#A0A0A0"
                    ]
                }
            ]
        },
        options: {
            animation: {
                animateScale: true
            },
            responsive: true
        }
    });

    var cv_pie = document.getElementById("cv_chart").getContext("2d");
    var cv_chart = new Chart(cv_pie, {
        type: 'pie',
        data: {
            labels: [
                "Valid",
                "Tidak Valid"
            ],
            datasets: [
                {
                    label: 'GB',
                    data: [20, 12],
                    backgroundColor: [
                        "#00B5AD",
                        "#A0A0A0"
                    ]
                }
            ]
        },
        options: {
            animation: {
                animateScale: true
            },
            responsive: true
        }
    });

    var pegawai_pie = document.getElementById("pegawai_chart").getContext("2d");
    var pegawai_chart = new Chart(pegawai_pie, {
        type: 'pie',
        data: {
            labels: [
                "Diterima",
                "Tidak Diterima"
            ],
            datasets: [
                {
                    label: 'GB',
                    data: [20, 12],
                    backgroundColor: [
                        "#00B5AD",
                        "#A0A0A0"
                    ]
                }
            ]
        },
        options: {
            animation: {
                animateScale: true
            },
            responsive: true
        }
    });

</script>
