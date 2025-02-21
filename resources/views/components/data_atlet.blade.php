<div class="tab-pane fade" id="data_atlet">
    <div class="mb-4">
        <h5>Data Atlet</h5>
    </div>
    <div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="berat_badan" class="form-label">Berat Badan</label>
                    <div class="input-group">
                        <input type="text" class="form-control notext" id="berat_badan" maxlength="3">
                        <span class="input-group-text">Kg</span>
                    </div>
                    <div id="berat_badan_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="tinggi_badan" class="form-label">Tinggi Badan</label>
                    <div class="input-group">
                        <input type="text" class="form-control notext" id="tinggi_badan" maxlength="3">
                        <span class="input-group-text">Cm</span>
                    </div>
                    <div id="tinggi_badan_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="kontingen" class="form-label">Kontingen</label>
                    <select id="kontingen" class="form-control"></select>
                    <div id="kontingen_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="cabor" class="form-label">Cabang Olahraga</label>
                    <select id="cabor" class="form-control"></select>
                    <div id="cabor_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
    <ul class="pager wizard twitter-bs-wizard-pager-link">
        <li class="previous">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="previousTab()"><i class="bx bx-chevron-left me-1"></i> Previous</a>
        </li>
        <li class="next" id="submit-btn-atlet">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="validateAndSubmitAtleet()">Submit <i class="bx bx-chevron-right ms-1"></i></a>
        </li>
        <li class="next" id="next-btn-atlet" style="display: none;">
            <a href="javascript:void(0);" class="btn btn-success" onclick="nextTab()">Next <i class="bx bx-chevron-right ms-1"></i></a>
        </li>
    </ul>
</div>

@section('catlet')
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
                }
            }
        });

        // Fungsi validasi dan submit data
        window.validateAndSubmitAtleet = function() {
            // Clear previous alert messages
            $(".alert").hide();

            var isValid = true;

            // Ambil nilai dari input dan select
            var beratBadan = $('#berat_badan').val();
            var tinggiBadan = $('#tinggi_badan').val();
            var kontingen = $('#kontingen').val();
            var cabor = $('#cabor').val();

            // Validasi form dan tampilkan alert
            if (!beratBadan) {
                $('#berat_badan_alert').text('Berat Badan harus diisi').show();
                isValid = false;
            }
            if (!tinggiBadan) {
                $('#tinggi_badan_alert').text('Tinggi Badan harus diisi').show();
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
                var formData = {
                    weight: beratBadan,
                    height: tinggiBadan,
                    regionalRepresentative: kontingen,
                    sportId: cabor,
                    // peopleId akan diambil sesuai dengan data yang ada di sistem Anda (misal, dari sesi login)
                    peopleId: localStorage.getItem('personId'), // Gantilah dengan ID orang yang sesuai
                    achievements: ''  // Optional, jika tidak ada bisa dikirim sebagai string kosong
                };

                // Kirim data ke server menggunakan AJAX
                $.ajax({
                    url: '/api/athletes', // URL untuk mengirim data
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: "Berhasil !",
                            text: "Data Atlet Sudah Disimpan",
                            icon: "success",
                            confirmButtonClass: "btn btn-success",
                            buttonsStyling: false,
                            customClass: {
                                popup: 'custom-popup'
                            }
                        });
                        $(".alert").hide();
                        document.getElementById('submit-btn-atlet').style.display = 'none';
                        document.getElementById('next-btn-atlet').style.display = 'block';
                        localStorage.setItem('atletId', response.id);
                    },
                    error: function(xhr, status, error) {
                        if (localStorage.getItem('atletId')) {
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
