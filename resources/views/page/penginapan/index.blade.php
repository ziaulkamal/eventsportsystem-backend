@extends('layout.admin')

@section('content')
<div id="form" class="col-lg-12">
<div class="card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Menambahkan Data {{ $title }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nama_penginapan" class="form-label fs-14">Nama Penginapan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nama_penginapan" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label fs-14">Nomor Telepon</label>
                                <div class="input-group">
                                    <input type="text" class="form-control notext" id="nomor_telepon" maxlength="13" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="kapasitas_penginapan" class="form-label fs-14">Kapasitas Penginapan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control notext" id="kapasitas_penginapan" maxlength="3" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="latitude" class="form-label fs-14">Latitude</label>
                                <div class="input-group">
                                    <input type="text" id="latitude" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="longitude" class="form-label fs-14">Longitude</label>
                                <div class="input-group">
                                    <input type="text" id="longitude" class="form-control" placeholder="">
                                </div>
                            </div>

                            <button class="btn btn-primary" onClick="validateAndSubmit()" type="button">Submit</button>
                        </div>
                    </div>

                </div>

            </div>
</div>

<div id="table" class="col-lg-12" style="display: none">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data {{ $title }}</h4>
        </div>
        <div class="card-body">
        <table id="example" class="table table-striped nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Penginanpan</th>
                    <th>Lokasi Google Map</th>
                    <th>Nomor Telepon</th>
                    <th>Kapasitas</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data tabel akan diisi disini -->
            </tbody>
        </table>

        </div>
    </div>
</div>
@endsection

@section('button_action')
<div class="page-btn">
    <a href="#" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Lihat Data</a>
</div>
@endsection

