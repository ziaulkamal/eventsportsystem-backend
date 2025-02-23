<div class="tab-pane fade" id="data_coach">
    <div class="mb-4">
        <h5>Data Coach</h5>
    </div>
    <div>
        <div class="row">
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="kontingen" class="form-label">Kontingen</label>
                    <select id="kontingen" class="form-control"></select>
                    <div id="kontingen_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="cabor" class="form-label">Cabang Olahraga</label>
                    <select id="cabor" class="form-control"></select>
                    <div id="cabor_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select id="role" class="form-control">
                        <option value="" selected disabled>--Pilih--</option>
                        <option value="coach">Coach Utama</option>
                        <option value="coach_asisten">Asisten Coach</option>
                    </select>
                    <div id="role_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
    <ul class="pager wizard twitter-bs-wizard-pager-link">
        <li class="previous">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="previousTab()"><i class="bx bx-chevron-left me-1"></i> Previous</a>
        </li>
        <li class="next" id="submit-btn-coach">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="validateAndSubmitCoach()">Submit <i class="bx bx-chevron-right ms-1"></i></a>
        </li>
        <li class="next" id="next-btn-coach" style="display: none;">
            <a href="javascript:void(0);" class="btn btn-success" onclick="nextTab()">Next <i class="bx bx-chevron-right ms-1"></i></a>
        </li>
    </ul>
</div>

@section('ccoach')
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk kontingen
        $('#kontingen').select2({
            placeholder: 'Pilih Kontingen',
            ajax: {
                url: '/api/search/kontingen', // URL untuk mengambil data kontingen
                dataType: 'json',
                type: 'GET',
                delay: 250, // Menunggu 250ms setelah mengetik untuk mengirim permintaan
                data: function (params) {
                    return {
                        term: params.term // Mengirimkan kata kunci pencarian (term) ke server
                    };
                },
                processResults: function (data) {
                    // Mengembalikan hasil yang sudah diproses oleh server
                    return {
                        results: data.results
                    };
                },
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }
        });

        // Inisialisasi Select2 untuk cabang olahraga
        $('#cabor').select2({
            placeholder: 'Pilih Cabang Olahraga',
            ajax: {
                url: '/api/search/sport', // URL untuk mengambil data cabang olahraga
                dataType: 'json',
                type: 'GET',
                delay: 250, // Menunggu 250ms setelah mengetik untuk mengirim permintaan
                data: function (params) {
                    return {
                        term: params.term // Mengirimkan kata kunci pencarian (term) ke server
                    };
                },
                processResults: function (data) {
                    // Mengembalikan hasil yang sudah diproses oleh server
                    return {
                        results: data.results
                    };
                },
                headers: {
                    'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }
        });

        // Fungsi validasi dan submit data
        window.validateAndSubmitCoach = function() {
            // Clear previous alert messages
            $(".alert").hide();

            const isValid = true;

            const kontingen = $('#kontingen').val();
            const cabor = $('#cabor').val();
            const role = $('#role').val();

            // Validasi form dan tampilkan alert
            if (!role) {
                $('#role_alert').text('Role harus dipilih').show();
                isValid = false;
            }
            if (!kontingen) {
                $('#kontingen_alert').text('Kontingen harus dipilih').show();
                isValid = false;
            }
            if (!cabor) {
                $('#cabor_alert').text('Cabang Olahraga harus dipilih').show();
                isValid = false;
            }

            // Jika form valid, kirim data
            if (isValid) {
                // Siapkan data untuk dikirim
                const formData = {
                    regionalRepresentative: kontingen,
                    sportId: cabor,
                    role:role,
                    peopleId: localStorage.getItem('personId'),
                };

                // Kirim data ke server menggunakan AJAX
                $.ajax({
                    url: '/api/coaches', // URL untuk mengirim data
                    method: 'POST',
                    data: formData,
                    headers: {
                        'Authorization': `Bearer ${getPsixFromLocalStorage()}`, // Ganti dengan token yang sesuai
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Berhasil !",
                            text: "Data Coach Sudah Disimpan",
                            icon: "success",
                            confirmButtonClass: "btn btn-success",
                            buttonsStyling: false,
                            customClass: {
                                popup: 'custom-popup'
                            }
                        });
                        $(".alert").hide();
                        document.getElementById('submit-btn-coach').style.display = 'none';
                        document.getElementById('next-btn-coach').style.display = 'block';
                        localStorage.setItem('coachId', response.id);
                    },
                    error: function(xhr, status, error) {
                        if (localStorage.getItem('coachId')) {
                            Swal.fire({
                                title: "Gagal !",
                                text: `Anda Telah Mengirimkan Data Ini Sebelumnya`,
                                icon: "error",
                                confirmButtonClass: "btn btn-danger",
                                buttonsStyling: false,
                                customClass: {
                                    popup: 'custom-popup'
                                }
                            });
                        }else {
                            Swal.fire({
                                title: "Gagal !",
                                text: `Pastikan Anda Telah Mengisi Data Personal`,
                                icon: "error",
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
        };
    });
</script>
@endsection
