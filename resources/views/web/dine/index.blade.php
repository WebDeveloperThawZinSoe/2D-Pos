@extends('web.master')

@section('body')
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const output = document.getElementById('output');

    document.addEventListener('keypress', function(event) {
        if (event.key === 'S' || event.key === 's') {

        }
    });
});
</script>

<div class="" style="">
    <div>
      <div class="row" >
            <div class="col-4 shadow p-3 my-3 rounded" style="max-width:400px;">

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="admin_sell" role="tabpanel"
                        aria-labelledby="pills-home-tab" tabindex="0">

                        @include('web.dine.add_number_function')



                    </div>




                </div>





                <div style="background: greenyellow;">
                    <table class="table table-bordered table-info">
                        <tr>
                            <td>
                                အရောင်း Sale
                            </td>
                            <td style="min-width:200px;">

                            </td>
                        </tr>

                        <tr>
                            <td>
                                ပြန်၀ယ်
                            </td>
                            <td style="min-width:200px;">

                            </td>
                        </tr>

                        <tr>
                            <td>
                                အရောင်း အ၀ယ်
                            </td>
                            <td style="min-width:200px;">

                            </td>
                        </tr>

                        <tr>
                            <td>
                                ခေါင်း Limit
                            </td>
                            <td style="min-width:200px;">
                                <form action="/admin/set/limit" method="POST" id="form_set_limit"
                                    style="display: inline;">
                                    @csrf
                                    <input hidden type="date" class="form-control"
                                        value="{{ $request->get_date ?? Carbon\Carbon::now()->format('Y-m-d') }}"
                                        onchange="change_section()" name="get_date" id="get_date">


                                    <select hidden name="get_am_pm" id="get_am_pm" onchange="change_section()"
                                        class="form-control">

                                        <option>AM
                                        </option>
                                        <option>PM
                                        </option>


                                    </select>


                                    <input name="amount" type="text"
                                        onchange="document.getElementById('form_set_limit').submit()"
                                        class="form-control" value="">
                                </form>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                ပေါက်သီး
                            </td>
                            <td style="min-width:200px;">




                                <form method="POST" action="/admin/set_lucky_number" id="set_lucky_number"
                                    style="display: inline;">
                                    @csrf
                                    <input hidden type="date" class="form-control"
                                        value="{{ $request->get_date ?? Carbon\Carbon::now()->format('Y-m-d') }}"
                                        onchange="change_section()" name="get_date" id="get_date">


                                    <select hidden name="get_am_pm" id="get_am_pm" onchange="change_section()"
                                        class="form-control">

                                        <option value="am">AM
                                        </option>
                                        <option value="pm">PM
                                        </option>


                                    </select>
                                    <input type="text" class="form-control" name="number"
                                        onchange="document.getElementById('set_lucky_number').submit();" value="">
                                </form>
                            </td>
                        </tr>

                        



                    </table>


                </div>


            </div>
            <div class="col-8 p-3 " style="max-width:860px;min-height:700px;">

                <div class="row">
                    <div class="col-8">



                    </div>


                </div>




                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab" tabindex="0">

                        <div id="legure_list">

                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width:70px;">
                                                Mon
                                            </th>
                                            <th style="width:70px;">
                                                Tue
                                            </th>
                                            <th style="width:70px;">
                                                Wed
                                            </th>
                                            <th style="width:70px;">
                                                Thu
                                            </th>
                                            <th style="width:70px;">
                                                Fri
                                            </th>

                                        </tr>
                                        <tr>

                                        </tr>

                                        <tr>

                                        </tr>



                                    </table>
                                </div>
                            </div>
                            <div class="col-8">
                                <a href="" class="btn btn-primary mx-1"> ပြန်၀ယ်မည် </a>
                                <a href="" class="btn btn-primary mx-1"> Search </a>

                            </div>
                        </div>





                    </div>




                </div>




            </div>

        </div>

        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>

<div class="modal fade" id="detail_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"> Detail </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> User </th>
                            <th> Amount </th>

                        </tr>
                    </thead>
                    <tbody id="detail_lottery">

                    </tbody>

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>





<script>

</script>
@endsection