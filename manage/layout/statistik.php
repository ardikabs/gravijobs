<div class="ui vertical stripe computes container" style="padding-top:5vh">
    <div class="ui stackable grid container">
        <div class="column">
            <h3 class="ui header">Statistik Pendaftar</h3>
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
                            <td>Jumlah Calon Pegawai</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>Jumlah Lowongan Kerja</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>Jumlah CV yang Valid</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="twelve wide column">
                <table class="ui teal fixed table">
                    <thead class="ui center aligned">
                        <tr>
                            <th>Calon Peegawai</th>
                            <th>Lowongan Kerja</th>
                            <th>Jumlah CV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <canvas id="kandidat_chart"></canvas>
                            </td>
                            <td>
                                <canvas id="lowongan_chart"></canvas>
                            </td>
                            <td>
                                <canvas id="cv_chart"></canvas>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </br>
        <div class="row">
            <h2>Lowongan Kerja yang Ditampilkan</h2>
            <table class="ui teal fixed table center aligned">
                <thead>
                    <th>Pembuatan Website</th>
                    <th>Deadline</th>
                    <th>Jumlah Pendaftar</th>
                    <th>Jumlah Pendaftar Valid</th>
                    <th>Jumlah Pendaftar Tidak Valid</th>
                    <th>Action</th>
                </thead>
                <tbody>
                        <tr>
                            <td>Peternakan</td>
                            <td>03 Juni 2014</td>
                            <td>502</td>
                            <td>202</td>                  
                            <td>300</td>
                            <td>
                                <form id="form-deactivate-{{exists[0]}}" method='POST'>
                                <input type="hidden" id="action-deactivate" name="action" value="">
                                <input type="hidden" id="flav-id" name="flav-id" value="">
                                <button class="ui blue button delete" onclick="deactivate('{{exists[0]}}')">Non Aktifkan</button>
                                </form>
                        
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
        </br>
        <div class="row">
            <h2>Atur Lowongan Kerja yang Ditampilkan</h2>
            <table class="ui teal fixed table center aligned">
                <thead>
                    <th>Judul Lowongan</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr>
                        <td>Pembuatan Website</td>
                        <td>
                            <button class="ui teal button network">Tampilkan</button>
                            <i class="teal checkmark icon"></i>
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

    var kandidat_pie = document.getElementById("kandidat_chart").getContext("2d");
    var kandidat_chart = new Chart(kandidat_pie, {
        type: 'pie',
        data: {
            labels: [
                "Calon Pegawai",
                "Sisa Kuota Calon Pegawai"
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
                "Jumlah Loker",
                "Batas Jumlah Loker"
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
                "CV Valid",
                "CV Tidak Valid"
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
