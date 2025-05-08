@extends('web.master')

@section('body')


<div class="row">
    <div class="col-md-4 col-12">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="admin_sell" role="tabpanel" aria-labelledby="pills-home-tab"
                tabindex="0">
                @include('web.dine.add_number_function')
            </div>
        </div>
        <div style="background: greenyellow;">
            <table class="table table-bordered table-info">
                <tr>
                    <td>
                        အရောင်း Sale
                    </td>
                    <td style="min-width:200px;">
                        @php
                        $user_id = Auth::id(); // Add this if not already defined
                        $date = session('selected_date');
                        $section = session('selected_section');

                        $orders = App\Models\Order::where("manager_id", $user_id)
                        ->where("date", $date)
                        ->where("section", $section)
                        ->where("status", 1)->where("buy_sell_type","sell")
                        ->sum('price');
                        @endphp
                        {{number_format($orders)}} Ks
                    </td>
                </tr>

                <tr>
                    <td>
                        ပြန်၀ယ်
                    </td>
                    <td style="min-width:200px;">

                    </td>
                </tr>

                <tr>
                    <td>
                        အရောင်း အ၀ယ်
                    </td>
                    <td style="min-width:200px;">

                    </td>
                </tr>

                <tr>
                    <td>
                        ခေါင်း Limit
                    </td>
                    <td style="min-width:200px;">

                        @php
                        $existingLimit = App\Models\DineHeadLimit::where('manager_id', auth()->id())
                        ->where('section', session('selected_section'))
                        ->where('date', session('selected_date'))
                        ->first();

                        @endphp
                        <form action="/limit/store" method="post" class="d-flex align-items-center gap-2">
                            @csrf
                            <input type="hidden" name="date" value="{{ session('selected_date') }}">
                            <input type="hidden" name="section" value="{{ session('selected_section') }}">
                            <input type="hidden" name="manager_id" value="{{ Auth::user()->id }}">
                            <input type="number" name="limit" class="form-control form-control-sm"
                                placeholder="Enter limit" required value="{{  $existingLimit->amount ?? '' }}">

                            <button type="submit" class="btn btn-sm btn-primary">
                                Submit
                            </button>
                        </form>
                    </td>
                </tr>

                <tr>
                    <td>
                        ပေါက်သီး
                    </td>
                    <td style="min-width:200px;">




                        <form method="POST" action="/admin/set_lucky_number" id="set_lucky_number"
                            style="display: inline;">
                            @csrf
                            <input hidden type="date" class="form-control"
                                value="{{ $request->get_date ?? Carbon\Carbon::now()->format('Y-m-d') }}"
                                onchange="change_section()" name="get_date" id="get_date">


                            <select hidden name="get_am_pm" id="get_am_pm" onchange="change_section()"
                                class="form-control">

                                <option value="am">AM
                                </option>
                                <option value="pm">PM
                                </option>


                            </select>
                            <input type="text" class="form-control" name="number"
                                onchange="document.getElementById('set_lucky_number').submit();" value="">
                        </form>
                    </td>
                </tr>



                <tr>
                    <td>ပိတ်သီး</td>
                    <td style="min-width:200px;">
                        @php
                        $date = session('selected_date');
                        $section = session('selected_section');
                        $user_id = Auth::user()->id;

                        $close_numbers = App\Models\CloseNumber::where("manager_id", $user_id)
                        ->where("date", $date)
                        ->where("section", $section)
                        ->get();
                        @endphp

                        @if ($close_numbers->count() > 0)
                        @foreach ($close_numbers as $close_number)
                        <span class="btn btn-danger mb-2">{{ $close_number->number }}</span><br>
                        @endforeach

                        <form action="/close/number/delete" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}">
                            <input type="hidden" name="section" value="{{ $section }}">
                            <button class="btn btn-danger">Delete All</button>
                        </form>
                        @else
                        <!-- Trigger Button -->
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#closeData">
                            ပိတ်သီးထည့်မည်။
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="closeData" tabindex="-1" aria-labelledby="customModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 shadow-lg">
                                    <div class="modal-header border-bottom-0">
                                        <h5 class="modal-title" id="customModalLabel">
                                            ပိတ်သီးထည့်မည်။<br>
                                            ရွှေးထားသည့်အချိန် - {{ $date }}
                                            <span style="color:blue;">{{ ucfirst($section) }}</span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body pt-0">
                                        <form action="/close/number/store" method="POST">
                                            @csrf
                                            <input type="hidden" name="date" value="{{ $date }}">
                                            <input type="hidden" name="section" value="{{ $section }}">

                                            <div class="mb-3">
                                                <label class="form-label">အကွက်များ *</label>
                                                <input type="text" id="patternInput2" class="form-control"
                                                    placeholder="ဥပမာ: 34,23,32,45,32" name="numbers" required>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary px-4">ထည့်မည်။</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Auto Format Input -->
                        <script>
                        document.getElementById('patternInput2').addEventListener('input', function(e) {
                            let value = e.target.value.replace(/[^0-9]/g, '');
                            let formatted = value.match(/.{1,2}/g)?.join(',') || '';
                            e.target.value = formatted;
                        });
                        </script>

                        <!-- Fallback Fix: Ensure aria-hidden is false when modal is visible -->
                        <script>
                        const modal = document.getElementById('closeData');
                        const observer = new MutationObserver(() => {
                            if (modal.style.display === 'block' && modal.getAttribute('aria-hidden') ===
                                'true') {
                                modal.setAttribute('aria-hidden', 'false');
                            }
                        });
                        observer.observe(modal, {
                            attributes: true,
                            attributeFilter: ['style', 'aria-hidden']
                        });
                        </script>
                        @endif
                    </td>
                </tr>



                <tr>
                    <td>
                        အကုန်ဖြတ်မည်
                    </td>
                    <td style="min-width:200px;">
                        <form action="/delete/all" method="post">
                            @csrf
                            <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
                            <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
                            <button type="submit" onclick="return confirm('Are You Sure To Do This Action ?')"
                                class="btn btn-danger w-100"> ယခု Section နှင့်ပက်သက်သော ထိုးထားသမျှအကုန်ဖြတ်မည်။
                            </button>
                        </form>
                    </td>
                </tr>

            </table>


        </div>
    </div>
    <div class="col-md-5 col-12">
        @php
        $totalItems = 100;
        $itemsPerColumn = 34;
        $columns = 3;
        @endphp
        <div class="row">
            @for ($col = 0; $col < $columns; $col++) <div class="col-md-4 col-4">
                @php
                $start = $col * $itemsPerColumn;
                $end = min($start + $itemsPerColumn - 1, $totalItems - 1);
                @endphp

                @for ($i = $start; $i <= $end; $i++) @php $number=str_pad($i, 2, '0' , STR_PAD_LEFT);
                    $user_id=Auth::user()->id;

                    $orders = App\Models\Order::where("manager_id", $user_id)
                    ->where("date", $date)
                    ->where("section", $section)
                    ->where("status", 1)->where("buy_sell_type","sell")
                    ->get();

                    $data = 0;
                    $orderDetails = collect(); // ← START with empty collection

                    foreach ($orders as $order) {
                    $details = App\Models\OrderDetail::where("order_id", $order->id)
                    ->where("number", $number)->where("buy_sell_type","sell")
                    ->get();

                    $orderDetails = $orderDetails->merge($details); // ← MERGE details

                    foreach ($details as $detail) {
                    $data += $detail->price;
                    }
                    }
                    @endphp

                    <div class="mb-2">
                        <span class="badge bg-primary p-2" style="cursor:pointer;" data-bs-toggle="modal"
                            data-bs-target="#modal-{{ $number }}">
                            {{ $number }}
                        </span>
                        <span>{{ $data }} Ks</span>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="modal-{{ $number }}" tabindex="-1"
                        aria-labelledby="modalLabel{{ $number }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Details for Number {{ $number }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Total amount: {{ $data }} Ks
                                    <hr>
                                    @foreach ($orderDetails as $orderDetail)
                                    <p><strong>User Name:</strong> {{ $orderDetail->user->name }}</p>
                                    <p><strong>Order Type:</strong> {{ $orderDetail->order_type }}</p>
                                    <p><strong>Price:</strong> {{ number_format($orderDetail->price) }} Ks</p>
                                    <hr>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
        </div>
        @endfor
    </div>
