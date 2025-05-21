@extends('web.master')

@section('body')

<h4 class="mb-4">
     <b>
        {{ ucfirst($type) }} စာရင်းချုပ် 
        ({{ $startDate->format('Y-m-d') }} မှ {{ $endDate->format('Y-m-d') }})
    </b>
</h4>

{{-- ✅ SELL ORDERS SECTION --}}
<hr>
<div class="table-responsive">
  
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>စဉ်</th>
                <th>ထိုးသားအမည်</th>
                <th>အရောင်း</th>
                <th>ကော်</th>
                <th>အဆ</th>
                <th>ဒဲ့ပေါက်</th>
                <th>စုစုပေါင်း</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sellOrders as $key => $order)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $order->user_name }}</td>
                <td>{{ number_format($order->total_amount) }} Ks</td>
                <td>
                    {{ $order->user->commission ?? 0 }}%
                    @php
                        $commission_money = $order->total_amount * ($order->user->commission / 100);
                    @endphp
                    ({{ number_format($commission_money) }} Ks)
                </td>
                <td>{{ $order->user->rate ?? 0 }}</td>
                <td>{{ number_format($order->deth_pauk ?? 0) }} Ks</td>
                <td>
                    @php
                        $final = $order->total_amount - $order->deth_pauk - $commission_money;
                        $final = $final * (-1);
                    @endphp
                    {{ number_format($final) }} Ks
                </td>
            </tr>
            @endforeach
            <tr class="fw-bold bg-light">
                <td colspan="6" class="text-end">ရူံးမြတ်ငွေစုစုပေါင်း</td>
                <td>
                    @php
                        $finalTotal = $sellOrders->sum(function($order) {
                            $commission = $order->total_amount * ($order->user->commission / 100);
                            return $order->total_amount - ($order->deth_pauk ?? 0) - $commission;
                        });

                        $finalTotal = $finalTotal * (-1);
                    @endphp
                    {{ number_format($finalTotal) }} Ks
                </td>
            </tr>
        </tbody>
    </table>
</div>



        </tbody>
    </table>
</div>


@endsection
