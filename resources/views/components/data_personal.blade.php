<div class="tab-pane fade show active" id="data_personal">
    <div class="mb-4">
        <h5>Data Personal</h5>
    </div>
    <div>
        <div class="row">
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Nomor Induk Kependudukan" class="form-label">Nomor Induk Kependudukan</label>
                    <input type="hidden" class="form-control" id="identityId">
                    <input type="text" class="form-control notext" id="nomor_induk_kependudukan" maxlength="16" autocomplete="off">
                    <div id="nik-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Nama Lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" autocomplete="off">
                    <div id="nama-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Jenis Kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin">
                        <option value="" selected disabled>--Pilih--</option>
                        <option value="male">Laki-Laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                    <div id="jk-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Tanggal Lahir" class="form-label">Tanggal Lahir</label>
                    <input type="text" class="form-control" id="tanggal_lahir" autocomplete="off">
                    <div id="tgl-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Nomor Kartu Keluarga" class="form-label">Nomor Kartu Keluarga</label>
                    <input type="text" class="form-control notext" id="nomor_kartu_keluarga" maxlength="16" autocomplete="off">
                    <div id="kk-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Nomor Handphone" class="form-label">Nomor Handphone</label>
                    <input type="text" class="form-control notext" id="nomor_handphone" maxlength="13" autocomplete="off">
                    <div id="hp-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Agama" class="form-label">Agama</label>
                    <select class="form-control" id="agama">
                        <option value="" selected disabled>--Pilih--</option>
                        <option value="1">Islam</option>
                        <option value="2">Kristen Katolik</option>
                        <option value="3">Kristen Protestan</option>
                        <option value="4">Hindu</option>
                        <option value="5">Buddha</option>
                        <option value="6">Konghucu</option>
                    </select>
                    <div id="agama-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" autocomplete="off">
                    <div id="alamat-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Provinsi" class="form-label">Provinsi</label>
                    <select id="provinsi" class="form-control"></select>
                    <div id="provinsi-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Kabupaten/Kota" class="form-label">Kabupaten/Kota</label>
                    <select id="kabupaten_kota" class="form-control" disabled></select>
                    <div id="kabupaten-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Kecamatan" class="form-label">Kecamatan</label>
                    <select id="kecamatan" class="form-control" disabled></select>
                    <div id="kecamatan-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="Desa" class="form-label">Desa</label>
                    <select id="desa" class="form-control" disabled></select>
                    <div id="desa-alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <ul class="pager wizard twitter-bs-wizard-pager-link">
        <li class="next" id="submit-btn-personal">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="validateAndSubmitPersonal()">Submit <i class="bx bx-chevron-right ms-1"></i></a>
        </li>
        <li class="next" id="next-btn-personal" style="display: none;">
            <a href="javascript:void(0);" class="btn btn-success" onclick="nextTab()">Next <i class="bx bx-chevron-right ms-1"></i></a>
        </li>
    </ul>
</div>

