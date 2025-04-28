@extends('web.master')

@section('body')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<section class="h-100 gradient-form">
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1" style="background-color: #eee;">
            <div style="padding:20px">

                @php
                    $groupedOrders = $orders->groupBy('date');
                @endphp

                @forelse($groupedOrders as $date => $orderGroup)
                    <h5 class="mt-4">{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>စဉ်</th>
                                    <th>အော်ဒါအမှတ်</th>
                                    <th>အမျိုးအစား</th>
                                    <th>Date /  Order Section</th>
                                    <th>စုစုပေါင်းတင်‌ငွေ</th>
                                    <th>Order Status</th>
                                    <th>Created At</th>
                                    <th>ဆောင်ရွက်မည်</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderGroup as $key => $order)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->order_type }}</td>
                                        <td>{{ $order->date }} /  {{ $order->section }}</td>
                                        <td>{{ number_format($order->price) }} Ks</td>
                                        <td>
                                            @if($order->user_order_status  == 0)
                                            <button class="btn btn-warning btn-sm">မတင်ရသေး</button>
                                            @else 

                                            <button class="btn btn-success btn-sm">တင်ပြီပါပြီ</button>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('H:i:s') }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#orderDetailModal{{ $order->id }}">
                                                View
                                            </button>

                                            @include('web.user.partials.order-detail-modal', ['order' => $order])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @empty
                    <div class="alert alert-warning">
                        မရှိသေးပါ။
                    </div>
                @endforelse

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
