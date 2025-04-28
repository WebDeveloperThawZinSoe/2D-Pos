<div class="modal" id="orderDetailModal{{ $order->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">အော်ဒါအသေးစိတ်</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <p><b>အော်ဒါအမှတ် :</b> {{ $order->order_number }}</p>
                                        <p><b>Order အခြေအနေ : </b> 
                                            @if($order->user_order_status == 0)
                                                <button class="btn btn-warning">မတင်ရသေးပါ</button>
                                            @else 
                                            <button class="btn btn-success">တင်ပြီပါပြီ</button>
                                            @endif
                                        </p>
                                        <p><b>ဒိုင် အမည် :</b> {{ Auth::user()->manager->name }}
                                            ({{ Auth::user()->manager->email }})</p>
                                        <p><b>အမျိုးအစား :</b> {{ $order->order_type }}</p>
                                        <p><b>စုစုပေါင်းတင်‌ငွေ :</b> {{ number_format($order->price) }} Ks</p>
                                        @php
                                        $orderDetails = App\Models\OrderDetail::where("order_id", $order->id)->get();
                                        @endphp
                                        <p><b>အကွက်စုစုပေါင်း :</b> {{ $orderDetails->count() }}</p>
                                        <p><b>ရက် / အချိန် :</b> {{ $order->created_at }}</p>

                                        <div class="order-details-list">
                                            @foreach($orderDetails as $key => $orderDetail)
                                            <div
                                                class="order-item d-flex justify-content-between align-items-center border-bottom py-2">
                                                <!-- <div><b>No:</b> {{ $key + 1 }}</div> -->
                                                <div><b>Number:</b> {{ $orderDetail->number }}</div>
                                                <div><b>Price:</b> {{ number_format($orderDetail->price) }} Ks</div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Optional custom styling -->
                        <style>
                        .order-details-list {
                            margin-top: 20px;
                        }

                        .order-item {
                            font-size: 14px;
                        }
                        </style>