</div>
<div class="col-md-3 col-12">
    <h5 class="card-title fw-bold text-primary mb-3">ခေါင်းကျော်နေသော အကွက်များ</h5>
    <table class="table table-bordered table-hover text-center align-middle">
        <thead>
            <tr>
                <th>Num</th>
                <th>Last Over</th>
                <th>Over</th>
                <th>Buy</th>
            </tr>
        </thead>
        <tbody>
            @php
                $user_id = Auth::id();
                $date = session('selected_date');
                $section = session('selected_section');

                $orderDetails = App\Models\OrderDetail::where("manager_id", $user_id)
                    ->where("date", $date)
                    ->where("section", $section)
                    ->where("user_order_status", 1)->where("buy_sell_type","sell")
                    ->get()
                    ->groupBy('number');

                $limitHead = App\Models\DineHeadLimit::where("manager_id", $user_id)
                    ->where("date", $date)
                    ->where("section", $section)
                    ->first();

                $limitHeadPrice = $limitHead->amount ?? 0;
            @endphp

            @foreach ($orderDetails as $number => $items)
                @php
                    $totalPrice = $items->sum('price');
                    $lastOver = $items->last();
                @endphp

                @if ($totalPrice > $limitHeadPrice)
                    <tr>
                    <td style="background-color: green !important; color: white !important;">{{ $number }}</td>
                        <td>- {{ $totalPrice - $limitHeadPrice }}</td>
                        <td>{{ $totalPrice - $limitHeadPrice }}</td>
                        <td>0</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>


</div>








<script>

</script>
@endsection