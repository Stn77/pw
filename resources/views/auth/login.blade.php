<x-layouts.auth title="Login">

    <!-- Pills content -->
    <div class="fade show active p-5 border w-50 h-50 d-flex rounded-3" aria-labelledby="tab-login">
        <form class="w-100 px-5" action="{{route('login.post')}}">
            <p class="text-center fw-bold display-6">Login</p>

            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" id="loginName" class="form-control" />
                <label class="form-label" for="usernamea">Username</label>
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="loginPassword" class="form-control" />
                <label class="form-label" for="password">Password</label>
            </div>

            <!-- Submit button -->
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Login</button>
        </form>
    </div>

    <!-- Pills content -->
</x-layouts.auth>
