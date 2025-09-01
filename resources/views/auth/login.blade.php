<x-layouts.auth title="Login">

    <!-- Pills content -->
    <div class="fade show active p-5 border w-50 h-50 d-flex rounded-3" aria-labelledby="tab-login">
        <form class="w-100 px-5" action="{{route('login.post')}}">
            <p class="text-center fw-bold display-6">Login</p>

            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" id="loginName" class="form-control" />
                <label class="form-label" for="loginName">Username</label>
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="loginPassword" class="form-control" />
                <label class="form-label" for="loginPassword">Password</label>
            </div>

            <!-- 2 column grid layout -->
            <div class="row mb-4">
                <div class="col-md-6 d-flex justify-content-center">
                    <!-- Checkbox -->
                    <div class="form-check mb-3 mb-md-0">
                        <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                        <label class="form-check-label" for="loginCheck"> Remember me </label>
                    </div>
                </div>

                <div class="col-md-6 d-flex justify-content-center">
                    <!-- Simple link -->
                    <a href="#!">Forgot password?</a>
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign
                in</button>
        </form>
    </div>

    <!-- Pills content -->
</x-layouts.auth>
