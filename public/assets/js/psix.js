function getPsixFromLocalStorage() {
    const userData = localStorage.getItem('user_data');
    if (userData) {
        const parsedUserData = JSON.parse(userData);
        return parsedUserData._psix || null;
    }
    return null;
}

window.addEventListener('load', function() {
    localStorage.removeItem('coachId');
    localStorage.removeItem('personId');
    localStorage.removeItem('atletId');
    localStorage.removeItem('documentId');
});

function getUserDataFromLocalStorage() {
    const userData = localStorage.getItem("user_data");
    return userData ? JSON.parse(userData) : null;
}

function displayUserData(userData) {
    if (userData) {
        document.getElementById("name_user").textContent = userData.name;
        document.getElementById("name_level").textContent = userData.roleName;
        document.getElementById("image-profile").src = window.location.origin + '/' + userData.image;
    }
}

function logoutUser() {
    const authToken = getPsixFromLocalStorage();
    if (!authToken) {
        alert('Token tidak ditemukan, silakan login kembali.');
        return;
    }
    $.ajax({
        url: "/api/logout",
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${authToken}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                window.location.href = '/';
            } else {
                alert('Logout gagal. Coba lagi.');
            }
        },
        error: function (xhr, status, error) {
            console.error('Terjadi kesalahan saat logout:', error);
            alert('Terjadi kesalahan. Coba lagi.');
        }
    });
}
$(document).ready(function () {
    const userData = getUserDataFromLocalStorage();
    displayUserData(userData);
    $('#logout').on('click', logoutUser);
    $('#logout-mobile').on('click', logoutUser);
});

function formatDateToIndonesian(dateString) {
        const months = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const date = new Date(dateString);
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();

        return `${day} ${month} ${year}`;
    }
