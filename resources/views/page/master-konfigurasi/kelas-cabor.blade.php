@extends('layout.admin')

@section('content')
<div id="table" class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data {{ $title }}</h4>
        </div>
        <div class="card-body">
        <table id="example" class="table table-striped nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Kelas Cabang Olahraga</th>
                    <th>Cabang Olahrag</th>
                    <th>Jenis</th>
                    <th>Terakhir Diubah</th>
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
                            <h4>Tambah Kelas Cabang Olahraga</h4>
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
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Kelas</label>
                                <input type="text" class="form-control" id="nama_kelas">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipe</label>
                                <select class="form-select" id="jenis_kelamin">
                                    <option value="" selected disabled>--Pilih--</option>
                                    <option value="male">Laki-Laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>

                            <div class="modal-footer-btn">
                                <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-submit" onClick="submitKelasCabor()">Submit</button>
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
window.submitKelasCabor = function() {
    const namaCabor = $('#cabang_olahraga').val().trim();
    const namaKelas = $('#nama_kelas').val().trim();
    const type      = $('#jenis_kelamin').val().trim();


    const formData = {
        sportId:namaCabor,
        type:type,
        classOption:namaKelas,
    };

    // Validasi: jika nama olahraga kosong
    if (namaKelas === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Nama Kelas Olahraga tidak boleh kosong!',
        });
        return; // Hentikan proses jika validasi gagal
    }

    $.ajax({
        url: '/api/sport-classes', // URL untuk mengirim data
        method: 'POST',
        data: formData,
        headers: {
            'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
            // 'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        success: function(response) {
            Swal.fire({
                title: "Berhasil!",
                text: "Data Kelas Olahraga Sudah Disimpan",
                icon: "success",
                confirmButtonClass: "btn btn-success",
                buttonsStyling: false,
                customClass: {
                    popup: 'custom-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Refresh halaman
                    window.location.reload();
                }
            });

            // Reset form setelah submit sukses
            $('#namaKelas').val('');
            $('#namaCabor').val('');
            $('#type').val('');

            // Reload DataTable setelah sukses
            table.ajax.reload(null, false); // 'false' agar tetap di halaman saat ini
        },
        error: function(xhr) {
            if (xhr.status === 422) { // Jika validasi gagal
                let errors = xhr.responseJSON.errors;
                if (errors.name) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Nama "' + namaKelas + '" sudah ada!',
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Silakan coba lagi.',
                });
            }
        }
    });
};



document.addEventListener("DOMContentLoaded", function () {
    const btnToggle = document.querySelector(".btn-added");

    btnToggle.addEventListener("click", function (event) {
        event.preventDefault();

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
            $('#cabang_olahraga').empty().append('<option selected disabled>Pilih Cabang Olahraga</option>');

            // Iterasi data JSON dan tambahkan opsi ke select
            $.each(data, function (index, item) {
                $('#cabang_olahraga').append(new Option(item.name, item.id));
            });

            // Aktifkan select
            $('#cabang_olahraga').prop('disabled', false);
        }
    });

    const table = $('#example').DataTable({
        "paging": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "/api/sports-class-complete",
            type: "GET",
            dataSrc: "data",
            headers: {
                'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        },
        "pageLength": 5,
        "columns": [
            { "data": "classOption" },
            { "data": "sport" },
            { "data":
                "type",
                render: function(data) {
                    let gender = data === 'male' ? 'Laki-laki' : 'Perempuan'
                    return `Untuk ${gender}`;
                }

            },
            { "data": "updated_timestamp" },
            {
                "data": "id",
                "render": function(data, type, row) {
                    return `
                        <div class="action-buttons">
                            <button class="btn btn-primary btn-sm edit-btn" onClick="editRow('${data}')">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" onClick="deleteRow('${data}')">Hapus</button>
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
});
</script>

@endsection
