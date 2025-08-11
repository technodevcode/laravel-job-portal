@extends('front.layouts.app')

@section('main')
<section class="section-4 bg-2">    
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                @include('front.message')
                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                
                                <div class="jobs_conetent">
                                    <a href="#">
                                        <h4>{{ $job->job_title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i> {{ $job->JobType->name }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jobs_right">
                                <div class="apply_now {{ ($count == 1) ? 'saved-job' : '' }}">
                                    <a class="heart_mark" href="javascript:void(0);" onclick="saveJob({{ $job->id }})"> <i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Job description</h4>
                            <p>{{ $job->description }}</p>
                        </div>
                        <div class="single_wrap">
                            <h4>Responsibility</h4>
                            <p>{{ $job->responsibility }}</p>
                        </div>
                        <div class="single_wrap">
                            <h4>Qualifications</h4>
                            <p>{{ $job->qualifications }}</p>
                        </div>
                        <div class="single_wrap">
                            <h4>Benefits</h4>
                            <p>{{ $job->benefits }}</p>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="pt-3 text-end">
                            @if (Auth::check())
                                <a href="javascript:void(0);" onclick="saveJob({{ $job->id }});" class="btn btn-secondary">Save</a>  
                            @else
                                <a href="javascript:void(0);" class="btn btn-secondary disabled">Login to Save</a>
                             @endif

                            @if(Auth::check())
                                <a href="javascript::void();" onclick="applyJob({{ $job->id }})" class="btn btn-primary">Apply</a>
                            @else
                                <a href="javascript::void();" class="btn btn-primary disabled">Login to apply</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Job Summery</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Published on: <span>{{ $job->formatted_created_at }}</span></li>
                                <li>Vacancy: <span>{{ $job->vacancy }}</span></li>
                                <li>Salary: <span>{{ $job->salary }}</span></li>
                                <li>Location: <span>{{ $job->location }}</span></li>
                                <li>Job Nature: <span> {{ $job->JobType->name }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card shadow border-0 my-4">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Company Details</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Name: <span>{{ $job->company_name }}</span></li>
                                @if(!empty($job->company_location))
                                <li>Locaion: <span>{{ $job->company_location }}</span></li>
                                @endif
                                @if(!empty($job->company_website))
                                <li>Webite: <span><a href="{{ $job->company_website  }}">{{ $job->company_website }}</a></span></li>
                                @endif
                            </ul>
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
function applyJob(id){
    if (confirm("Are you sure you want to apply on this job?")) {
        $.ajax({
            url : '{{ route("applyJob") }}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success: function(response) {
                window.location.href = "{{ url()->current() }}";
            } 
        });
    }
}

function saveJob(id){
    $.ajax({
        url : '{{ route("saveJob") }}',
        type: 'post',
        data: {id:id},
        dataType: 'json',
        success: function(response) {
            window.location.href = "{{ url()->current() }}";
        } 
    });
}
</script>
@endsection