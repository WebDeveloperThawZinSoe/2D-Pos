@extends('web.master')

@section('body')
<div class="p-3">
    <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
        <form action="{{ url()->current() }}" method="GET">
            
            <div class="shadow rounded p-4">
                <h5>စာရင်းသွင်းလိုသည့် Date ကိုရွေးပါ</h5>

                <div class="row my-3 d-flex justify-content-center">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-3">
                                ရက်စွဲ
                            </div>
                            <div class="col-6">
                                <input type="date" name="get_date" class="form-control" value="{{ request('get_date', now()->toDateString()) }}" required>
                            </div>
                            <div class="col-3">
                                <select name="get_am_pm" class="form-control" required>
                                    <option value="">-- AM/PM --</option>
                                    <option value="am" {{ request('get_am_pm') == 'am' ? 'selected' : '' }}>AM</option>
                                    <option value="pm" {{ request('get_am_pm') == 'pm' ? 'selected' : '' }}>PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row my-3 d-flex justify-content-end">
                    <div class="col-4">
                        <button type="submit" class="btn btn-warning form-control">OK</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
