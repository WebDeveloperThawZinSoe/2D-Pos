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
    <p><b>အရောင်းစာရင်း</b></p>
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
                    @endphp
                    {{ number_format($final) }} Ks
                </td>
            </tr>
            @endforeach
            <tr class="fw-bold bg-light">
                <td colspan="6" class="text-end">အရောင်းဘတ်မှ ရူံးမြတ်ငွေစုစုပေါင်း</td>
                <td>
                    @php
                        $finalTotal = $sellOrders->sum(function($order) {
                            $commission = $order->total_amount * ($order->user->commission / 100);
                            return $order->total_amount - ($order->deth_pauk ?? 0) - $commission;
                        });
                    @endphp
                    {{ number_format($finalTotal) }} Ks
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- ✅ BUY ORDERS SECTION --}}
<hr>
<div class="table-responsive">
    <p><b>အဝယ်စာရင်း</b></p>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>စဉ်</th>
                <th>ဒိုင်နာမည်</th>
                <th>အဝယ်</th>
                <th>ကော်</th>
                <th>အဆ</th>
                <th>ဒဲ့ပေါက်</th>
                <th>စုစုပေါင်း</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buyOrders as $key => $order)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $order->dine_name }}</td>
                <td>{{ number_format($order->total_amount) }} Ks</td>
                <td>
                    {{ $order->dine->commission ?? 0 }}%
                    @php
                        $commission_money = $order->total_amount * ($order->dine->commission / 100);
                    @endphp
                    ({{ number_format($commission_money) }} Ks)
                </td>
                <td>{{ $order->dine->rate ?? 0 }}</td>
                <td>{{ number_format($order->deth_pauk ?? 0) }} Ks</td>
                <td>
                    @php
                        $final = $order->total_amount - ( $order->deth_pauk + $commission_money );
                        $final = -1 * $final;
                    @endphp
                    {{ number_format($final) }} Ks
                </td>
            </tr>
            @endforeach
           <tr class="fw-bold bg-light">
    <td colspan="6" class="text-end">အဝယ်ဘတ်မှ ရူံးမြတ်ငွေစုစုပေါင်း</td>
    <td>
        @php
            $buyTotal = $buyOrders->sum(function($order) {
                $commission = $order->total_amount * ($order->dine->commission / 100);
                return $order->total_amount - ($order->deth_pauk ?? 0) - $commission;
            });

            
            $buyTotal = -1 * $buyTotal;
        @endphp
        {{ number_format($buyTotal) }} Ks
    </td>
</tr>

        </tbody>
    </table>
</div>

@php 
        $finalMoney = $finalTotal - $buyTotal;
@endphp 

<h4>စာရင်းချုပ်ငွေ  :  {{number_format($finalMoney)}} Ks </h4>


@endsection
