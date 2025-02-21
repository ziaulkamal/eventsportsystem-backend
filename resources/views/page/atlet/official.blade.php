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
                        <a href="#data_official" class="nav-link" data-bs-toggle="pill">
                            <div class="step-icon" data-bs-toggle="tooltip" title="Data Official">
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
                    @include('components.data_official')
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
                    <th>Nama Coach</th>
                    <th>Foto</th>
                    <th>Kelamin</th>
                    <th>Usia</th>
                    <th>Kontingen</th>
                    <th>Cabang Olahraga</th>
                    <th>Agama</th>
                    <th>Nomor Telepon</th>
                    <th>Opsi</th>
                    <th>Provinsi</th>
                    <th>Kabupaten Kota</th>
                    <th>Kecamatan</th>
                    <th>Desa</th>
                    <th>Terakhir Diubah</th>
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
    <a href="#" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Tambah Data</a>
</div>
@endsection

@section('script')

<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnToggle = document.querySelector(".btn-added");
    const table = document.getElementById("table");
    const form = document.getElementById("form");

    btnToggle.addEventListener("click", function (event) {
        event.preventDefault();

        if (table.style.display !== "none") {
            table.style.display = "none";
            form.style.display = "block";
            btnToggle.innerHTML = '<i data-feather="eye" class="me-2"></i>Lihat Data';
        } else {
            table.style.display = "block";
            form.style.display = "none";
            btnToggle.innerHTML = '<i data-feather="plus-circle" class="me-2"></i>Tambah Data';
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
            url: "/api/official-completes",
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
            }
        },
        "pageLength": 5, // Jumlah data per halaman
        "columns": [
            { "data": "fullName" },
            {
                "data": "imageProfile",
                "render": function(data, type, row) {
                    let defaultImage = row.gender === 'female' ? '/assets/img/default-bg/female.png' : '/assets/img/default-bg/male.png';
                    let imageSrc = data ? `/storage/${data}` : defaultImage;
                    return `
                        <div class="card-on-table">
                            <img src="${imageSrc}" class="card-img-top image-on-table" alt="${row.fullName}">
                        </div>
                    `;
                }
            },
            {
                "data": "gender",
                "render": function(data) {
                    return data === "female" ? "Perempuan" : "Laki-laki";
                }
            },
            { "data": "age" },
            { "data": "regional_representative" },
            { "data": "sport" },
            { "data": "religion" },
            { "data": "phoneNumber" },
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
            { "data": "province" },
            { "data": "regencie" },
            { "data": "district" },
            { "data": "village" },
            { "data": "updated_timestamp" }
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
});

</script>
@yield('cpersonal')
@yield('cofficial')
@yield('cdocuments')

<script>
    function nextTab() {
        const currentTab = document.querySelector('.twitter-bs-wizard .nav-link.active');
        const nextTab = currentTab.closest('li').nextElementSibling?.querySelector('.nav-link');

        if (nextTab) {
            const bsTab = new bootstrap.Tab(nextTab);
            bsTab.show();
        }
    }

    function previousTab() {
        const currentTab = document.querySelector('.twitter-bs-wizard .nav-link.active');
        const prevTab = currentTab.closest('li').previousElementSibling?.querySelector('.nav-link');

        if (prevTab) {
            const bsTab = new bootstrap.Tab(prevTab);
            bsTab.show();
        }
    }
</script>

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = "#";
            preview.style.display = 'none';
        }
    }
</script>
@endsection
