<div class="tab-pane fade" id="data_dokumen">
    <div class="mb-4">
        <h5>Data Dokumen</h5>
    </div>
    <form id="data-dokumen-form" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="foto_ktp" class="form-label">Foto KTP *</label>
                    <input type="file" class="form-control" id="foto_ktp" accept="image/*" onchange="previewImage(this, 'preview_foto_ktp')">
                    <div class="mt-2">
                        <img id="preview_foto_ktp" class="preview-image" src="#" alt="Preview Foto KTP" style="display: none;">
                    </div>
                    <div id="foto_ktp_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="foto_ijazah" class="form-label">Foto Ijazah *</label>
                    <input type="file" class="form-control" id="foto_ijazah" accept="image/*" onchange="previewImage(this, 'preview_foto_ijazah')">
                    <div class="mt-2">
                        <img id="preview_foto_ijazah" class="preview-image" src="#" alt="Preview Foto Ijazah" style="display: none;">
                    </div>
                    <div id="foto_ijazah_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="foto_akte_kelahiran" class="form-label">Foto Akte Kelahiran *</label>
                    <input type="file" class="form-control" id="foto_akte_kelahiran" accept="image/*" onchange="previewImage(this, 'preview_foto_akte_kelahiran')">
                    <div class="mt-2">
                        <img id="preview_foto_akte_kelahiran" class="preview-image" src="#" alt="Preview Foto Akte Kelahiran" style="display: none;">
                    </div>
                    <div id="foto_akte_kelahiran_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="foto_selfie_ktp" class="form-label">Foto Selfie + KTP *</label>
                    <input type="file" class="form-control" id="foto_selfie_ktp" accept="image/*" onchange="previewImage(this, 'preview_foto_selfie_ktp')">
                    <div class="mt-2">
                        <img id="preview_foto_selfie_ktp" class="preview-image" src="#" alt="Preview Foto Selfie + KTP" style="display: none;">
                    </div>
                    <div id="foto_selfie_ktp_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="pas_foto" class="form-label">Pas Foto *</label>
                    <input type="file" class="form-control" id="pas_foto" accept="image/*" onchange="previewImage(this, 'preview_pas_foto')">
                    <div class="mt-2">
                        <img id="preview_pas_foto" class="preview-image" src="#" alt="Preview Pas Foto" style="display: none;">
                    </div>
                    <div id="pas_foto_alert" class="alert alert-warning mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
    </form>
    <ul class="pager wizard twitter-bs-wizard-pager-link">
        <li class="previous">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="previousTab()"><i class="bx bx-chevron-left me-1"></i> Previous</a>
        </li>
        <li class="next" id="submit-btn-document">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="validateAndSubmitDocument()">Submit <i class="bx bx-chevron-right ms-1"></i></a>
        </li>
        <li class="next" id="next-btn-document" style="display: none;">
            <a href="javascript:void(0);" class="btn btn-success" onclick="reloadPage()">Selesai <i class="bx bx-chevron-right ms-1"></i></a>
        </li>
    </ul>
</div>

