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
                    <td class="bg-success text-white py-1">{{ $number }}</td>
                    <td class="py-1">-{{ $totalPrice - $limitHeadPrice }}</td>
                    <td class="py-1">{{ $totalPrice - $limitHeadPrice }}</td>
                    <td class="py-1">0</td>
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