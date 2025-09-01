<x-layouts.auth title="Login">

    <!-- Pills content -->
    <div class="fade show active p-5 w-50 h-50 min-vh-25 d-flex rounded-3" aria-labelledby="tab-login">

        <form method="POST" class="w-100 px-5" action="{{route('login.post')}}">
            @csrf
            <p class="text-center fw-bold display-6">Login</p>

            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" id="username" class="form-control" name="username"/>
                <label class="form-label" for="username">Username</label>
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="password" class="form-control" name="password"/>
                <label class="form-label" for="password">Password</label>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4">Login</button>
        </form>
    </div>

    <!-- Pills content -->
</x-layouts.auth>
