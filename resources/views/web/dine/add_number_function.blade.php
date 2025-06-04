<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<div style="background-color:beige;" class="p-2">


    <!-- <div class="row">
        <div class="col-12">
            ရွှေးထားသည့်အချိန် - {{ session('selected_date', 'Not set') }} <span style="color:blue;">
                {{ ucfirst(session('selected_section', 'Not set')) }}
            </span>
        </div>

        <div class="col-8">
            <input type="text" class="form-control" hidden value="{{ request()->get('get_date') }}" name="get_date"
                id="get_date">
        </div>

        <div class="col-4">
            <input type="text" class="form-control" hidden value="{{ request()->get('get_am_pm') }}" name="get_am_pm"
                id="get_am_pm">
        </div>
    </div>
    <form action="/number_store" method="post">
        @csrf
        <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
        <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
        <input type="hidden" name="buy_sell" value="sell">
        <input type="hidden" name="manager_id" value="{{Auth::user()->id}}">
        <div class="row gx-0 my-2">
            <div class="col-2 d-flex align-items-center">
                <div> ထိုးသား </div>
            </div>
            <div class="col">
                <select name="client" id="client" required class="form-control">
                    <option value="">ထိုးသားကိုရွှေးပါ။</option>
                    @php
                    $parent_id = Auth::user()->id;
                    $clients = App\Models\User::where("manager_id",$parent_id)->get();
                    @endphp
                    @foreach($clients as $client)
                    <option value="{{$client->id}}">{{$client->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-1 ms-2">
                <div style="width:30px;height:30px;">
                    <a href="{{route('dine.agents')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path
                                d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="row my-3" id="normal_input">
            <div class="col-9">
                <input type="text" class="form-control @error('number') is-invalid @enderror" name="number" required
                    placeholder="နံပါတ် တင်ငွေရိုက် ထည့်ပါ...">
                @error('number')
                <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror

            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary form-control"> ရွှေးသည် </button>
            </div>
        </div>

    </form>

    <hr>

    <button type="button" class="btn btn-primary btn-full btn-lg w-100" data-bs-toggle="modal"
        data-bs-target="#customModal">
        အကွက်မတူငွေတူ
    </button>

    <div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="customModalLabel">အကွက်မတူငွေတူ <br> ရွှေးထားသည့်အချိန် -
                        {{ session('selected_date', 'Not set') }} <span style="color:blue;">
                            {{ ucfirst(session('selected_section', 'Not set')) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">
                    <form action="/multi/number_store" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
                        <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
                        <input type="hidden" name="buy_sell" value="sell">
                        <input type="hidden" name="manager_id" value="{{Auth::user()->id}}">
                        <div class="mb-3">
                            <label for="client" class="form-label">ထိုးသားကိုရွှေးပါ * </label>
                            <select name="client" required id="client" class="form-select">
                                <option value="">ထိုးသားကိုရွှေးပါ။</option>
                                @php
                                $parent_id = Auth::user()->id;
                                $clients = App\Models\User::where("manager_id",$parent_id)->get();
                                @endphp
                                @foreach($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">အကွက်များ * </label>
                            <input type="text" id="patternInput" class="form-control" placeholder="ဥပမာ: 34-23-32-45-32"
                                name="numbers" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ငွေပမာဏ * </label>
                            <input type="number" class="form-control" name="amount" required
                                placeholder="ငွေပမာဏထည့်ပါ">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">ရွှေးသည်</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('patternInput').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, ''); // Remove non-digits
        let formatted = value.match(/.{1,2}/g)?.join('-') || '';
        e.target.value = formatted;
    });
    </script>

    <br> <br> -->

    <div class="row">
        <div class="col-12">
            ရွှေးထားသည့်အချိန် - {{ session('selected_date', 'Not set') }}
            <span style="color:blue;">
                {{ ucfirst(session('selected_section', 'Not set')) }}
            </span>
        </div>

        <div class="col-8">
            <input type="text" class="form-control" hidden value="{{ request()->get('get_date') }}" name="get_date"
                id="get_date">
        </div>

        <div class="col-4">
            <input type="text" class="form-control" hidden value="{{ request()->get('get_am_pm') }}" name="get_am_pm"
                id="get_am_pm">
        </div>
    </div>

    <form action="/number_store" method="post">
        @csrf
        <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
        <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
        <input type="hidden" name="buy_sell" value="sell">
        <input type="hidden" name="manager_id" value="{{ Auth::user()->id }}">

        <div class="row gx-0 my-2">
            <div class="col-2 d-flex align-items-center">
                <div> ထိုးသား </div>
            </div>
            <div class="col">
                <select name="client" id="client" required class="form-control">
                    <option value="">ထိုးသားကိုရွှေးပါ။</option>
                    @php
                    $parent_id = Auth::user()->id;
                    $clients = App\Models\User::where("manager_id", $parent_id)->get();
                    @endphp
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-1 ms-2">
                <div style="width:30px;height:30px;">
                    <a href="{{ route('dine.agents') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path
                                d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="row my-3" id="normal_input">
            <div class="col-9">
                <input type="text" class="form-control @error('number') is-invalid @enderror" name="number" required
                    placeholder="နံပါတ် တင်ငွေရိုက် ထည့်ပါ...">
                @error('number')
                <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary form-control"> ရွှေးသည် </button>
            </div>
        </div>
    </form>

    <hr>

    <!-- 🔵 Button to open modal -->
    <button type="button" class="btn btn-primary btn-full btn-lg w-100" id="openCustomModal">
        အကွက်မတူငွေတူ
    </button>

    <!-- 🔵 Modal -->
    <div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="customModalLabel">
                        အကွက်မတူငွေတူ <br>
                        ရွှေးထားသည့်အချိန် - {{ session('selected_date', 'Not set') }}
                        <span style="color:blue;">{{ ucfirst(session('selected_section', 'Not set')) }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">
                    <form action="/multi/number_store" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
                        <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
                        <input type="hidden" name="buy_sell" value="sell">
                        <input type="hidden" name="manager_id" value="{{ Auth::user()->id }}">

                        <div class="mb-3" style="display: none;">
                            <label for="modal_client" class="form-label">ထိုးသားကိုရွှေးပါ * </label>
                            <select name="client" required id="modal_client" class="form-select">
                                <option value="">ထိုးသားကိုရွှေးပါ။</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">အကွက်များ * </label>
                            <input type="text" id="patternInput" class="form-control" placeholder="ဥပမာ: 34-23-32-45-32"
                                name="numbers" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ငွေပမာဏ * </label>
                            <input type="number" class="form-control" name="amount" required
                                placeholder="ငွေပမာဏထည့်ပါ">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">ရွှေးသည်</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔵 Script Section -->
    <script>
    // Format number input
    document.getElementById('patternInput').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, ''); // Remove non-digits
        let formatted = value.match(/.{1,2}/g)?.join('-') || '';
        e.target.value = formatted;
    });

    // Prevent modal if no client selected
    document.getElementById('openCustomModal').addEventListener('click', function() {
        const clientSelect = document.getElementById('client');
        const selectedValue = clientSelect.value;

        if (!selectedValue) {
            alert("ကျေးဇူးပြုပြီး ထိုးသားကို ရွေးချယ်ပါ။");
            return;
        }

        // Set modal's client select to same value
        document.getElementById('modal_client').value = selectedValue;

        // Show modal manually
        const modal = new bootstrap.Modal(document.getElementById('customModal'));
        modal.show();
    });
    </script>


    <br> <br>


    <div class="row" style="background-color:azure;">
        <br>
        <div class="col-md-12 shadow rounded p-1" style="height:250px;overflow:scroll;">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @php
                    $user_id = Auth::user()->id;
                    $date = session('selected_date');
                    $section = session('selected_section');
                    $orders =
                    App\Models\Order::where("created_by",$user_id)->where("date",$date)->where("section",$section)->where("buy_sell_type",'sell')->where("status",0)->get();
                    @endphp
                    @foreach($orders as $order)
                    <div class="d-inline-block position-relative me-2">
                        <!-- Cancel Button -->
                        <form action="/order/delete" method="POST" class="position-absolute"
                            style="top: -8px; right: -8px; z-index: 1;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $order->id }}">
                            <button type="submit"
                                class="btn btn-danger rounded-circle border-0 shadow d-flex align-items-center justify-content-center"
                                title="Cancel Order"
                                style="width: 36px; height: 36px; font-size: 1.25rem; padding: 0; transition: 0.3s;">
                                ×
                            </button>
                        </form>

                        <!-- Main Order Button -->
                        <button class="btn btn-primary mt-3" data-bs-toggle="modal"
                            data-bs-target="#orderModal{{ $order->id }}">
                            {{ $order->order_type }}
                        </button>
                    </div>



                    <!-- Modal for each order -->
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
                                    <p><strong>Buy Or Sell:</strong> {{ $order->buy_sell_type }}</p>
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Number</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $OrderDetail = App\Models\OrderDetail::where("order_id",$order->id)->get();
                                            @endphp
                                            @foreach($OrderDetail as $key=>$OrderDetail)
                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td>{{$OrderDetail->number}}</td>
                                                <td>{{$OrderDetail->price}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    &nbsp;&nbsp;&nbsp;
                    @endforeach

                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="with_format" role="tabpanel" tabindex="0">
                    <div class="row gx-0" id="2D_list"></div>
                </div>
                <div class="tab-pane fade" id="with_list" role="tabpanel" tabindex="0">
                    <table id="show_with_list" class="table table-hover table-bordered"></table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-borderless">
                @php


                $user_id = Auth::id(); // Add this if not already defined
                $date = session('selected_date');
                $section = session('selected_section');

                $orders = App\Models\Order::where("created_by", $user_id)
                ->where("date", $date)
                ->where("section", $section)
                ->where("status", 0)
                ->get();

                $total_order_count = 0;
                $total_sum_amount = 0;

                foreach ($orders as $order) {
                $total_order_count += App\Models\OrderDetail::where("order_id", $order->id)->count();
                $total_sum_amount += App\Models\OrderDetail::where("order_id", $order->id)->sum('price');
                }
                @endphp

                <tr>
                    <td style="width:25%;padding-right:5px !important;">
                        <div class="bg-secondary rounded p-1" style="min-height:33px;">
                            <span style="color:white"> Count </span>
                        </div>
                    </td>
                    <td style="width:25%;padding-right:5px !important;">
                        <div class="bg-secondary rounded p-1" style="min-height:33px;">
                            <span style="color:white" id="count_id">
                                {{$total_order_count}}
                            </span>
                        </div>
                    </td>
                    <td style="width:25%;padding-right:5px !important;">
                        <div class="bg-secondary rounded p-1 " style="min-height:33px;">
                            <span style="color:white"> Total </span>
                        </div>
                    </td>
                    <td style="width:25%;">
                        <div class="bg-secondary rounded p-1" style="min-height:33px;">
                            <span style="color:white" id="total_number_value"> {{$total_sum_amount}} </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <!-- Button trigger -->
    <form action="/order/confirm/all" method="post">
        @csrf
        <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
        <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
        <input type="hidden" name="buy_sell" value="sell">
        <button type="submit" class="btn btn-primary btn-lg w-100">
            တင်သည်။
        </button>
    </form>


    <br>
</div>