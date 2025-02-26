@extends('layout.admin')

@section('content')
<div id="form" class="col-lg-12" style="display: none;">
    <div class="card">
        <div class="card-body">
            <div id="basic-pills-wizard" class="twitter-bs-wizard">
                <!-- Tab Navigation -->
                <ul class="nav nav-pills twitter-bs-wizard-nav">
                    <li class="nav-item">
                        <a href="#data_personal" class="nav-link active" data-bs-toggle="pill">
                            <div class="step-icon" data-bs-toggle="tooltip" title="Data Personal">
                                <i class="far fa-user"></i>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#data_coach" class="nav-link" data-bs-toggle="pill">
                            <div class="step-icon" data-bs-toggle="tooltip" title="Data Coach">
                                <i class="fa-solid fa-volleyball"></i>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#data_dokumen" class="nav-link" data-bs-toggle="pill">
                            <div class="step-icon" data-bs-toggle="tooltip" title="Data Dokumen">
                                <i class="fa-regular fa-file"></i>
                            </div>
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content twitter-bs-wizard-tab-content mt-4">
                    @include('components.data_personal')
                    @include('components.data_coach')
                    @include('components.documents.nonatlet')
                </div>
            </div>
        </div>
    </div>
</div>

<div id="table" class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data {{ $title }}</h4>
        </div>
        <div class="card-body">
        <table id="example" class="table table-striped nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Cabang Olahraga</th>
                    <th>Kelas</th>
                    <th>Kategori</th>
                    <th>Venue</th>
                    <th>Jadwal</th>
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
<div class="modal fade" id="form-add-data">
    <div class="modal-dialog modal-dialog-centered custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Tambah Data Pertandingan</h4>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <div>
                            <div class="mb-3">
                                <label class="form-label">Cabang Olahraga</label>
                                <select class="form-control" id="cabang_olahraga"></select>
                                <div id="cabang_olahraga_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelas Olahraga</label>
                                <select class="form-control" id="kelas"></select>
                                <div id="kelas_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Venue</label>
                                <select class="form-control" id="venue"></select>
                                <div id="venue_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pertandingan</label>
                                <input type="text" class="form-control" id="tanggal_pertandingan" autocomplete="off">
                                <div id="tanggal_pertandingan_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                            </div>

                            <div class="modal-footer-btn">
                                <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-submit" onClick="validateAndSubmit()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('button_action')
<div class="page-btn">
    <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#form-add-data"><i data-feather="plus-circle" class="me-2"></i>Tambah Data</a>
</div>
@endsection

@section('script')

<script>

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
    flatpickr("#tanggal_pertandingan", {
        dateFormat: "d-m-Y"
    });

    $.ajax({
        url: '/api/sports',
        type: 'GET',
        headers: {
            'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
            // 'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        success: function (data) {
            // Kosongkan select dan tambahkan opsi default
            $('#cabang_olahraga').empty().append('<option selected disabled>--Pilih Cabor--</option>');

            // Iterasi data JSON dan tambahkan opsi ke select
            $.each(data, function (index, item) {
                $('#cabang_olahraga').append(new Option(item.name, item.id));
            });

            // Aktifkan select
            $('#cabang_olahraga').prop('disabled', false);
        }
    });

    $('#cabang_olahraga').change(function() {
        const cabang_olahragaId = $(this).val(); // Ambil ID cabang olahraga yang dipilih

        // Reset parrent setiap kali ada perubahan di cabang_olahraga
        $('#kelas').empty().append('<option selected disabled>--Pilih Kelas--</option>');
        $('#kelas').prop('disabled', true); // Menonaktifkan select parrent saat reset

        if (cabang_olahragaId) {
            console.log(cabang_olahragaId);

            // AJAX kedua berdasarkan cabang olahraga yang dipilih
            $.ajax({
                url: `/api/sport-parrent/${cabang_olahragaId}`,
                type: 'GET',
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`,
                    'Accept': 'application/json'
                },
                success: function (data) {
                    const gender =
                    // Iterasi data JSON dan tambahkan opsi ke select
                    $.each(data, function (index, item) {
                        // Konversi tipe ke bahasa Indonesia
                        let typeInIndonesian = item.type === "female" ? "Perempuan" : "Laki-laki";
                        let optionText = `${item.classOption} - ${typeInIndonesian}`;

                        $('#kelas').append(`<option value="${item.id}" data-type="${item.type}">${optionText}</option>`);
                    });

                    $('#kelas').prop('disabled', false);
                },
                error: function (xhr, status, error) {
                    console.log('Error: ' + error);
                }
            });
        }
    });

    // Event saat memilih kelas
    $('#kelas').change(function() {
        let selectedType = $("#kelas option:selected").data("type");
        console.log("Tipe yang dipilih:", selectedType);

        // Reset venue setiap kali kelas berubah
        $('#venue').empty().append('<option selected disabled>--Pilih Venue--</option>').prop('disabled', true);

        // AJAX untuk mendapatkan daftar venue setelah kelas dipilih
        $.ajax({
            url: `/api/venues`,
            type: 'GET',
            headers: {
                'Authorization': `Bearer ${getPsixFromLocalStorage()}`,
                'Accept': 'application/json'
            },
            success: function (data) {
                console.log("Data Venue:", data);

                $.each(data, function (index, item) {
                    $('#venue').append(new Option(item.name, item.id));
                });

                $('#venue').prop('disabled', false);
            },
            error: function (xhr, status, error) {
                console.log('Error:', error);
            }
        });
    });

    // Event saat memilih venue (opsional jika ingin menangani event ini)
    $('#venue').change(function() {
        let selectedVenue = $(this).val();
        console.log("Venue yang dipilih:", selectedVenue);
    });


    // Fungsi untuk menangani aksi Edit
    const table = $('#example').DataTable({
        "paging": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "/api/schedule-completes",
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
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        },
        "pageLength": 10, // Jumlah data per halaman
        "columns": [
            { "data": "sportId" },
            { "data": "sportClassId" },
            {
                "data": "gender",
                "render": function (data) {
                    let gender;
                    gender = data.type === 'female' ? 'Perempuan' : 'Laki-Laki';
                    return gender;

                }
             },
            { "data": "venue" },
            {
                "data": "date",
                "render": function(data){
                    return formatDateToIndonesian(data);
                }

            },
            {
                "data": "id",
                "render": function(data, type, row) {
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

        let isValid = true;

        const sportId = $('#cabang_olahraga').val();
        const venueId = $('#venue').val();
        const sportClassId = $('#kelas').val();
        const date = $('#tanggal_pertandingan').val();



        // Validasi form dan tampilkan alert
        if (!sportId) {
            $('#cabang_olahraga_alert').text('Harap pilih cabor').show();
            isValid = false;
        }

        if (!venueId) {
            $('#venue_alert').text('Harap pilih venue').show();
            isValid = false;
        }

        if (!sportClassId) {
            $('#kelas_alert').text('Harap pilih kelas cabor').show();
            isValid = false;
        }

        if (!date) {
            $('#tanggal_pertandingan_alert').text('Tanggal pertandingan harus dipilih').show();
            isValid = false;
        }



        // Jika form valid, kirim data
        if (isValid) {
            // Siapkan data untuk dikirim
            const formData = {
                sportId: sportId,
                venueId: venueId,
                sportClassId: sportClassId,
                date: date,
                status: 'active'
            };

            // Kirim data ke server menggunakan AJAX
            $.ajax({
                url: '/api/schedules', // URL untuk mengirim data
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
                        text: "Data Pertandingan Sudah Dibuat",
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