@section('cpersonal')
<script src="{{ asset('assets/js/custom-pora.js') }}"></script>
<script>
function resetDropdown(selector, placeholder) {
    $(selector).empty().append(`<option selected disabled>${placeholder}</option>`);
    // $(selector).prop('disabled', true);
}
function loadMendagriAPI() {
    // $('#provinsi, #kabupaten_kota, #kecamatan, #desa').select2();

    // Fetch Provinsi
    $.ajax({
        url: '/api/provinces',
        type: 'GET',
        headers: {
            'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        success: function (data) {
            $('#provinsi').empty().append('<option selected disabled>Pilih Provinsi</option>');
            $.each(data, function (name, id) {
                $('#provinsi').append(new Option(name, id));
            });
            $('#provinsi').prop('disabled', false);
        }
    });

    // Event ketika Provinsi berubah
    $('#provinsi').on('change', function () {
        var provinceId = $(this).val();

        // Kosongkan dan reset dropdown di bawahnya
        resetDropdown('#kabupaten_kota', 'Pilih Kabupaten/Kota');
        resetDropdown('#kecamatan', 'Pilih Kecamatan');
        resetDropdown('#desa', 'Pilih Desa');

        if (provinceId) {
            $.ajax({
                url: `/api/regencies/${provinceId}`,
                type: 'GET',
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                success: function (data) {
                    $.each(data, function (name, id) {
                        $('#kabupaten_kota').append(new Option(name, id));
                    });
                    $('#kabupaten_kota').prop('disabled', false);
                }
            });
        }
    });

    // Event ketika Kabupaten/Kota berubah
    $('#kabupaten_kota').on('change', function () {
        var regencyId = $(this).val();

        // Kosongkan dan reset dropdown di bawahnya
        resetDropdown('#kecamatan', 'Pilih Kecamatan');
        resetDropdown('#desa', 'Pilih Desa');

        if (regencyId) {
            $.ajax({
                url: `/api/districts/${regencyId}`,
                type: 'GET',
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                success: function (data) {
                    $.each(data, function (name, id) {
                        $('#kecamatan').append(new Option(name, id));
                    });
                    $('#kecamatan').prop('disabled', false);
                }
            });
        }
    });

    // Event ketika Kecamatan berubah
    $('#kecamatan').on('change', function () {
        var districtId = $(this).val();

        // Kosongkan dan reset dropdown Desa
        resetDropdown('#desa', 'Pilih Desa');

        if (districtId) {
            $.ajax({
                url: `/api/villages/${districtId}`,
                type: 'GET',
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                success: function (data) {
                    $.each(data, function (name, id) {
                        $('#desa').append(new Option(name, id));
                    });
                    $('#desa').prop('disabled', false);
                }
            });
        }
    });
}
</script>
<script>
function validateAndSubmitPersonal() {
    let isValid = true;

    function showAlert(id, message) {
        let alertElement = document.getElementById(id);
        if (message) {
            alertElement.style.display = 'block';
            alertElement.innerText = message;
        } else {
            alertElement.style.display = 'none';
        }
    }

    // Get values from inputs
    let nik = document.getElementById('nomor_induk_kependudukan').value.trim();
    let nama = document.getElementById('nama_lengkap').value.trim();
    let jenisKelamin = document.getElementById('jenis_kelamin').value;
    let tanggalLahir = document.getElementById('tanggal_lahir').value.trim();
    let nomorKK = document.getElementById('nomor_kartu_keluarga').value.trim();
    let nomorHP = document.getElementById('nomor_handphone').value.trim();
    let agama = document.getElementById('agama').value;
    let alamat = document.getElementById('alamat').value.trim();
    let provinsi = document.getElementById('provinsi').value;
    let kabupatenKota = document.getElementById('kabupaten_kota').value;
    let kecamatan = document.getElementById('kecamatan').value;
    let desa = document.getElementById('desa').value;

    // Validation checks and alert display
    if (nik === '') {
        showAlert('nik-alert', 'Nomor Induk Kependudukan harus diisi!');
        isValid = false;
    } else {
        showAlert('nik-alert', '');
    }

    if (nama === '') {
        showAlert('nama-alert', 'Nama Lengkap harus diisi!');
        isValid = false;
    } else {
        showAlert('nama-alert', '');
    }

    if (jenisKelamin === '') {
        showAlert('jk-alert', 'Pilih Jenis Kelamin!');
        isValid = false;
    } else {
        showAlert('jk-alert', '');
    }

    if (tanggalLahir === '') {
        showAlert('tgl-alert', 'Tanggal Lahir harus diisi!');
        isValid = false;
    } else {
        showAlert('tgl-alert', '');
    }



    if (nomorHP === '') {
        showAlert('hp-alert', 'Nomor Handphone harus diisi!');
        isValid = false;
    } else {
        showAlert('hp-alert', '');
    }

    if (agama === '') {
        showAlert('agama-alert', 'Pilih Agama!');
        isValid = false;
    } else {
        showAlert('agama-alert', '');
    }

    if (alamat === '') {
        showAlert('alamat-alert', 'Alamat harus diisi!');
        isValid = false;
    } else {
        showAlert('alamat-alert', '');
    }

    if (provinsi === '') {
        showAlert('provinsi-alert', 'Pilih Provinsi!');
        isValid = false;
    } else {
        showAlert('provinsi-alert', '');
    }

    if (kabupatenKota === '') {
        showAlert('kabupaten-alert', 'Pilih Kabupaten/Kota!');
        isValid = false;
    } else {
        showAlert('kabupaten-alert', '');
    }

    if (kecamatan === '') {
        showAlert('kecamatan-alert', 'Pilih Kecamatan!');
        isValid = false;
    } else {
        showAlert('kecamatan-alert', '');
    }

    if (desa === '') {
        showAlert('desa-alert', 'Pilih Desa!');
        isValid = false;
    } else {
        showAlert('desa-alert', '');
    }

    // If form is valid, proceed to submit
    if (!isValid) {
        return;
    }
    const parts = tanggalLahir.split("-"); // [ "08", "02", "1981" ]
    const formattedBirthdate = `${parts[2]}-${parts[1]}-${parts[0]} 07:00:00`;
    const formData = {
        fullName: nama,
        birthdate: formattedBirthdate,
        identityNumber: nik,
        familyIdentityNumber: nomorKK,
        gender: jenisKelamin,
        streetAddress: alamat,
        religion: agama,
        provinceId: provinsi,
        regencieId: kabupatenKota,
        districtId: kecamatan,
        villageId: desa,
        phoneNumber: nomorHP,
        email: '',
        userId: ''
    };

    // Send data to API using jQuery AJAX
    $.ajax({
        url: '/api/people',  // Ganti dengan URL endpoint API Anda
        type: 'POST',
        data: JSON.stringify(formData),
        headers: {
            'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        success: function(response) {
            Swal.fire({
                title: "Berhasil !",
                text: "Data Personal Sudah Disimpan",
                icon: "success",
                confirmButtonClass: "btn btn-success",
                buttonsStyling: false,
                customClass: {
                    popup: 'custom-popup'
                }
            });
            document.getElementById('submit-btn-personal').style.display = 'none';
            document.getElementById('next-btn-personal').style.display = 'block';
            localStorage.setItem('personId', response.id);
            localStorage.setItem('documentId', response.documentId);

        },
        error: function(xhr, status, error) {
            const response = JSON.parse(xhr.responseText);
            if (response.error_code) {
                Swal.fire({
                    title: "Gagal !",
                    text: `${response.message}`,
                    icon: "danger",
                    confirmButtonClass: "btn btn-danger",
                    buttonsStyling: false,
                    customClass: {
                        popup: 'custom-popup'
                    }
                });
            } else {
                Swal.fire({
                    title: "Gagal !",
                    text: `Terjadi kesalahan saat mengirim data!`,
                    icon: "danger",
                    confirmButtonClass: "btn btn-danger",
                    buttonsStyling: false,
                    customClass: {
                        popup: 'custom-popup'
                    }
                });
            }
        }
    });
}

