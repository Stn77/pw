<x-layouts.auth title="Login">
    <div class="min-vh-100 d-flex">
        <!-- Left Section (Form) -->
        <div class="px-5 bg-white col-md-6 d-flex flex-column justify-content-center ">
            <h2 class="mb-2 fw-bold">Login</h2>
            <p class="mb-4 text-muted">Selamat datang di Prima Pay. Login untuk mengakses fitur Prima Score</p>
            <hr>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Your Username</label>
                    <input type="text" id="username" name="username"
                        class="form-control @error('username') is-invalid @enderror" placeholder="Username" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Login Button -->
                <button type="submit" class="text-white btn w-100"
                    style="background-color:#6f5a75;">Login</button>

                <!-- Register Link -->
                {{-- <a href="{{ route('register') }}" class="mt-2 text-white btn w-100"
                    style="background-color:#6f5a75;">Register</a> --}}
            </form>
        </div>

        <!-- Right Section (Logo) -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center bg-light">
            <img src="{{ asset('img/login logo.png') }}" alt="Prima Pay Logo" class="mb-3" style="max-width:300px;">
        </div>
    </div>
</x-layouts.auth>
