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
                    <form action="{{ route('dine.storeAgent') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label>Commission</label>
                            <input type="number" name="commission" class="form-control" value="0">
                            @error('commission')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label>Rate</label>
                            <input type="number" name="rate" class="form-control" value="80">
                            @error('rate')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label>Max Limit</label>
                            <input type="number" name="max_limit" class="form-control" value="100000">
                            @error('max_limit')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label>End AM Time</label>
                            <input type="time" name="end_am" value="11:30" class="form-control">
                            @error('end_am')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>End PM Time</label>
                            <input type="time" name="end_pm" value="15:50" class="form-control">
                            @error('end_pm')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                                    <!-- Edit Button: Navigate to the edit form page -->
                                    <a href="{{ route('dine.editAgent', $client->id) }}"
                                        class="btn btn-sm btn-primary">Edit</a>

                                    <!-- Delete Button: Sends a POST request to delete the agent -->
                                    <form action="{{ route('dine.deleteAgent', $client->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this agent?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
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