// Reset the alert messages when user starts typing or selecting input fields
document.querySelectorAll('input, select').forEach(input => {
    input.addEventListener('input', function () {
        let alertId = this.id + '-alert';
        let alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.style.display = 'none';
        }
    });
});
</script>

<script>
const formatDate = (date) => {
    if (!date) return '';
    const day = String(date.getDate()).padStart(2, '0'); // Menambahkan leading zero jika hari < 10
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Menambahkan leading zero jika bulan < 10
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
};

const convertToApiDate = (dateStr) => {
    if (!dateStr) return '';
    const [day, month, year] = dateStr.split('-');
    return `${year}-${month}-${day}`;  // Format yyyy-mm-dd
};

// Mengisi dropdown secara otomatis berdasarkan data yang diterima
function setDropdownValue(selector, value, apiUrl) {
    $.ajax({
        url: apiUrl,
        type: 'GET',
        headers: {
            'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        success: function (data) {
            $(selector).empty().append('<option value="" selected disabled>Pilih...</option>');
            $.each(data, function (name, id) {
                $(selector).append(new Option(name, id));
            });
            $(selector).val(value);  // Set nilai dropdown ke value yang sesuai
            $(selector).prop('disabled', false);
        },
        error: function () {
            console.error(`Gagal memuat data untuk ${selector}`);
        }
    });
};

