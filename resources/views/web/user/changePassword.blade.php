@extends('web.master')

@section('body')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<section class="h-100 gradient-form">
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1" style="background-color: #eee;">
            <div style="padding:20px">

                <h3 class="text-center mb-4">စကားဝှက်ပြောင်းရန်</h3>

                <form method="POST" action="{{ route('user.password.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="old_password" class="form-label">အဟောင်း စကားဝှက်</label>
                        <input type="password" name="old_password" id="old_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">အသစ် စကားဝှက်</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">အသစ် စကားဝှက် အတည်ပြုပါ</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">စကားဝှက် ပြောင်းမည်</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection
