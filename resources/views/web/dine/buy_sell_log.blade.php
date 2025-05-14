@extends('web.master')

@section('body')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<div class="container py-4">
    <h4 class="mb-4">
        <b>
            အရောင်းအဝယ်စာရင်း (ရွှေးထားသည့်အချိန် - {{ session('selected_date', 'Not set') }}
            <span class="text-primary">{{ ucfirst(session('selected_section', 'Not set')) }}</span>)
        </b>
    </h4>
    <hr>
    <div class="table-responsive">
        <p><b>အရောင်းစာရင်း</b></p>
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>စဉ်</th>
                    <th>အော်ဒါနံပတ်</th>
                    <th>ထိုးသားအမည်</th>
                    <th>ထိုးကွက်စုစုပေါင်း</th>
                    <th>ထိုးငွေ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($sellOrders as $key => $order)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>
                        {{ \App\Models\OrderDetail::where('order_id', $order->id)->count() }}
                    </td>
                    <td>{{ number_format($order->price) }} Ks</td>
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#orderModal{{ $order->id }}">အသေးစိတ်</button>
                        <form id="delete-form-{{ $order->id }}" action="{{ route('order.delete') }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $order->id }}">
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete({{ $order->id }})">
                                ဖြတ်မည်
                            </button>
                        </form>

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
                                <p><strong>Client:</strong> {{$order->user->name ?? ""}} </p>
                                <p><strong>Total Amount:</strong> {{ $order->price }} Ks</p>
                                <p><strong>Date:</strong> {{ $order->date }}</p>
                                <p><strong>Section:</strong> {{ $order->section }}</p>

                                @php
                                $OrderDetail = App\Models\OrderDetail::where("order_id",$order->id)->get();
                                @endphp

                                @foreach($OrderDetail as $key => $detail)
                                <div class="border rounded p-2 mb-2">
                                    <p class="mb-1"><strong>စဉ်:</strong> {{ $key + 1 }}</p>
                                    <p class="mb-1"><strong>နံပါတ်:</strong> {{ $detail->number }}</p>
                                    <p class="mb-0"><strong>ဈေးနှုန်း:</strong> {{ number_format($detail->price) }} Ks
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
                    <td colspan="4" class="text-end">ထိုးငွေစုစုပေါင်း</td>
                    <td colspan="2">{{ number_format($sellOrders->sum('price')) }} Ks</td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr>

     
    <div class="table-responsive">
        <p><b>အဝယ်စာရင်း</b></p>
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>စဉ်</th>
                    <th>အော်ဒါနံပတ်</th>
                    <th>ဒိုင်အမည်</th>
                    <th>ထိုးကွက်စုစုပေါင်း</th>
                    <th>ထိုးငွေ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($buyOrders as $key => $order)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->dine->name }}</td>
                    <td>
                        {{ \App\Models\OrderDetail::where('order_id', $order->id)->count() }}
                    </td>
                    <td>{{ number_format($order->price) }} Ks</td>
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#orderModal{{ $order->id }}">အသေးစိတ်</button>
                        <form id="delete-form-{{ $order->id }}" action="{{ route('order.delete') }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $order->id }}">
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete({{ $order->id }})">
                                ဖြတ်မည်
                            </button>
                        </form>

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
                                <p><strong>Client:</strong> {{$order->dine->name ?? ""}} </p>
                                <p><strong>Total Amount:</strong> {{ $order->price }} Ks</p>
                                <p><strong>Date:</strong> {{ $order->date }}</p>
                                <p><strong>Section:</strong> {{ $order->section }}</p>

                                @php
                                $OrderDetail = App\Models\OrderDetail::where("order_id",$order->id)->get();
                                @endphp

                                @foreach($OrderDetail as $key => $detail)
                                <div class="border rounded p-2 mb-2">
                                    <p class="mb-1"><strong>စဉ်:</strong> {{ $key + 1 }}</p>
                                    <p class="mb-1"><strong>နံပါတ်:</strong> {{ $detail->number }}</p>
                                    <p class="mb-0"><strong>ဈေးနှုန်း:</strong> {{ number_format($detail->price) }} Ks
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
                    <td colspan="4" class="text-end">ထိုးငွေစုစုပေါင်း</td>
                    <td colspan="2">{{ number_format($buyOrders->sum('price')) }} Ks</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(orderId) {
    Swal.fire({
        title: 'သေချာပြီလား?',
        text: 'ဤအော်ဒါကို ဖြတ်မည်။ ',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ဖြတ်မည်',
        cancelButtonText: 'မဖြတ်ပါ',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form with the specific order ID
            document.getElementById('delete-form-' + orderId).submit();
        }
    });
}
</script>


@endsection