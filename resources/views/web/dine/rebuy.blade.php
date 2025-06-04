@extends('web.master')

@section('body')


<div class="row">
    <div class="col-md-4 col-12">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="admin_sell" role="tabpanel" aria-labelledby="pills-home-tab"
                tabindex="0">
                @include('web.dine.add_number_function_rebuy')
            </div>
        </div>
        <div style="background: greenyellow;">
            <table class="table table-bordered table-info">

            </table>


        </div>
    </div>

<div class="col-md-3 col-12">
    <h5 class="card-title fw-bold text-primary mb-3">ခေါင်းကျော်နေသော အကွက်များ</h5>
    <form action="/rebuy/store" method="POST" id="rebuyForm">
        @csrf
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

                    $sellDetails = App\Models\OrderDetail::where("manager_id", $user_id)
                        ->where("date", $date)
                        ->where("section", $section)
                        ->where("user_order_status", 1)
                        ->where("buy_sell_type", "sell")
                        ->get()
                        ->groupBy('number');

                    $buyDetails = App\Models\OrderDetail::where("manager_id", $user_id)
                        ->where("date", $date)
                        ->where("section", $section)
                        ->where("user_order_status", 1)
                        ->where("buy_sell_type", "buy")
                        ->get()
                        ->groupBy('number');

                    $limitHead = App\Models\DineHeadLimit::where("manager_id", $user_id)
                        ->where("date", $date)
                        ->where("section", $section)
                        ->first();

                    $limitHeadPrice = $limitHead->amount ?? 0;
                @endphp

                @if($limitHeadPrice > 0)
                    @foreach ($sellDetails as $number => $sellItems)
                        @php
                            $sellTotal = $sellItems->sum('price');
                            $buyTotal = $buyDetails->has($number) ? $buyDetails[$number]->sum('price') : 0;

                            $lastOver = $sellTotal - $limitHeadPrice;
                            $calLastOver = (-$lastOver) + $buyTotal;
                        @endphp

                        @if ($sellTotal > $limitHeadPrice && $calLastOver < 0)
                            <tr>
                                <td class="bg-success text-white py-1">{{ $number }}</td>
                                <td class="py-1">{{ number_format($calLastOver) }}</td>
                                <td class="py-1">{{ number_format($lastOver) }}</td>
                                <td class="py-1">{{ number_format($buyTotal) }}</td>
                            </tr>

                            <!-- Hidden inputs for each number -->
                            <input type="hidden" name="numbers[{{ $number }}][number]" value="{{ $number }}">
                            <input type="hidden" name="numbers[{{ $number }}][amount]" value="{{ $calLastOver }}">
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="4" class="bg-light text-center py-3">
                            <!-- Hidden Dine Select: will be populated from #client -->
                            <select name="dine" id="dine_id" required style="display:none" class="form-control">
                                <option value="">ဒိုင်ကိုရွှေးမည်။</option>
                                @php
                                    $parent_id = Auth::user()->id;
                                    $clients = App\Models\ReDine::where("manager_id", $parent_id)->get();
                                @endphp
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>

                            <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
                            <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
                            <input type="hidden" name="buy_sell" value="buy">
                            <input type="hidden" name="manager_id" value="{{ Auth::user()->id }}">

                            <br>
                            <label for="rebuyPercent" class="mb-0 fw-bold text-secondary">ပြန်ဝယ် %:</label>
                            <input
                                type="number"
                                id="rebuyPercent"
                                name="rebuy_percent"
                                value="100"
                                max="300"
                                min="1"
                                class="form-control form-control-sm d-inline w-auto mx-2"
                                required
                            >
                            <button type="submit" class="btn btn-sm btn-primary">ပြန်ဝယ်မည်</button>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4">ခေါင်ကျော်သောအကွက်များမရှိပါ</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </form>
</div>

<script>
    const clientSelect = document.getElementById('client');
    const dineSelect = document.getElementById('dine_id');
    const rebuyForm = document.getElementById('rebuyForm');

    // Sync selection from client to hidden dine
    clientSelect.addEventListener('change', function () {
        const selectedValue = clientSelect.value;

        const matchingOption = Array.from(dineSelect.options).find(option => option.value === selectedValue);
        if (matchingOption) {
            dineSelect.value = selectedValue;
        } else {
            dineSelect.value = '';
        }
    });

    // Prevent form submit if no client selected
    rebuyForm.addEventListener('submit', function (e) {
        if (!clientSelect.value) {
            e.preventDefault(); // stop form from submitting
            alert('ကျေးဇူးပြု၍ ဒိုင်ကိုရွေးပါ။');
        }
    });
</script>






    <div class="col-md-5 col-12">
        <div class="table-responsive">
            <p><b>အဝယ်စာရင်း</b></p>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>


                        <th>ဒိုင်အမည်</th>
                        <th>ထိုးကွက်စုစုပေါင်း</th>
                        <th>ထိုးငွေ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($buyOrders as $key => $order)
                    <tr>


                        <td>{{ $order->dine->name ??"" }}</td>
                        <td>
                            {{ \App\Models\OrderDetail::where('order_id', $order->id)->count() }}
                        </td>
                        <td>{{ number_format($order->price) }} </td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#orderModal{{ $order->id }}">အသေးစိတ်</button>
                            <!-- <form id="delete-form-{{ $order->id }}" action="{{ route('order.delete') }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $order->id }}">
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete({{ $order->id }})">
                                ဖြတ်မည်
                            </button>
                        </form> -->

                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1"
                        aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Type:</strong> {{ $order->order_type }}</p>
                                    <p><strong>Dine:</strong> {{$order->dine->name ?? ""}} </p>
                                    <p><strong>Total Amount:</strong> {{ $order->price }} </p>
                                    <p><strong>Date:</strong> {{ $order->date }}</p>
                                    <p><strong>Section:</strong> {{ $order->section }}</p>
                                    <p><a target="_blank" href="/print/{{$order->id}}" class="btn btn-primary">Print</a>
                                    </p>
                                    @php
                                    $OrderDetail = App\Models\OrderDetail::where("order_id",$order->id)->get();
                                    @endphp

                                    @foreach($OrderDetail as $key => $detail)
                                    <div class="border rounded p-2 mb-2">
                                        <p class="mb-1"><strong>စဉ်:</strong> {{ $key + 1 }}</p>
                                        <p class="mb-1"><strong>နံပါတ်:</strong> {{ $detail->number }}</p>
                                        <p class="mb-0"><strong>ဈေးနှုန်း:</strong> {{ number_format($detail->price) }}
                                        </p>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>


                    @endforeach

                    <!-- Summary row -->
                    <tr class="fw-bold bg-light">
                        <td colspan="3" class="text-end">ထိုးငွေစုစုပေါင်း</td>
                        <td colspan="2">{{ number_format($buyOrders->sum('price')) }} </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>








<script>

</script>
@endsection