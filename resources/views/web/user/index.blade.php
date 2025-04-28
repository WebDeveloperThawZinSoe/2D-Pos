@extends('web.master')
@section('body')

@php
use Carbon\Carbon;

$setting = App\Models\Setting::first();
$am_open = $setting->open_time_am; // Example: 08:00
$am_close = $setting->close_time_am; // Example: 11:30
$pm_open = $setting->open_time_pm; // Example: 13:00
$pm_close = $setting->close_time_pm; // Example: 17:00

$now = Carbon::now()->format('H:i'); // Current time in "Hour:Minute" 24hr format

if ($now >= $am_open && $now <= $am_close) {
    $current_section = 'AM';
} elseif ($now >= $pm_open && $now <= $pm_close) {
    $current_section = 'PM';
} else {
    $current_section = 'Closed';
}
@endphp

<section
        class="h-100 gradient-form">
        <div class="row">
            <div class="col-10 offset-1 col-md-6 offset-md-3" style="background-color: #eee;">
                <div style="padding:20px">

                    <p>
                        <span style="padding:5px;background:green;color:white"> နာမည် </span> &nbsp; -
                        {{ Auth::user()->name }} ({{ Auth::user()->email }})
                    </p>

                    <p>
                        <span style="padding:5px;background:green;color:white"> သွင်းမည့်အချိန် </span> &nbsp; -
                        {{ now()->format('Y-m-d') }} {{ $current_section }}
                    </p>

                    <p>
                        <span style="padding:5px;background:red;color:white"> ပိတ်ချိန် </span> &nbsp; -
                        @if ($current_section == 'AM')
                        {{ $setting->close_time_am }} {{ $current_section }}
                        @elseif ($current_section == 'PM')
                        {{ $setting->close_time_pm }} {{ $current_section }}
                        @endif
                    </p>

                    <p>
                        <span style="padding:5px;background:green;color:white"> တစ်ကွက်အများဆုံးခွင့်ပြုငွေ </span>
                        &nbsp; -
                        <span style="color:blue;"> {{ number_format(Auth::user()->max_limit) }} Ks </span>
                    </p>

                    <form id="submit_2d_form" action="/lottery_2d_user" method="POST">
                        @csrf

                        <div class="row my-4 justify-content-center">
                            <div class="col-12">

                                <div class="mb-3 row">
                                    <div class="col-8">
                                    <input type="text" class="form-control form-control-lg text-center"
                                        id="add_2D_number_input" placeholder="နံပါတ်ရိုက်ထည့်ပါ..."
                                        onkeyup="change_format_type(this.value)" onkeydown="add_2D_number(this)">
                                    </div>
                                    <div class="col-4">
                                    <input type="text" class="form-control form-control-lg text-center"
                                        id="price" placeholder="တင်ငွေရိုက်ထည့်ပါ..."
                                     >
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
                                                <td><button type="button" onclick="key_enter('F')"
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
                                                    <button type="button" onclick="key_enter('save')"
                                                        class="btn btn-success w-100 h-100 py-4">သိမ်းမည်</button>
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
                        </div>

                    </form>


                </div>
            </div>
        </div>
        </section>
        
        <script>
            function key_enter(data) {
                const add_sound = new Audio("{{ asset('sound/type.mp3') }}");
                add_sound.play();
                if (data == "del") {
                    $("#add_2D_number_input").val("")
                } else if (data == "save") {
                    add_2D_number2($("#add_2D_number_input").val());
                } else {
                    let cur = $("#add_2D_number_input").val();
                    $("#add_2D_number_input").val(cur + data);
                }
            }
        </script>
    
        @endsection