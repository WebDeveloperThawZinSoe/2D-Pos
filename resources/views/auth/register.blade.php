@extends('web.master')
@section('body')
<style>
.gradient-custom-2 {
    background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
}

/* input {
    border: 2px solid #d8363a !important;
    border-radius: 8px !important;
    padding: 10px !important;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
} */

input:focus {
    border-color: #b44593 !important;
    box-shadow: 0 0 8px rgba(180, 69, 147, 0.5);
    outline: none;
}

@media (min-width: 768px) {
    .gradient-form {
        height: 130vh !important;
    }
}

@media (min-width: 769px) {
    .gradient-custom-2 {
        border-top-right-radius: .3rem;
        border-bottom-right-radius: .3rem;
    }
}
</style>
<br>
<section class="h-100 gradient-form" style="background-color: #eee;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
                <div class="card rounded-3 text-black">
                    <div class="row g-0">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <div class="card-body p-md-5 mx-md-4">
                                <div class="text-center">
                                    <img style="width:100px !important;height:100px !important;"
                                        src="{{ asset('logo.png') }}" alt="logo">
                                    <h4 class="mt-1 mb-5 pb-1">Welcome to {{ env('APP_NAME') }}</h4>
                                </div>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <p>Create a new account</p>

                                    <div class="form-floating mb-4">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            placeholder="Full Name" value="{{ old('name') }}" />
                                        <label for="name">Full Name</label>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="Email Address" value="{{ old('email') }}" />
                                        <label for="email">Email Address</label>
                                        @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            placeholder="Password" />
                                        <label for="password">Password</label>
                                        @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input type="password" name="password_confirmation" class="form-control"
                                            id="password_confirmation" placeholder="Confirm Password" />
                                        <label for="password_confirmation">Confirm Password</label>
                                    </div>

                                    <div class="text-center pt-1 mb-5 pb-1">
                                        <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3"
                                            type="submit">
                                            Register
                                        </button>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-center pb-4">
                                        <p class="mb-0 me-2">Already have an account?</p>
                                        <a href="/login" class="btn btn-outline-danger">Login</a>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div> <!-- End Row -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection