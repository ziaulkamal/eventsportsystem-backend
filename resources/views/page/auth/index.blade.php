@extends('layout.auth')

@section('content')
<form action="#">

    <div class="login-userset">
        <div class="login-logo logo-normal">
            <img src="{{ asset('assets/img/authentication/logo.png') }}" alt="img">
        </div>
        <a href="#" class="login-logo logo-white">
            <img src="{{ asset('assets/img/authentication/logo.png') }}"  alt="">
        </a>
        <div class="login-userheading">
            <h3>{{ $h1 }}</h3>
            <h4>{{ $description }}</h4>
        </div>
        <div id="login-alert" class="alert alert-outline-danger alert-dismissible fade show d-none">
            <span id="alert-message">None</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="form-login">
            <label>Email Atau Username</label>
            <div class="form-addons">
                <input type="text" class="form-control" id="username_or_email">
                <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="img">
            </div>
        </div>
        <div class="form-login">
            <label>Password</label>
            <div class="pass-group">
                <input type="password" class="pass-input" id="password">
                <span class="fas toggle-password fa-eye-slash"></span>
            </div>
        </div>
        <div class="form-login authentication-check">
            <div class="row">
                <div class="col-6">
                    <div class="custom-control custom-checkbox">
                        <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                            <input type="checkbox">
                            <span class="checkmarks"></span>Remember me
                        </label>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-login">
            <button type="submit" class="btn btn-login">Login</button>
        </div>


    </div>
</form>
@endsection

@section('script')
<script>
document.querySelector('form').addEventListener('submit', async function(e) {
    e.preventDefault();  // Mencegah form melakukan submit standar

    const user_or_email = document.getElementById('username_or_email').value;
    const password = document.getElementById('password').value;
    const alertBox = document.getElementById('login-alert');
    const alertMessage = document.getElementById('alert-message');

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({
                username_or_email: user_or_email,
                password: password
            })
        });

        const data = await response.json();
        console.log(data);

        if (response.ok) {
            alertMessage.innerText = 'Login berhasil! Anda akan segera dialihkan.';
            alertBox.classList.remove('d-none', 'alert-outline-danger');
            alertBox.classList.add('alert-outline-success');
            localStorage.setItem('user_data', JSON.stringify(data.data));
            // Redirect setelah beberapa detik
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 2000);
        } else {
            alertMessage.innerText = data.message || 'Login gagal, periksa kembali email dan password Anda.';
            alertBox.classList.remove('d-none', 'alert-outline-success');
            alertBox.classList.add('alert-outline-danger');
        }
    } catch (error) {
        console.error('Terjadi kesalahan:', error);
        alertMessage.innerText = 'Terjadi kesalahan saat mencoba login. Silakan coba lagi.';
        alertBox.classList.remove('d-none', 'alert-outline-success');
        alertBox.classList.add('alert-outline-danger');
    }
});

</script>
@endsection