@section('hscript')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endsection
@section('script')
<script>
    // Memanggil fungsi initMap setelah halaman dimuat, dengan delay 300ms
    setTimeout(initMap, 300);

    function initMap() {
        // Menggunakan koordinat Calang, Aceh Jaya
        const map = L.map('map').setView([4.63785, 95.59188], 14); // Pusat Calang, Aceh Jaya

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxBounds: [[-11, 95], [6, 141]], // Batas wilayah Indonesia
            maxBoundsViscosity: 1.0
        }).addTo(map);

        // Menambahkan marker yang dapat diseret
        const marker = L.marker([4.63785, 95.59188], { draggable: true }).addTo(map)
            .bindPopup('Pilih lokasi')
            .openPopup();

        // Menangani event drag marker
        marker.on('dragend', function(event) {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        // Menangani klik pada peta untuk mengubah posisi marker
        map.on('click', function(event) {
            const lat = event.latlng.lat;
            const lng = event.latlng.lng;
            marker.setLatLng([lat, lng]);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });


    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnToggle = document.querySelector(".btn-added");
        const table = document.getElementById("table");
        const form = document.getElementById("form");

        btnToggle.addEventListener("click", function (event) {
            event.preventDefault();

            if (form.style.display !== "none") {
                form.style.display = "none";
                table.style.display = "block";
                btnToggle.innerHTML = '<i data-feather="eye" class="me-2"></i>Tambah Data';
            } else {
                form.style.display = "block";
                table.style.display = "none";
                btnToggle.innerHTML = '<i data-feather="plus-circle" class="me-2"></i>Lihat Data';
            }

            feather.replace(); // Untuk memperbarui ikon Feather
        });
    });
    function editRow(id) {
        alert(`Edit action triggered for ID: ${id}`);
        // Lakukan operasi Edit, seperti membuka form atau modal untuk mengedit data
    }

    // Fungsi untuk menangani aksi Delete
    function deleteRow(id) {
        if (confirm(`Are you sure you want to delete the record with ID: ${id}?`)) {
            alert(`Delete action triggered for ID: ${id}`);
            // Lakukan operasi Delete, seperti menghapus data dari server
        }
    }


    $(document).ready(function() {
        // Fungsi untuk menangani aksi Edit
        const table = $('#example').DataTable({
            "paging": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "/api/venue-completes",
                type: "GET",
                data: function(d) {
                    return {
                        draw: d.draw,
                        start: d.start,
                        length: d.length,
                        search: { value: d.search.value } // Kirim parameter search
                    };
                },
                dataSrc: function(json) {
                    return json.data; // Gunakan data yang dikembalikan dari backend
                },
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                    // 'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            },
            "pageLength": 10, // Jumlah data per halaman
            "columns": [
                { "data": "name" },          // Menampilkan nama lengkap
                {
                    "data": "location",
                    "render": function(data){
                        return`
                        <a class="btn btn-outline-success btn-sm" href="${data}" target="_blank">
                            <i data-feather="map-pin"></i> <span>Lihat Disini</span>
                        </a>
                        `;
                    }
                },               // Menampilkan umur
                { "data": "latitude" },            // Menampilkan berat badan
                { "data": "longitude" },             // Menampilkan tinggi badan
                {
                    "data": "status",
                    "render": function(data){
                        const status = data === 'active' ? 'badge badge-linesuccess' : 'badge badge-linedanger';
                        return`
                        <span class='${status}'>${data}</span>
                        `;
                    }
                },             // Menampilkan tinggi badan
                {
                    "data": "id", // Kolom untuk ID
                    "render": function(data, type, row) {
                        // Tombol Edit dan Delete
                        return `
                            <div class="action-buttons">
                                <button class="btn btn-primary btn-sm edit-btn" data-id="${data}" onClick="editRow('${data}')">Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${data}" onClick="deleteRow('${data}')">Hapus</button>
                            </div>
                        `;
                    }
                },
            ],

            "responsive": true, // Mengaktifkan fitur responsif
            "drawCallback": function(settings) {
                // Kode untuk memodifikasi pagination
                customPagination(settings);
            }
        });

        // Fungsi untuk membuat pagination custom
        function customPagination(settings) {
            const pageCount = Math.ceil(settings.json.recordsTotal / settings._iDisplayLength);
            const paginationContainer = $('.dataTables_paginate');
            paginationContainer.empty(); // Kosongkan kontainer pagination sebelumnya

            for (let i = 1; i <= pageCount; i++) {
                const pageButton = $('<a class="paginate_button" href="javascript:void(0);">' + i + '</a>');
                pageButton.on('click', function() {
                    const page = $(this).text() - 1;  // Halaman yang dipilih
                    table.page(page).draw('page');
                });
                paginationContainer.append(pageButton);
            }
        }

                // Fungsi validasi dan submit data
        window.validateAndSubmit = function() {
            // Clear previous alert messages
            $(".alert").hide();

            const isValid = true;
            const nama_penginapan = $('#nama_penginapan').val();
            const nomor_telepon = $('#nomor_telepon').val();
            const kapasitas_penginapan = $('#kapasitas_penginapan').val();
            const latitude = $('#latitude').val();
            const longitude = $('#longitude').val();


            // Validasi form dan tampilkan alert
            if (!nama_penginapan) {
                $('#nama_penginapan').text('Nama Penginapan harus diisi').show();
                isValid = false;
            }

            if (!nomor_telepon) {
                $('#nomor_telepon').text('Nomor Telepon harus diisi').show();
                isValid = false;
            }

            if (!kapasitas_penginapan) {
                $('#kapasitas_penginapan').text('Kapasitas penginapan harus diisi').show();
                isValid = false;
            }

            if (!latitude) {
                $('#latitude').text('Koordinat ini harus diisi').show();
                isValid = false;
            }
            if (!longitude) {
                $('#longitude').text('Koordinat ini harus diisi').show();
                isValid = false;
            }


            // Jika form valid, kirim data
            if (isValid) {
                // Siapkan data untuk dikirim
                const formData = {
                    name:nama_penginapan,
                    phoneNumber:nomor_telepon,
                    capacity:kapasitas_penginapan,
                    latitude: latitude,
                    longitude: longitude,
                    location: `https://www.google.com/maps/@${latitude},${longitude},19z`,
                };

                // Kirim data ke server menggunakan AJAX
                $.ajax({
                    url: '/api/housing', // URL untuk mengirim data
                    method: 'POST',
                    data: formData,
                    headers: {
                        'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                        // 'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Berhasil !",
                            text: "Data Penginapan Sudah Disimpan",
                            icon: "success",
                            confirmButtonClass: "btn btn-success",
                            buttonsStyling: false,
                            customClass: {
                                popup: 'custom-popup'
                            }
                        });
                        $(".alert").hide();

                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON?.message || error;
                        Swal.fire({
                            title: "Gagal!",
                            text: `Ada kesalahan saat menyimpan. Keterangan: ${errorMessage}`,
                            icon: "error",
                            confirmButtonClass: "btn btn-danger",
                            buttonsStyling: false
                        });
                    }
                });
            }
        };
    });

</script>
@endsection