$(document).ready(function() {

    loadMendagriAPI();

    const checkAndFetchData = () => {
        const identityId = $('#identityId').val();
        const name = $('#nama_lengkap').val();
        const birthdate = $('#tanggal_lahir').val();
        const formattedBirthdate = convertToApiDate(birthdate);
        const gender = $('#jenis_kelamin').val();

        if (identityId && name && formattedBirthdate && gender) {
            const apiUrl = `/api/fetch-people-with-attribute?personIdentity=${identityId}&name=${encodeURIComponent(name)}&birthdate=${formattedBirthdate}&gender=${gender}`;

            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                success: function (data) {
                    if (data.province) setDropdownValue('#provinsi', data.province, '/api/provinces');
                    if (data.city) setDropdownValue('#kabupaten_kota', data.city, `/api/regencies/${data.province}`);
                    if (data.district) setDropdownValue('#kecamatan', data.district, `/api/districts/${data.city}`);
                    if (data.village) setDropdownValue('#desa', data.village, `/api/villages/${data.district}`);
                },
                error: function (xhr) {
                    const response = xhr.responseJSON;
                    console.error('Error fetching data:', response ? response.message : xhr.statusText);
                    Swal.fire({
                        title: "Gagal !",
                        text: `Gagal mendapatkan data alamat lengkap, harus isi manual!`,
                        icon: "info",
                        confirmButtonClass: "btn btn-danger",
                        buttonsStyling: false,
                        customClass: {
                            popup: 'custom-popup'
                        }
                    });
                }
            });
        }
    };

    $('#identityId, #nama_lengkap, #tanggal_lahir, #jenis_kelamin').on('change', checkAndFetchData);

    flatpickr("#tanggal_lahir", {
        dateFormat: "d-m-Y"
    });

    $("#nama_lengkap").on('input', function() {
        // Sembunyikan alert dengan ID "nik-alert"
        $("#nik-alert").hide();
    });

    $("#nomor_induk_kependudukan").on('input', function() {
        let nik = $(this).val();

        if (nik.length < 16) {
            $("#identityId").val(""); // Reset input hidden identityId
            $("#nik-alert").hide();   // Sembunyikan alert jika NIK tidak valid
            $("#jenis_kelamin").val(""); // Reset jenis kelamin
            $("#tanggal_lahir").val(""); // Reset tanggal lahir
            resetDropdown('#provinsi', 'Pilih Provinsi');
            resetDropdown('#kabupaten_kota', 'Pilih Kabupaten/Kota');
            resetDropdown('#kecamatan', 'Pilih Kecamatan');
            resetDropdown('#desa', 'Pilih Desa');
            loadMendagriAPI();
        }

        // Cek apakah panjang NIK adalah 16 karakter
        if (nik.length === 16) {
            // Lakukan ajax untuk mencari NIK
            $.ajax({
                url: `/api/people/find-by-nik/${nik}`,
                type: 'GET',
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                success: function(data) {
                    if (data !== 200) {
                        let alertBox = $("#nik-alert");
                        alertBox.show();
                        alertBox.html("NIK ini sudah tersedia di sistem.");
                        alertBox.removeClass("alert-warning alert-success");
                        alertBox.addClass("alert-danger");
                        $("#identityId").val(data.id);

                    } else {
                        $("#nik-alert").hide();
                        $.ajax({
                            url: `/api/fetch-people/${nik}`,
                            type: 'GET',
                            headers: {
                                'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            success: function(data) {
                                if (data) {
                                    let alertBox = $("#nik-alert");
                                    alertBox.show();
                                    alertBox.html("Nomor Induk Kependudukan bisa didaftarkan.");
                                    alertBox.removeClass("alert-warning alert-danger");
                                    alertBox.addClass("alert-success");
                                    let gender = genderFromKtp(nik);
                                    console.log("Gender from NIK:", gender);
                                    let birthdate = birthdateFromKtp(nik);

                                    $("#jenis_kelamin").val(gender);
                                    if (birthdate) {
                                        $("#tanggal_lahir").val(formatDate(birthdate));
                                    }
                                    $("#nama_lengkap").attr("placeholder", data.name);
                                    $("#identityId").val(data.id);

                                } else {
                                    let alertBox = $("#nik-alert");
                                    alertBox.show();
                                    alertBox.html("Data tidak ditemukan di sistem eksternal.");
                                    alertBox.removeClass("alert-warning alert-danger");
                                    alertBox.addClass("alert-danger");
                                }
                            },
                            error: function(xhr, status, error) {
                                alert("Terjadi kesalahan saat mengakses data eksternal.");
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    alert("Terjadi kesalahan saat mengakses data.");
                }
            });
        }
    });
});

</script>
@endsection

