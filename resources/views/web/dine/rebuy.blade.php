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
        <form action="/rebuy/store" method="POST">
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

                    @foreach ($sellDetails as $number => $sellItems)
                    @php
                    $sellTotal = $sellItems->sum('price');
                    $buyTotal = $buyDetails->has($number) ? $buyDetails[$number]->sum('price') : 0;

                    $lastOver = $sellTotal - $limitHeadPrice;
                    $calLastOver = (-$lastOver) + $buyTotal;
                    @endphp

                    @if ($sellTotal > $limitHeadPrice && $calLastOver < 0) <tr>
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
                                <select name="dine" id="client" required class="form-control">
                                    <option value="">ဒိုင်ကိုရွှေးမည်။</option>
                                    @php
                                    $parent_id = Auth::user()->id;
                                    $clients = App\Models\ReDine::where("manager_id",$parent_id)->get();
                                    @endphp
                                    @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
                                <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
                                <input type="hidden" name="buy_sell" value="buy">
                                <input type="hidden" name="manager_id" value="{{Auth::user()->id}}">
                                <br>
                                <label for="rebuyPercent" class="mb-0 fw-bold text-secondary">ပြန်ဝယ် %:</label>
                                <input type="number" id="rebuyPercent" name="rebuy_percent" value="100" max="300"
                                    min="1" class="form-control form-control-sm d-inline w-auto mx-2" required>
                                <button type="submit" class="btn btn-sm btn-primary">ပြန်ဝယ်မည်</button>
                            </td>
                        </tr>
                </tbody>
            </table>
        </form>
    </div>


</div>








<script>

</script>
@endsection