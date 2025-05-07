<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<div style="background-color:beige;" class="p-2">
   

        <div class="row">
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
                <input type="text" class="form-control" hidden value="{{ request()->get('get_am_pm') }}"
                    name="get_am_pm" id="get_am_pm">
            </div>
        </div>

        <div class="row gx-0 my-2">
            <div class="col-2 d-flex align-items-center">
                <div> ထိုးသား </div>
            </div>
            <div class="col">
                <select name="client" id="client" class="form-control">
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
                <input type="text" id="add_2d_number_id" class="form-control" onkeyup="change_format_type(this.value)"
                    onkeydown="add_2D_number(this)" placeholder="Enter Here">
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary form-control"> ရွှေးသည် </button>
            </div>
        </div>

        <hr>

        <!-- Button trigger -->
        <button type="button" class="btn btn-primary btn-full btn-lg w-100" data-bs-toggle="modal" data-bs-target="#customModal">
            အကွက်မတူငွေတူ
        </button>

        <!-- Modal -->
        <div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="customModalLabel">အကွက်မတူငွေတူ  <br> ရွှေးထားသည့်အချိန် - {{ session('selected_date', 'Not set') }} <span style="color:blue;">
                        {{ ucfirst(session('selected_section', 'Not set')) }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body pt-0">
                        <form action="/multi/number_store" method="post">
                            @csrf
                            <input type="hidden" name="date" value="{{ session('selected_date', 'Not set') }}">
                            <input type="hidden" name="section" value="{{ session('selected_section', 'Not set') }}">
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
                                <label class="form-label">အကွက်များ  *  </label>
                                <input type="text" id="patternInput" class="form-control"
                                    placeholder="ဥပမာ: 34-23-32-45-32" name="numbers" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ငွေပမာဏ  *  </label>
                                <input type="number" class="form-control" name="amount" required placeholder="ငွေပမာဏထည့်ပါ">
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

        <br> <br>

        <!-- <div class="row my-3" id="number_looping">
            <div class="col-12">
                <div class="row d-flex justify-content-center">
                    <div class="col-5">နံပတ်ရိုက်ရန်နေရာ</div>
                    <div class="col-6">
                        <input type="text" class="form-control" onkeypress="add_number(this.value)" onkeyup="add_number(this.value)" id="add_number_input">
                    </div>
                </div>

                <div class="bg-light" style="height:200px;" id="add_nunber_list"></div>

                <button type="button" class="btn btn-primary form-control my-3" onclick="remove_last_number()">Remove Last Number</button>

                <div class="container my-3" id="num_list_here"></div>

                <p class="my-3">ငွေရိုက်ထည့်ရန်နေရာ</p>
                <div class="row gx-0">
                    <div class="col-6">
                        <input type="text" class="form-control" onkeyup="change_format_type_2(this.value)" onkeypress="check_enter_save(this.value)" id="get_add_number_price">
                    </div>
                    <div class="col-3 px-2">
                        <button onclick="submit_confirm_number()" type="button" class="btn btn-primary form-control">Save</button>
                    </div>
                    <div class="col-3 px-2">
                        <button onclick="cancle_looping()" type="button" class="btn btn-danger form-control">Cancel</button>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row" style="background-color:azure;">
            <br>
            <div class="col-md-12 shadow rounded p-1" style="height:250px;overflow:scroll;">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist"></div>
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
                    <tr>
                        <td style="width:25%;padding-right:5px !important;">
                            <div class="bg-secondary rounded p-1" style="min-height:33px;">
                                <span style="color:white"> Count </span>
                            </div>
                        </td>
                        <td style="width:25%;padding-right:5px !important;">
                            <div class="bg-secondary rounded p-1" style="min-height:33px;">
                                <span style="color:white" id="count_id"> 0 </span>
                            </div>
                        </td>
                        <td style="width:25%;padding-right:5px !important;">
                            <div class="bg-secondary rounded p-1 " style="min-height:33px;">
                                <span style="color:white"> Total </span>
                            </div>
                        </td>
                        <td style="width:25%;">
                            <div class="bg-secondary rounded p-1" style="min-height:33px;">
                                <span style="color:white" id="total_number_value"> 0 </span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
   
       
          <!-- Button trigger -->
          <button type="button" class="btn btn-primary btn-lg w-100">
            တင်သည်။
        </button>

        <br>
</div>