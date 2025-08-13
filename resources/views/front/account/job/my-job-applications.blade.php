@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.message')
                <div class="row">
                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <h3 class="fs-4 mb-1">Jobs Applied</h3>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Job Created</th>
                                            <th scope="col">Applicants</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @if($appliedJobs->isNotEmpty())
                                            @foreach($appliedJobs as $appliedJob)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{ $appliedJob->job->job_title }}</div>
                                                        <div class="info1">{{ $appliedJob->job->jobType->name }} . {{ $appliedJob->job->location }}</div>
                                                    </td>
                                                    <td>{{ $appliedJob->job->formatted_created_at }}</td>
                                                    <td>130 Applications</td>
                                                    <td>
                                                        <div class="job-status text-capitalize">
                                                            {{ $appliedJob->job->status == 1 ? 'Active' : 'Block' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="action-dots float-end">
                                                            <a href="#" class="" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="{{ route('jobDetialPage', $appliedJob->job->id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="deleteJob({{ $appliedJob->job->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>  
            </div>
        </div>
    </div>
</section>
@endsection
@section('custom_js')
<script>
    function deleteJob(jobId) {
    if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                url : '{{ route("account.deleteJob") }}',
                type: 'post',
                data: {jobId: jobId},
                dataType: 'json',
                success: function(response) {
                    window.location.href='{{ route("account.myJobs") }}';
                }
            });
        } 
    }
</script>
@endsection