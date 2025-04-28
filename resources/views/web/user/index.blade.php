@extends('web.master')

@section('body')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Default styles (desktop and bigger screens) */
td {
    padding: 12px;
    font-size: 1rem;
}

button {
    padding: 12px 0;
    font-size: 1rem;
    margin: 0;
}

/* Mobile styles */
@media (max-width: 576px) {
    td {
        padding: 2px !important;
        font-size: 0.75rem !important;
    }

    button {
        padding: 6px 4px !important;
        /* top/bottom 6px, left/right 4px */
        font-size: 0.75rem !important;
        margin: 2px !important;
        /* small margin around button */
    }

    .d-flex.flex-column.gap-1 {
        gap: 2px !important;
    }

    .table-responsive {
        margin: 0 !important;
        padding: 0 !important;
    }

    table {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
<?php
use Carbon\Carbon;

$timezone = 'Asia/Yangon';

// Load settings
$setting = App\Models\Setting::first();
$am_open = $setting->open_time_am;  // example: '08:00:00'
$am_close = $setting->close_time_am; // example: '11:59:59'
$pm_open = $setting->open_time_pm;  // example: '13:00:00'
$pm_close = $setting->close_time_pm; // example: '17:00:00'

// Current time
$now = Carbon::now($timezone);

// Today’s open and close times
$today_am_open = Carbon::createFromFormat('H:i:s', $am_open, $timezone)->setDate($now->year, $now->month, $now->day);
$today_am_close = Carbon::createFromFormat('H:i:s', $am_close, $timezone)->setDate($now->year, $now->month, $now->day);
$today_pm_open = Carbon::createFromFormat('H:i:s', $pm_open, $timezone)->setDate($now->year, $now->month, $now->day);
$today_pm_close = Carbon::createFromFormat('H:i:s', $pm_close, $timezone)->setDate($now->year, $now->month, $now->day);

$alert = null;

// Check current section
if ($now->between($today_am_open, $today_am_close)) {
    $current_date = $now->toDateString();
    $current_section = 'AM';
} elseif ($now->between($today_pm_open, $today_pm_close)) {
    $current_date = $now->toDateString();
    $current_section = 'PM';
} else {
    // Time is outside of AM and PM
    if ($now->lt($today_am_open)) {
        // Before AM opens today
        $current_date = $now->toDateString();
        $current_section = 'AM';
        $alert = 'Currently closed. Will open in AM session.';
    } elseif ($now->gt($today_pm_close)) {
        // After PM closes, move to next day AM
        $current_date = $now->copy()->addDay()->toDateString();
        $current_section = 'AM';
        $alert = 'Closed for today. Will open tomorrow in AM session.';
    } elseif ($now->gt($today_am_close) && $now->lt($today_pm_open)) {
        // Between AM close and PM open (lunch break)
        $current_date = $now->toDateString();
        $current_section = 'PM';
        $alert = 'Currently closed. Will open in PM session.';
    } else {
        // Fallback
        $current_date = $now->toDateString();
        $current_section = 'Closed';
        $alert = 'Currently closed.';
    }
}

// Output
// echo "Date: " . $current_date . "<br>";
// echo "Section: " . $current_section;
// if ($alert) {
//     echo " <span style='color:red;'>(" . $alert . ")</span>";
// }
?>

<section class="h-100 gradient-form">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3" style="background-color: #eee;">
            <div style="padding:20px">


                @if (!empty($alert))
                <div class="alert alert-danger text-center">
                    {{ $alert }}
                </div>
                @endif

                <p><span style="padding:5px;background:green;color:white">နာမည်</span> - {{ Auth::user()->name }}
                    ({{ Auth::user()->email }})</p>
                <p><span style="padding:5px;background:green;color:white">သွင်းမည့်အချိန်</span> - {{ $current_date }}
                    {{ $current_section }}</p>
                <p><span style="padding:5px;background:red;color:white">ပိတ်ချိန်</span> -
                    @if ($current_section == 'AM')
                    {{ $setting->close_time_am }} AM
                    @elseif ($current_section == 'PM')
                    {{ $setting->close_time_pm }} PM
                    @else
                    ပိတ်နေပါသည်။
                    @endif
                </p>
                <p><span style="padding:5px;background:green;color:white">တစ်ကွက်အများဆုံးခွင့်ပြုငွေ</span> - <span
                        style="color:blue;">{{ number_format(Auth::user()->max_limit) }} Ks</span></p>

                <form id="submit_2d_form" action="/user/number_store" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $current_date }}">
                    <input type="hidden" name="section" value="{{ $current_section }}">

                    <div class="row my-4 justify-content-center">
                        <div class="col-12">
                            <div class="mb-3 row">
                                <div class="col-7">
                                    <input type="text"
                                        class="form-control form-control-lg text-center @error('number') is-invalid @enderror"
                                        id="add_2D_number_input" name="number" placeholder="နံပါတ်ထည့်ပါ..."
                                        onkeyup="change_format_type(this.value)" onkeydown="add_2D_number(this)"
                                        required maxlength="30">
                                    @error('number')
                                    <div class="invalid-feedback text-start">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-5">
                                    <input type="number"
                                        class="form-control form-control-lg text-center @error('price') is-invalid @enderror"
                                        id="add_2D_number_input_price" name="price" placeholder="တင်ငွေရိုက် ထည့်ပါ..."
                                        required max="{{ Auth::user()->max_limit }}">
                                    @error('price')
                                    <div class="invalid-feedback text-start">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center bg-white shadow-sm">
                                <tbody>
                                    <tr>
                                        <td><button type="button" onclick="key_enter('del')"
                                                class="btn btn-danger w-100 py-3">ဖျက်</button></td>
                                        <td><button type="button" onclick="key_enter('P')"
                                                class="btn btn-primary w-100 py-3">အပါ</button></td>
                                        <td><button type="button" onclick="key_enter('R')"
                                                class="btn btn-primary w-100 py-3">အာ</button></td>
                                        <td><button type="button" onclick="key_enter('A')"
                                                class="btn btn-primary w-100 py-3">အပူး</button></td>
                                        <td><button type="button" onclick="key_enter('F')"
                                                class="btn btn-warning w-100 py-3">ထိပ်</button></td>
                                    </tr>
                                    <tr>
                                        <td><button type="button" onclick="key_enter('7')"
                                                class="btn btn-light w-100 py-3">7</button></td>
                                        <td><button type="button" onclick="key_enter('8')"
                                                class="btn btn-light w-100 py-3">8</button></td>
                                        <td><button type="button" onclick="key_enter('9')"
                                                class="btn btn-light w-100 py-3">9</button></td>
                                        <td><button type="button" onclick="key_enter('S')"
                                                class="btn btn-info w-100 py-3">စုံ</button></td>
                                        <td><button type="button" onclick="key_enter('f')"
                                                class="btn btn-warning w-100 py-3">နောက်</button></td>
                                    </tr>
                                    <tr>
                                        <td><button type="button" onclick="key_enter('4')"
                                                class="btn btn-light w-100 py-3">4</button></td>
                                        <td><button type="button" onclick="key_enter('5')"
                                                class="btn btn-light w-100 py-3">5</button></td>
                                        <td><button type="button" onclick="key_enter('6')"
                                                class="btn btn-light w-100 py-3">6</button></td>
                                        <td><button type="button" onclick="key_enter('M')"
                                                class="btn btn-secondary w-100 py-3">မ</button></td>
                                        <td class="d-flex flex-column gap-1">
                                            <button type="button" onclick="key_enter('B')"
                                                class="btn btn-dark w-100 py-1">ဘရိတ်</button>
                                            <button type="button" onclick="key_enter('N')"
                                                class="btn btn-dark w-100 py-1">နက္ခ</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><button type="button" onclick="key_enter('1')"
                                                class="btn btn-light w-100 py-3">1</button></td>
                                        <td><button type="button" onclick="key_enter('2')"
                                                class="btn btn-light w-100 py-3">2</button></td>
                                        <td><button type="button" onclick="key_enter('3')"
                                                class="btn btn-light w-100 py-3">3</button></td>
                                        <td rowspan="2">
                                            <button type="button" onclick="key_enter(' ')"
                                                class="btn btn-dark w-100 py-3">Space</button>
                                        </td>
                                        <td class="d-flex flex-column gap-1">
                                            <button type="button" onclick="key_enter('W')"
                                                class="btn btn-secondary w-100 py-1">ပါ၀ါ</button>
                                            <button type="button" onclick="key_enter('X')"
                                                class="btn btn-secondary w-100 py-1">ညီကို</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <button type="button" onclick="key_enter('0')"
                                                class="btn btn-light w-100 py-3">0</button>
                                        </td>
                                        <td>
                                            <button type="button" onclick="key_enter('00')"
                                                class="btn btn-light w-100 py-3">00</button>
                                        </td>
                                        <td>
                                            <button type="button" onclick="key_enter('Z')"
                                                class="btn btn-info w-100 py-3">ခွေ</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-4">သိမ်းမည်</button>
                </form>

                <script>
                function key_enter(data) {
                    const add_sound = new Audio("{{ asset('sound/type.mp3') }}");
                    add_sound.play();
                    let inputField = document.getElementById('add_2D_number_input');
                    let inputField2 = document.getElementById('add_2D_number_input_price');
                    if (data === "del") {
                        inputField.value = "";
                        inputField2.value = "";
                    } else {
                        inputField.value += data;
                    }
                }
                </script>

                <hr>
                <h5> {{ $current_date }} {{ $current_section }} - @if (!empty($alert)) နောက် @else ယခု @endif Section
                    အတွက်အော်ဒါ</h5>

                @php
                $orders = App\Models\Order::where('user_id', Auth::id())
                ->where('date', $current_date)
                ->where('section', $current_section)
                ->orderBy('id', 'desc')
                ->get();
                @endphp

                <table class="table table-bordered text-center bg-white shadow-sm">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Total Number</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_type }}</td>
                            <td>{{ App\Models\OrderDetail::where('order_id', $order->id)->count() }}</td>
                            <td>{{ number_format($order->price) }} Ks</td>
                            <td>
                                @if ($order->user_order_status == 0)
                                <form action="/user/order/status" method="post"  style="display:inline-block !important;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$order->id}}">
                                    <button type="submit" class="btn btn-success btn-sm">
                                  
                                    <i class="fas fa-check"></i>
                                </button>
                                </form>
                                <a class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#orderDetailModal{{ $order->id }}">
                                    <i class="fas fa-info-circle"></i>
                                </a> 
                                <form action="/user/order/delete" method="post"  style="display:inline-block !important;">
                                @csrf
                                <input type="hidden" name="id" value="{{$order->id}}">
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this order?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                @else
                                <a class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#orderDetailModal{{ $order->id }}">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal for Order Details -->
                        @include('web.user.partials.order-detail-modal', ['order' => $order])



                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>



@endsection