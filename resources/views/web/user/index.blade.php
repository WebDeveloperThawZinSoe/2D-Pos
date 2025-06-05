@extends('web.master')

@section('body')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
td {
    line-height: 40px !important;
}

.responsive-section-header {
    font-size: 1.25rem;
}

@media (max-width: 600px) {
    .responsive-section-header {
        font-size: 1rem;
    }
}

/* Base Button */
.btnTzs {
    display: inline-block;
    font-weight: 500;
    font-size: 1rem;
    line-height: 1.5;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid transparent;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
    transition:
        background-color 0.2s ease-in-out,
        color 0.2s ease-in-out,
        border-color 0.2s ease-in-out,
        box-shadow 0.2s ease-in-out;
}

/* Prevent layout shift */
.btnTzs:focus,
.btnTzs:active {
    outline: none;
    box-shadow: none;
}

/* Primary */
.btnTzs-primary {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}

.btnTzs-primary:hover,
.btnTzs-primary:focus,
.btnTzs-primary:active {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

/* Info */
.btnTzs-info {
    background-color: #0dcaf0;
    color: #fff;
    border-color: #0dcaf0;
}

.btnTzs-info:hover,
.btnTzs-info:focus,
.btnTzs-info:active {
    background-color: #31d2f2;
    border-color: #25cff2;
}

/* Warning */
.btnTzs-warning {
    background-color: #ffc107;
    color: #212529;
    border-color: #ffc107;
}

.btnTzs-warning:hover,
.btnTzs-warning:focus,
.btnTzs-warning:active {
    background-color: #ffca2c;
    border-color: #ffcd39;
}

/* Danger */
.btnTzs-danger {
    background-color: #dc3545;
    color: #fff;
    border-color: #dc3545;
}

.btnTzs-danger:hover,
.btnTzs-danger:focus,
.btnTzs-danger:active {
    background-color: #bb2d3b;
    border-color: #b02a37;
}

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
                @php
                $date = session('selected_date');
                $section = session('selected_section');
                @endphp
                <div class="d-none d-sm-block">
                    <span>
                        <p><span style="padding:5px;background:green;color:white">နာမည်</span> -
                            {{ Auth::user()->name }} ({{ Auth::user()->email }})</p>
                        <p><span style="padding:5px;background:green;color:white">သွင်းမည့်အချိန်</span> - {{ $date }}
                            {{ $section }} </p>
                        <p><span style="padding:5px;background:green;color:white">ကော်မရှင် / ဆ</span> -
                            {{ Auth::user()->commission }}% / {{ Auth::user()->rate ?? 0 }} </p>
                        <p><span style="padding:5px;background:red;color:white">ပိတ်ချိန်</span> -
                            @if($section == 'am')
                            {{ \Carbon\Carbon::parse(Auth::user()->end_am)->format('h:i A') }}
                            @else
                            {{ \Carbon\Carbon::parse(Auth::user()->end_pm)->format('h:i A') }}
                            @endif
                        </p>
                        <p><span style="padding:5px;background:green;color:white">တစ်ကွက်အများဆုံးခွင့်ပြုငွေ</span> -
                            <span style="color:blue;">{{ number_format(Auth::user()->max_limit) }}</span>
                        </p>
                    </span>

                    @php
                    $serverTime = now()->setTimezone('Asia/Yangon');
                    @endphp

                    <p>
                        Current Server Time:
                        <span id="server-time" data-time="{{ $serverTime->format('Y-m-d H:i:s') }}">
                            {{ $serverTime->format('Y-m-d H:i:s') }}
                        </span>
                    </p>

                    <script>
                    // Parse initial server time
                    const serverTimeEl = document.getElementById('server-time');
                    let serverTime = new Date(serverTimeEl.dataset.time.replace(/-/g, '/'));

                    // Update the time every second
                    setInterval(() => {
                        serverTime.setSeconds(serverTime.getSeconds() + 1);

                        // Format to YYYY-MM-DD HH:mm:ss
                        const formatted = serverTime.getFullYear() + '-' +
                            String(serverTime.getMonth() + 1).padStart(2, '0') + '-' +
                            String(serverTime.getDate()).padStart(2, '0') + ' ' +
                            String(serverTime.getHours()).padStart(2, '0') + ':' +
                            String(serverTime.getMinutes()).padStart(2, '0') + ':' +
                            String(serverTime.getSeconds()).padStart(2, '0');

                        serverTimeEl.textContent = formatted;
                    }, 1000);
                    </script>
                </div>




                @php
                use Illuminate\Support\Facades\Auth;
                use App\Models\CloseNumber;

                $blockNumbers = CloseNumber::where("date", $date)
                ->where("section", $section)
                ->where("manager_id", Auth::user()->manager->id)
                ->first();
                @endphp

                @if ($blockNumbers)
                <p class="alert alert-info">
                    ယခု Section အတွက်ဒိုင်မှပိတ်ထားသော <b>{{ $blockNumbers->number }}</b> ပိတ်သီးများမှာ အော်ဒါတင်ပါက
                    System မှ အလိုအလျှောက်ဖြတ်ထုတ်သွားပါမည်။
                </p>
                @else
                <p class="alert alert-info d-none d-sm-block"><b>ယခု Section အတွက်ဒိုင်မှပိတ်ထားသော ပိတ်သီးမရှိပါ။</b>
                </p>
                @endif


                @php
                $winNumber = App\Models\WinNumber::where('manager_id',Auth::user()->manager->id)
                ->where('section', session('selected_section'))
                ->where('date', session('selected_date'))
                ->first();

                @endphp



                @php
                $now = Carbon::now('Asia/Yangon');
                $selectedDateTime = null;
                $isClosed = false;

                if ($date && $section) {
                $user = Auth::user();
                $end_am = $user->end_am ?? '11:30:00';
                $end_pm = $user->end_pm ?? '15:30:00';

                $selectedTime = $section === 'am' ? $end_am : $end_pm;

                if ($selectedTime) {
                $selectedDateTime = Carbon::parse($date . ' ' . $selectedTime, 'Asia/Yangon');
                $isClosed = $now->greaterThan($selectedDateTime);
                }
                }
                @endphp
                @if(is_null($winNumber))
                @if(!$isClosed)
                <form id="submit_2d_form" action="/number_store" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="section" value="{{ $section }}">
                    <input type="hidden" name="buy_sell" value="sell">
                    <input type="hidden" name="manager_id" value="{{Auth::user()->manager->id}}">
                    <input type="hidden" name="client" value="{{Auth::user()->id}}">
                    <div class="row my-4 justify-content-center">
                        <div class="col-12">
                            <div class="mb-3 row">
                                <div class="col-12">
                                    <input type="text"
                                        class="form-control form-control-lg text-center @error('number') is-invalid @enderror"
                                        id="add_2D_number_input" name="number"
                                        placeholder="နံပါတ် တင်ငွေရိုက် ထည့်ပါ..." oninput="validate2DNumber(this)"
                                        required maxlength="30">
                                    @error('number')
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
                                                class="btnTzs btnTzs-danger w-100 py-3">ဖျက်</button></td>
                                        <td><button type="button" onclick="key_enter('P')"
                                                class="btnTzs btnTzs-primary w-100 py-3">အပါ</button></td>
                                        <td><button type="button" onclick="key_enter('R')"
                                                class="btnTzs btnTzs-primary w-100 py-3">အာ</button></td>
                                        <td><button type="button" onclick="key_enter('A')"
                                                class="btnTzs btnTzs-primary w-100 py-3">အပူး</button></td>
                                        <td><button type="button" onclick="key_enter('F')"
                                                class="btnTzs btnTzs-warning w-100 py-3">ထိပ်</button></td>
                                    </tr>
                                    <tr>
                                        <td><button type="button" onclick="key_enter('7')"
                                                class="btnTzs btnTzs-light w-100 py-3">7</button></td>
                                        <td><button type="button" onclick="key_enter('8')"
                                                class="btnTzs btnTzs-light w-100 py-3">8</button></td>
                                        <td><button type="button" onclick="key_enter('9')"
                                                class="btnTzs btnTzs-light w-100 py-3">9</button></td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <button type="button" onclick="key_enter('SS')"
                                                    class="btnTzs btnTzs-info w-100 py-3">
                                                    စုံစုံ
                                                </button>
                                                <button type="button" onclick="key_enter('SM')"
                                                    class="btnTzs btnTzs-info w-100 py-3">
                                                    စုံမ
                                                </button>
                                            </div>
                                        </td>

                                        <td><button type="button" onclick="key_enter('f')"
                                                class="btnTzs btnTzs-warning w-100 py-3">နောက်</button></td>
                                    </tr>
                                    <tr>
                                        <td><button type="button" onclick="key_enter('4')"
                                                class="btnTzs btnTzs-light w-100 py-3">4</button></td>
                                        <td><button type="button" onclick="key_enter('5')"
                                                class="btnTzs btnTzs-light w-100 py-3">5</button></td>
                                        <td><button type="button" onclick="key_enter('6')"
                                                class="btnTzs btnTzs-light w-100 py-3">6</button></td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <button type="button" onclick="key_enter('MM')"
                                                    class="btnTzs btnTzs-info w-100 py-3">
                                                    မမ
                                                </button>
                                                <button type="button" onclick="key_enter('MS')"
                                                    class="btnTzs btnTzs-info w-100 py-3">
                                                    မစုံ
                                                </button>
                                            </div>
                                        </td>

                                        <td class="d-flex flex-column gap-1">
                                            <button type="button" onclick="key_enter('B')"
                                                class="btnTzs btnTzs-warning w-100 py-1">ဘရိတ်</button>
                                            <button type="button" onclick="key_enter('N')"
                                                class="btnTzs btnTzs-warning w-100 py-1">နက္ခ</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><button type="button" onclick="key_enter('1')"
                                                class="btnTzs btnTzs-light w-100 py-3">1</button></td>
                                        <td><button type="button" onclick="key_enter('2')"
                                                class="btnTzs btnTzs-light w-100 py-3">2</button></td>
                                        <td><button type="button" onclick="key_enter('3')"
                                                class="btnTzs btnTzs-light w-100 py-3">3</button></td>
                                        <td rowspan="2">
                                            <button type="button" class="btnTzs btnTzs-info w-100 py-3"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                အကွက်မတူငွေတူ
                                            </button>

                                            <br>

                                            <button onclick="key_enter('singleDel')" type="button"
                                                class="btnTzs btnTzs-danger w-100 py-3 mt-4">
                                                Del.
                                            </button>

                                        </td>
                                        <td class="d-flex flex-column gap-1">
                                            <button type="button" onclick="key_enter('W')"
                                                class="btnTzs btnTzs-warning w-100 py-1">ပါ၀ါ</button>
                                            <button type="button" onclick="key_enter('X')"
                                                class="btnTzs btnTzs-warning w-100 py-1">ညီကို</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <button type="button" onclick="key_enter('0')"
                                                class="btnTzs btnTzs-light w-100 py-3">0</button>
                                        </td>
                                        <td>
                                            <button type="button" onclick="key_enter('00')"
                                                class="btnTzs btnTzs-light w-100 py-3">00</button>
                                        </td>
                                        <!-- <td>
                                            <button type="button" onclick="key_enter('ZZ')"
                                                class="btnTzs btnTzs-warning w-100 py-3">ခွေပူး</button>
                                                 <button type="button" onclick="key_enter('Z')"
                                                class="btnTzs btnTzs-warning w-100 py-3">ခွေ</button>
                                        </td> -->
                                        <td class="d-flex flex-column gap-1">
                                            <button type="button" onclick="key_enter('ZZ')"
                                                class="btnTzs btnTzs-warning w-100 py-1">ခွေပူး</button>
                                            <button type="button" onclick="key_enter('Z')"
                                                class="btnTzs btnTzs-warning w-100 py-1">ခွေ</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-4">သိမ်းမည်</button>
                </form>
                @else
                <div class="alert alert-danger text-center">
                    <p>ပိတ်ချိန်ဖြစ်နေ၍ {{ $date }} {{ $section }} အတွက် အော်ဒါတင်၍မရပါ။</p>
                </div>
                @endif
                @else
                @php
                $isWinOrNot = App\Models\OrderDetail::where('user_id', Auth::id())
                ->where('date', $date)
                ->where('section', $section)
                ->where('manager_id', Auth::user()->manager->id ?? null)
                ->where('number', $winNumber->number ?? null)
                ->count() ?? 0;
                @endphp

                @if ($winNumber)
                <div class="alert alert-success text-center">
                    <h5>ဂဏန်း <b>{{ $winNumber->number }}</b> ပေါက်ပါပြီ။</h5>
                </div>
                @endif
                @if(isset($isWinOrNot))
                @if ($isWinOrNot > 0)
                <div class="alert alert-success text-center">
                    ဂုဏ်ယူပါတယ် သင်ပေါက်ပါပြီ။
                </div>
                @endif
                @endif
                @php

                @endphp
                @endif


                <script>
                function key_enter(data) {
                    const add_sound = new Audio("{{ asset('sound/type.mp3') }}");
                    add_sound.play();

                    let inputField = document.getElementById('add_2D_number_input');
                    let inputField2 = document.getElementById('add_2D_number_input_price');

                    if (data === "del") {
                        inputField.value = "";
                        if (inputField2) inputField2.value = "";
                    } else if (data === "singleDel") {
                        inputField.value = inputField.value.slice(0, -1);
                        if (inputField2) inputField2.value = inputField2.value.slice(0, -1);
                    } else {
                        inputField.value += data;
                        applyCustomReplacements(inputField);
                    }

                    validate2DNumber(inputField);
                }

                // ✅ Handle direct typing with keyboard
                document.addEventListener("DOMContentLoaded", function() {
                    const inputField = document.getElementById('add_2D_number_input');

                    inputField.addEventListener('input', function() {
                        applyCustomReplacements(inputField);
                        validate2DNumber(inputField);
                    });
                });

                // ✅ Custom pattern replacements
                function applyCustomReplacements(inputField) {
                    const replacements = [{
                            pattern: /^\/\//,
                            replacement: 'W'
                        },
                        {
                            pattern: /^\*\*/,
                            replacement: 'N'
                        },
                        {
                            pattern: /^\/\*/,
                            replacement: 'X'
                        },
                        {
                            pattern: /^(\d{2})\*$/,
                            replacement: '$1R'
                        }, // e.g. 12* -> 12R
                        {
                            pattern: /^(\d)\*$/,
                            replacement: '$1F'
                        }, // e.g. 1* -> 1F
                        {
                            pattern: /^\*(\d)$/,
                            replacement: 'F$1'
                        }, // e.g. *1 -> F1
                        {
                            pattern: /^(\d)\+$/,
                            replacement: '$1B'
                        }, // e.g. 1+ -> 1B
                        {
                            pattern: /^(\d{2})\+$/,
                            replacement: '$1B'
                        }, // e.g. 12+ -> 12B
                        {
                            pattern: /^(\d{3})\+$/,
                            replacement: '$1Z'
                        }, // e.g. 123+ -> 123Z
                        {
                            pattern: /^(\d{3})\+\+$/,
                            replacement: '$1ZZ'
                        }, // e.g. 123++ -> 123ZZ
                        {
                            pattern: /^\+\+$/,
                            replacement: 'SS'
                        },
                        {
                            pattern: /^\-\-$/,
                            replacement: 'MM'
                        },
                        {
                            pattern: /^\+\-$/,
                            replacement: 'SM'
                        },
                        {
                            pattern: /^\-\+$/,
                            replacement: 'MS'
                        },
                    ];

                    let value = inputField.value;

                    for (let rule of replacements) {
                        if (rule.pattern.test(value)) {
                            inputField.value = value.replace(rule.pattern, rule.replacement);
                            break; // stop at first match
                        }
                    }
                }
                </script>

                <script>
                function validate2DNumber(input) {
                    let value = input.value;

                    // Replace multiple spaces with a single space
                    value = value.replace(/\s+/g, ' ');

                    // Allow only one space and digits after the space
                    let parts = value.split(' ');
                    if (parts.length > 2) {
                        parts = [parts[0], parts[1]]; // limit to two parts only
                    }

                    // Only allow digits after the space
                    if (parts.length === 2) {
                        parts[1] = parts[1].replace(/\D/g, '');
                    }

                    input.value = parts.join(' ');
                }
                </script>


                <hr>


                <h5 class="responsive-section-header"> {{ $date }} {{ $section }} - @if (!empty($alert)) နောက် @else ယခု
                    @endif Section အတွက်အော်ဒါ</h5>


                @php
                $orders = App\Models\Order::where('user_id', Auth::id())
                ->where('date', $date)
                ->where('section', $section)
                ->orderBy('id', 'desc')
                ->get();
                @endphp

                <form id="bulk-action-form" action="/orders/bulk-action" method="POST">
                    @csrf
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" name="action" value="confirm"
                            class="btn btn-success btn-sm rounded-pill shadow-sm px-4">
                            ✅ Bulk Confirm
                        </button>
                        <button type="submit" name="action" value="delete"
                            class="btn btn-danger btn-sm rounded-pill shadow-sm px-4"
                            onclick="return confirm('Are you sure you want to delete selected orders?')">
                            🗑️ Bulk Delete
                        </button>
                    </div>
                    <br>
                    <table class="table table-bordered text-center bg-white shadow-sm">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Type</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalPrice = 0; @endphp
                            @foreach ($orders as $order)
                            @if($order->user_order_status == 1 && $order->status == 1)
                            @php $totalPrice += $order->price; @endphp
                            @endif
                            <tr>
                                <td>
                                     @if ($order->user_order_status == 0)
                                    <input type="checkbox" class="order-checkbox" name="order_ids[]"
                                        value="{{ $order->id }}">
                                        @endif
                                    </td>
                                <td>{{ $order->order_type }}
                                    ({{ App\Models\OrderDetail::where('order_id', $order->id)->count() }})</td>
                                <td>{{ number_format($order->price) }}</td>
                                <td>
                                    <div class="d-flex flex-column gap-1 align-items-center">
                                        @if ($order->user_order_status == 0)
                                        <form action="/order/status" method="post" class="m-0">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $order->id }}">
                                            <button type="submit"
                                                class="btn btn-success btn-sm rounded-pill shadow-sm px-3"
                                                style="min-width: 90px;">
                                                <i class="fas fa-check me-1"></i> Confirm
                                            </button>
                                        </form>

                                        <a class="btn btn-info btn-sm rounded-pill shadow-sm text-white px-3"
                                            data-bs-toggle="modal" data-bs-target="#orderDetailModal{{ $order->id }}"
                                            style="min-width: 90px;">
                                            <i class="fas fa-info-circle me-1"></i> Details
                                        </a>

                                        <form action="/order/delete" method="post" class="m-0"
                                            onsubmit="return confirm('Are you sure you want to delete this order?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $order->id }}">
                                            <button type="submit"
                                                class="btn btn-danger btn-sm rounded-pill shadow-sm px-3"
                                                style="min-width: 90px;">
                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                            </button>
                                        </form>
                                        @else
                                        <a class="btn btn-info btn-sm rounded-pill shadow-sm text-white px-3"
                                            data-bs-toggle="modal" data-bs-target="#orderDetailModal{{ $order->id }}"
                                            style="min-width: 90px;">
                                            <i class="fas fa-info-circle me-1"></i> Details
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            @include('web.user.partials.order-detail-modal', ['order' => $order])
                            @endforeach

                            <tr>
                                <td colspan="2">စုစုပေါင်းထိုးငွေ</td>
                                <td colspan="2">{{ number_format($totalPrice) }}</td>
                            </tr>

                            <tr>
                                <td colspan="2">ဒဲ့ပေါက်</td>
                                <td colspan="2">
                                    @php
                                    $isWinOrNot = App\Models\OrderDetail::where('user_id', Auth::id())
                                    ->where('date', $date)
                                    ->where('section', $section)
                                    ->where('manager_id', Auth::user()->manager->id ?? null)
                                    ->where('number', $winNumber->number ?? null)
                                    ->count() ?? 0;
                                    @endphp
                                    @if($isWinOrNot > 0)
                                    @php
                                    $WinNumber = App\Models\OrderDetail::where('user_id', Auth::id())
                                    ->where('date', $date)
                                    ->where('section', $section)
                                    ->where('manager_id', Auth::user()->manager->id ?? null)
                                    ->where('number', $winNumber->number ?? null)
                                    ->first();
                                    $Price = $WinNumber->price ?? 0;
                                    $Rate = Auth::user()->rate ?? 1;
                                    $total = $Price * $Rate;
                                    echo number_format($total);
                                    @endphp
                                    @else
                                    0
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">ကော်မရှင်</td>
                                <td colspan="2">
                                    @php
                                    $commission = Auth::user()->commission ?? 0;
                                    $amount = $totalPrice * $commission / 100;
                                    echo number_format($amount);
                                    @endphp
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">ကျသင့်ငွေ</td>
                                <td colspan="2">
                                    @php
                                    $WinNumber = App\Models\OrderDetail::where('user_id', Auth::id())
                                    ->where('date', $date)
                                    ->where('section', $section)
                                    ->where('manager_id', Auth::user()->manager->id ?? null)
                                    ->where('number', $winNumber->number ?? null)
                                    ->first();
                                    $Price = $WinNumber->price ?? 0;
                                    $Rate = Auth::user()->rate ?? 1;
                                    $total = $Price * $Rate;
                                    $grandTotal = $totalPrice - $total - $amount;
                                    echo number_format($grandTotal * -1);
                                    @endphp
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <a href="/user/report/daily"
                                        class="btn btn-outline-primary w-100 rounded-pill shadow-sm">
                                        📅 စာရင်းချုပ် နေ့စဉ်
                                    </a>
                                </td>
                                <td colspan="2">
                                    <a href="/user/report/weekly"
                                        class="btn btn-outline-primary w-100 rounded-pill shadow-sm">
                                        📆 စာရင်းချုပ် အပတ်စဉ်
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <a href="/user/report/monthly"
                                        class="btn btn-outline-primary w-100 rounded-pill shadow-sm">
                                        🗓️ စာရင်းချုပ် လစဉ်
                                    </a>
                                </td>
                                <td colspan="2">
                                    <a href="/user/report/yearly"
                                        class="btn btn-outline-primary w-100 rounded-pill shadow-sm">
                                        🗓️ စာရင်းချုပ် နစ်စဉ်
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    
                </form>

                <script>
                document.getElementById('select-all').addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.order-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
                </script>


                <div class="d-block d-sm-none">
                    <br>
                    <hr>
                    <span>
                        <p><span style="padding:5px;background:green;color:white">နာမည်</span> -
                            {{ Auth::user()->name }} ({{ Auth::user()->email }})</p>
                        <p><span style="padding:5px;background:green;color:white">သွင်းမည့်အချိန်</span> - {{ $date }}
                            {{ $section }} </p>
                        <p><span style="padding:5px;background:green;color:white">ကော်မရှင် / ဆ</span> -
                            {{ Auth::user()->commission }}% / {{ Auth::user()->rate ?? 0 }} </p>
                        <p><span style="padding:5px;background:red;color:white">ပိတ်ချိန်</span> -
                            @if($section == 'am')
                            {{ \Carbon\Carbon::parse(Auth::user()->end_am)->format('h:i A') }}
                            @else
                            {{ \Carbon\Carbon::parse(Auth::user()->end_pm)->format('h:i A') }}
                            @endif
                        </p>
                        <p><span style="padding:5px;background:green;color:white">တစ်ကွက်အများဆုံးခွင့်ပြုငွေ</span> -
                            <span style="color:blue;">{{ number_format(Auth::user()->max_limit) }}</span>
                        </p>
                    </span>

                    @php
                    $serverTime = now()->setTimezone('Asia/Yangon');
                    @endphp

                    <p>
                        Current Server Time:
                        <span id="server-time" data-time="{{ $serverTime->format('Y-m-d H:i:s') }}">
                            {{ $serverTime->format('Y-m-d H:i:s') }}
                        </span>
                    </p>

                    <script>
                    // Parse initial server time
                    const serverTimeEl = document.getElementById('server-time');
                    let serverTime = new Date(serverTimeEl.dataset.time.replace(/-/g, '/'));

                    // Update the time every second
                    setInterval(() => {
                        serverTime.setSeconds(serverTime.getSeconds() + 1);

                        // Format to YYYY-MM-DD HH:mm:ss
                        const formatted = serverTime.getFullYear() + '-' +
                            String(serverTime.getMonth() + 1).padStart(2, '0') + '-' +
                            String(serverTime.getDate()).padStart(2, '0') + ' ' +
                            String(serverTime.getHours()).padStart(2, '0') + ':' +
                            String(serverTime.getMinutes()).padStart(2, '0') + ':' +
                            String(serverTime.getSeconds()).padStart(2, '0');

                        serverTimeEl.textContent = formatted;
                    }, 1000);
                    </script>
                </div>

            </div>
        </div>
    </div>


</section>




<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">အကွက်မတူငွေတူ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="customModalLabel">အကွက်မတူငွေတူ <br> ရွှေးထားသည့်အချိန် -
                        {{ session('selected_date', 'Not set') }} <span style="color:blue;">
                            {{ ucfirst(session('selected_section', 'Not set')) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">
                    <form action="/multi/number_store" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
                        <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
                        <input type="hidden" name="buy_sell" value="sell">
                        <input type="hidden" name="manager_id" value="{{Auth::user()->manager->id}}">
                        <input type="hidden" name="client" value="{{Auth::user()->id}}">

                        <div class="mb-3">
                            <label class="form-label">အကွက်များ * </label>
                            <input type="text" id="patternInput" class="form-control" placeholder="ဥပမာ: 34-23-32-45-32"
                                name="numbers" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ငွေပမာဏ * </label>
                            <input type="text" class="form-control" name="amount" required placeholder="ငွေပမာဏထည့်ပါ">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">ရွှေးသည်</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
document.getElementById('patternInput').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, ''); // Remove non-digits
    let formatted = value.match(/.{1,2}/g)?.join('-') || '';
    e.target.value = formatted;
});
</script>

@endsection