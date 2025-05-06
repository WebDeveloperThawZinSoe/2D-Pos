@extends('web.master')

@section('body')
<div class="container py-4">
    <div class="row">
        <!-- Create User Form -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Create New Agent</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label>Commission</label>
                            <input type="number" name="commission" class="form-control" value="0">
                        </div>

                        <div class="mb-2">
                            <label>Rate</label>
                            <input type="number" name="rate" class="form-control" value="80">
                        </div>

                        <div class="mb-2">
                            <label>Max Limit</label>
                            <input type="number" name="max_limit" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>End AM Time</label>
                            <input type="time" name="end_am" value="11:30" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>End PM Time</label>
                            <input type="time" name="end_pm" value="15:50" class="form-control">
                        </div>


                        <button type="submit" class="btn btn-success w-100">Create User</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Agent List</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Commission</th>
                                <th>Rate</th>
                                <th>Max Limit</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>AM Time</th>
                                <th>PM Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $parent_id = Auth::user()->id;
                            $clients = App\Models\User::where("manager_id", $parent_id)->get();
                            @endphp
                            @forelse($clients as $key => $client)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->commission }}</td>
                                <td>{{ $client->rate }}</td>
                                <td>{{ $client->max_limit }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->plain_password }}</td>
                                <td>{{ $client->end_am }}</td>
                                <td>{{ $client->end_pm }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-muted">No users found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection