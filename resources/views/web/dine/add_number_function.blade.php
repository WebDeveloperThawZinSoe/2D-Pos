<div style="background-color:beige;" class="p-2">
    <form id="submit_2d_form" action="/lottery_2d_admin" method="POST">
        @csrf

        <div class="row">
            <div class="col-12">
            ရွှေးထားသည့်အချိန် -  {{ session('selected_date', 'Not set') }} <span style="color:blue;">
                {{ ucfirst(session('selected_section', 'Not set')) }}
                </span>
            </div>

            <div class="col-8">
                <input type="text" class="form-control" hidden value="{{ request()->get('get_date') }}" name="get_date" id="get_date">
            </div>

            <div class="col-4">
                <input type="text" class="form-control" hidden value="{{ request()->get('get_am_pm') }}" name="get_am_pm" id="get_am_pm">
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
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="row my-3" id="normal_input">
            <div class="col-9">
                <input type="text" id="add_2d_number_id" class="form-control" onkeyup="change_format_type(this.value)" onkeydown="add_2D_number(this)" placeholder="Enter Here">
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary form-control"> ရွှေးသည် </button>
            </div>
        </div>

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
    </form>
</div>
