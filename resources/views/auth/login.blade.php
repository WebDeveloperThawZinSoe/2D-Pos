@extends('web.master')
@section('body')
<style>
.gradient-custom-2 {
    /* fallback for old browsers */
    background: #fccb90;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
}

/* input {
    border: 1px solid gray !important;
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
        height: 110vh !important;
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
                        <div class="col-lg-3">

                        </div>
                        <div class="col-lg-6">
                            <div class="card-body p-md-5 mx-md-4">

                                <div class="text-center">
                                    <img style="width:100px !important;height:100px !important;"
                                        src="{{asset('logo.png')}}" style="width: 185px;" alt="logo">
                                    <br>
                                    <h4 class="mt-1 mb-5 pb-1">Weclome From {{env('app_name')}} </h4>
                                </div>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    @if (session('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('error') }}
                                    </div>
                                    @endif
                                    <p>Login to your account</p>

                                    <div class="form-floating mb-4">
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Email address" value="{{ old('email') }}" />
                                        <label for="email">Email</label>
                                        @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Password" />
                                        <label for="password">Password</label>
                                        @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="text-center pt-1 mb-5 pb-1">
                                        <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3"
                                            type="submit">
                                            Log in
                                        </button>
                                        <!-- <a class="text-muted" href="#!">Forgot password?</a> -->
                                    </div>

                                   
                                </form>


                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection