@extends('web.master')

@section('body')
<div class="container py-4">
    <div class="row">
        <!-- Edit User Form -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Agent</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dine.updateAgent', $agent->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name', $agent->name) }}" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $agent->email) }}" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                            <small class="form-text text-muted">Leave empty to keep current password.</small>
                        </div>

                        <div class="mb-2">
                            <label>Commission</label>
                            <input type="number" name="commission" value="{{ old('commission', $agent->commission) }}" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Rate</label>
                            <input type="number" name="rate" value="{{ old('rate', $agent->rate) }}" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Max Limit</label>
                            <input type="number" name="max_limit" value="{{ old('max_limit', $agent->max_limit) }}" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>End AM Time</label>
                            <input type="time" name="end_am" value="{{ old('end_am', $agent->end_am) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>End PM Time</label>
                            <input type="time" name="end_pm" value="{{ old('end_pm', $agent->end_pm) }}" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Update Agent</button>
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
                            @foreach($clients as $key => $client)
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
                                    <a href="{{ route('dine.editAgent', $client->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('dine.deleteAgent', $client->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this agent?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
