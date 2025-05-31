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
                                <input type="date" name="get_date" class="form-control"
                                    value="{{ request('get_date', now()->toDateString()) }}" required>
                            </div>
                            @php
                            $currentHour = now()->format('H'); // 24-hour format
                            $currentMinute = now()->format('i'); // minutes
                            $autoPm = ($currentHour >= 12 || ($currentHour == 12 && $currentMinute > 01)) ? 'pm' : 'am';
                            $selectedAmPm = request('get_am_pm', $autoPm);
                            @endphp

                            <div class="col-3">
                                <select name="get_am_pm" class="form-control" required>
                                    <option value="">-- AM/PM --</option>
                                    <option value="am" {{ $selectedAmPm == 'am' ? 'selected' : '' }}>AM</option>
                                    <option value="pm" {{ $selectedAmPm == 'pm' ? 'selected' : '' }}>PM</option>
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