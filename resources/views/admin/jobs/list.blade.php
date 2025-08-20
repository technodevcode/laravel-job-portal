@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Home</a></li>
                        <li class="breadcrumb-item active">Jobs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                <div class="card border-0 shadow mb-4">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Jobs</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                <ul class="nav nav-tabs" id="jobTabs">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $status === 'active' ? 'active' : '' }}"
                                           href="{{ route('admin.jobs', ['status' => 'active']) }}">Active Jobs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $status === 'deleted' ? 'active' : '' }}"
                                           href="{{ route('admin.jobs', ['status' => 'deleted']) }}">Deleted Jobs</a>
                                    </li>
                                </ul>
                            </div>                           
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if ($jobs->isNotEmpty())
                                        @foreach ($jobs as $job)
                                        <tr>
                                            <td>{{ $job->id }}</td>
                                            <td>
                                                <p>{{ $job->job_title }}</p>
                                                <p>Applicants: {{ $job->applications->count() }}</p>
                                            </td>
                                            <td>{{ $job->user->name }}</td>
                                            <td>
                                                @if ($job->status == 1)
                                                    <p class="text-success">Active</p>
                                                @elseif ($job->status == 0)
                                                    <p class="text-danger">Inactive</p>
                                                @else
                                                <p class="text-danger">Block</p>
                                                @endif
                                            </td>
                                            <td>{{ $job->formatted_created_at }}</td>
                                            <td>
                                                <div class="action-dots ">
                                                    
                                                    @if($status === 'deleted')
                                                    <!-- Show Restore button -->
                                                        <form action="{{ route('admin.jobs.restore', $job->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                Restore
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="{{ route('admin.jobs.edit',$job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                            <li><a class="dropdown-item" onclick="deleteJob({{ $job->id }})" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                                                        </ul>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="6" style="text-align:center;">No records found.</td>
                                        </tr> 
                                    @endif
                                </tbody>                                
                            </table>
                        </div>
                        <div>
                            {{ $jobs->appends(['status' => $status])->links() }}
                        </div>
                    </div>
                </div>                          
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script type="text/javascript">
    function deleteJob(id) {
        if (confirm("Are you sure you want to delete?")) {
            jQuery.ajax({
                url: "{{ route('admin.jobs.destroy') }}",
                type: 'delete',
                data: { id: id},
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ route('admin.jobs') }}";
                }
            });
        }
    }
</script>
@endsection