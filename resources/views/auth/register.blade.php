<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container login-con">
        <div class="container login-form row justify-content-end">
            <img src="{{ asset('img/gadai.png') }}" alt="logo" class="logo" />
            <p class="masuk">Buat Akun Baru</p>
            <p>Selamat datang Di Aplikasi Go Pegadaian! Ayo masuk ke akunmu supaya bisa menikmati banyak fitur</p>
            <hr style="background-color: black; height: 1px; border: none;">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <label for="name">Nama<span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama" class="form-control"
                        required />
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- <div>
                    <label for="email">Email<span class="text-danger">*</span></label>
                    <input type="text" id="email" name="email" class="form-control"
                        placeholder="Masukkan email" required />
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}
                <div>
                    <label for="password">Password<span class="text-danger">*</span></label>
                    <div class="input-groupi">
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Masukkan password" autocomplete="off" required />
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- <div>
                    <label for="password_confirmation">Ulangi Password<span class="text-danger">*</span></label>
                    <div class="input-groupi">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control" placeholder="Masukkan ulang password" autocomplete="off" required />
                    </div>
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}
                <div>
                    <button type="submit" class="btn btn-primary w-100">Create an account</button>
                </div>
                <p class="mt-1 text-center">
                    Sudah Punya Akun?
                    <a href="{{ route('login') }}" class="text-primary">Login here</a>
                </p>
                <p class="text-center">atau</p>
                <div class="icon">
                    <button class="google-btn">
                        <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Logo">
                        <span>Lanjutkan Dengan Google</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="container boxlogin">
        <div class="container box position-relative" style="height: 100%;">
            <img src="{{ asset('img/bgSAN.png') }}" alt="background" class="position-absolute"
                style="width: 100%; height: 100%;">
            <div class="position-absolute d-flex justify-content-center align-items-center"
                style="left: 0; right: 0; top: 0; bottom: 0; margin: auto;">
                <img src="{{ asset('img/SANabsolute.png') }}" width="400" alt="centered-image">
            </div>
        </div>
    </div>
</body>

</html>
