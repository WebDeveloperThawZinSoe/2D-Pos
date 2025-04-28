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

if ($now >= $am_open && $now <= $am_close) { $current_section='AM' ; } elseif ($now>= $pm_open && $now <= $pm_close) {
        $current_section='PM' ; } else { $current_section='Closed' ; } @endphp <style>
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
        padding: 6px 4px !important; /* top/bottom 6px, left/right 4px */
        font-size: 0.75rem !important;
        margin: 2px !important; /* small margin around button */
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



        <section class="h-100 gradient-form">
            <div class="row">
                <div class="col-12  col-md-6 offset-md-3" style="background-color: #eee;">
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

                        <form id="submit_2d_form" action="/user/number_store" method="POST">
                            @csrf
                            <input type="hidden">
                            <div class="row my-4 justify-content-center">
                                <div class="col-12">
                                    <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                                    <input type="hidden" name="section" value="{{ $current_section }}">

                                    <div class="mb-3 row">
                                        <div class="col-7">
                                            <input type="text"
                                                class="form-control form-control-lg text-center @error('number') is-invalid @enderror"
                                                id="add_2D_number_input" name="number"
                                                placeholder="နံပါတ်ထည့်ပါ..."
                                                onkeyup="change_format_type(this.value)" onkeydown="add_2D_number(this)"
                                                required maxlength="30">
                                            @error('number')
                                            <div class="invalid-feedback text-start">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-5">
                                        <input type="number"
                                        class="form-control form-control-lg text-center @error('price') is-invalid @enderror"
                                        id="add_2D_number_input_price" name="price"
                                        placeholder="တင်ငွေရိုက် ထည့်ပါ..."
                                        required
                                        
                                        max="{{ Auth::user()->max_limit }}">

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

                        // function change_format_type(value) {
                        //     let formattedValue = '';
                        //     let inputLength = value.length;

                        //     // Loop through each character in the input value
                        //     for (let i = 0; i < inputLength; i++) {
                        //         let char = value[i];

                        //         // If it's an alphabet, append it followed by a comma
                        //         if (/[a-zA-Z]/.test(char)) {
                        //             formattedValue += char + ',';
                        //         }
                        //         // If it's a number, append it directly to handle digits
                        //         else if (/\d/.test(char)) {
                        //             if (i + 1 < inputLength && /\d/.test(value[i + 1])) {
                        //                 formattedValue += char + value[i + 1] + ',';
                        //                 i++; // Skip the next digit
                        //             } else {
                        //                 formattedValue += char + ',';
                        //             }
                        //         }
                        //     }

                        //     // Trim the last comma
                        //     if (formattedValue.endsWith(',')) {
                        //         formattedValue = formattedValue.slice(0, -1);
                        //     }

                        //     document.getElementById('add_2D_number_input').value = formattedValue;
                        // }
                        </script>



                    </div>
                </div>
            </div>
        </section>




        @endsection