@section('cdocuments')
<script>
    function reloadPage() {
        // Pastikan tombol "Next" hanya berfungsi setelah data berhasil dikirim
        if ($("#next-btn-document").is(":visible")) {
            location.reload();
        } else {
            alert("Silakan lengkapi dan kirim data terlebih dahulu.");
        }
    }
    // Mapping ID input form ke format API
    const fieldMapping = {
        "foto_ktp": "docsKtp",
        "foto_ijazah": "docsIjazah",
        "foto_akte_kelahiran": "docsAkte",
        "foto_selfie_ktp": "docsSelfieKtp",
        "pas_foto": "docsImageProfile",
    };

    // Fungsi untuk menampilkan preview gambar
    function previewImage(input, previewId) {
        const file = input.files[0];
        const alertId = `${input.id}_alert`;
        const reader = new FileReader();

        if (file) {
            const validation = isValidImage(file);
            if (!validation.isValid) {
                document.getElementById(alertId).style.display = "block";
                document.getElementById(alertId).innerHTML = validation.message;
                document.getElementById(previewId).style.display = "none";
                input.value = ""; // Reset input jika tidak valid
                return;
            }

            reader.onload = function (e) {
                document.getElementById(previewId).src = e.target.result;
                document.getElementById(previewId).style.display = "block";
            };
            reader.readAsDataURL(file);
            document.getElementById(alertId).style.display = "none";
        }
    }

    // Fungsi validasi file gambar
    function isValidImage(file) {
        const validExtensions = ["image/jpeg", "image/png", "image/jpg"];
        const maxSize = 3 * 1024 * 1024; // Maksimum 3MB

        if (!validExtensions.includes(file.type)) {
            return { isValid: false, message: "Silakan unggah file gambar yang valid (JPEG, PNG)." };
        }

        if (file.size > maxSize) {
            return { isValid: false, message: "Ukuran file melebihi batas 3MB. Silakan unggah file yang lebih kecil." };
        }

        return { isValid: true, message: "" };
    }

    // Fungsi validasi dan submit
    function validateAndSubmitDocument() {
        let isValid = true;
        const documentId = localStorage.getItem("documentId");

        if (!documentId) {
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
            return;
        }

        const formData = new FormData();
        Object.keys(fieldMapping).forEach((formId) => {
            const apiField = fieldMapping[formId];
            const input = document.getElementById(formId);
            const file = input.files[0];
            const alertId = `${formId}_alert`;
            const previewId = `preview_${formId}`;

            if (!file) {
                document.getElementById(alertId).style.display = "block";
                document.getElementById(alertId).innerHTML = `Harap unggah file untuk ${formId.replace('_', ' ')}.`;
                document.getElementById(previewId).style.display = "none";
                isValid = false;
                return;
            }

            const validation = isValidImage(file);
            if (!validation.isValid) {
                document.getElementById(alertId).style.display = "block";
                document.getElementById(alertId).innerHTML = validation.message;
                document.getElementById(previewId).style.display = "none";
                input.value = ""; // Reset input jika invalid
                isValid = false;
            } else {
                formData.append(apiField, file);
                document.getElementById(alertId).style.display = "none";
            }
        });

        if (!isValid) return;

        // formData.append("userId", "uuid-user-sample");
        formData.append("_method", "PATCH"); // Trik untuk PATCH
        formData.append("docsKtp", document.getElementById("foto_ktp").files[0]);
        formData.append("docsIjazah", document.getElementById("foto_ijazah").files[0]);
        formData.append("docsAkte", document.getElementById("foto_akte_kelahiran").files[0]);
        formData.append("docsSelfieKtp", document.getElementById("foto_selfie_ktp").files[0]);
        formData.append("docsImageProfile", document.getElementById("pas_foto").files[0]);

        $.ajax({
            url: `/api/documents/${documentId}`,
            type: "POST", // Coba ganti dari PATCH ke POST
            data: formData,
            processData: false,
            contentType: false,
            // headers: {
            //     Authorization: `Bearer ${localStorage.getItem("authToken")}`,
            // },
            success: function (response) {
                Swal.fire({
                    title: "Berhasil !",
                    text: "Data Dokumen Berhasil Disimpan",
                    icon: "success",
                    confirmButtonClass: "btn btn-success",
                    buttonsStyling: false,
                    customClass: {
                        popup: 'custom-popup'
                    }
                });
                $("#submit-btn-document").hide();
                $("#next-btn-document").show();
            },
            error: function (xhr) {
                let errorMessage = "Terjadi kesalahan saat mengirim data.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    title: "Gagal !",
                    text: `Kesalahan ${errorMessage}`,
                    icon: "error",
                    confirmButtonClass: "btn btn-danger",
                    buttonsStyling: false,
                    customClass: {
                        popup: 'custom-popup'
                    }
                });
            },
        });
    }
</script>


@endsection
