@extends('front.layouts.app')

@section('main')
<section class="section-3 py-5 bg-2 ">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10 ">
                <h2>Find Jobs</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <select name="sort" id="sort" class="form-control">
                        <option value="1" {{ (Request::get('sort') == '1') ? 'selected' : '' }}>Latest</option>
                        <option value="0" {{ (Request::get('sort') == '0') ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-4 col-lg-3 sidebar mb-4">
                <form class="" name="searchForm" action="" id="searchForm"> 
                    <div class="card border-0 shadow p-4">
                        <div class="mb-4">
                            <h2>Keywords</h2>
                            <input value="{{ Request::get('keyword') }}" type="text" placeholder="Keywords" name="keyword" class="form-control" id="keyword">
                        </div>

                        <div class="mb-4">
                            <h2>Location</h2>
                            <input value="{{ Request::get('location') }}" type="text" placeholder="Location" name="location" class="form-control" id="location">
                        </div>

                        <div class="mb-4">
                            <h2>Category</h2>
                            <select name="category" id="category" class="form-control">
                                <option value="">Select a Category</option>
                                @if($categories->isNotEmpty())
                                    @foreach ($categories as $category)
                                        <option {{ (Request::get('category') == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>                   

                        <div class="mb-4">
                            <h2>Job Type</h2>
                            @if($job_types->isNotEmpty())
                                @foreach ($job_types as $job_type) 
                                    <div class="form-check mb-2"> 
                                        <input {{ (in_array($job_type->id, $job_type_array)) ? 'checked' : ''}} class="form-check-input" name="job_type" type="checkbox" value="{{ $job_type->id }}" id="job_type-{{ $job_type->id }}">    
                                        <label class="form-check-label" for="job_type-{{ $job_type->id }}">{{ $job_type->name }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mb-4">
                            <h2>Experience</h2>
                            <select name="experience" id="experience" class="form-control">
                                <option value="">Select Experience</option>
                                @foreach($experienceOptions as $value => $label)
                                    <option {{ (Request::get('experience') == $value) ? 'selected' : '' }} value="{{ $value }}"> {{ $label }} </option>
                                @endforeach
                            </select>
                        </div>                    
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route("jobs") }}" class="btn btn-secondary mt-3">Reset</a>
                    </div>
                </form>
            </div>
            <div class="col-md-8 col-lg-9 ">
                <div class="job_listing_area">                    
                    <div class="job_lists">
                    <div class="row">
                        @if($jobs->isNotEmpty())
                            @foreach ($jobs as $job)
                                <div class="col-md-4">
                                    <div class="card border-0 p-3 shadow mb-4">
                                        <div class="card-body">
                                            <h3 class="border-0 fs-5 pb-2 mb-0">{{ $job->job_title }}</h3>
                                            <p>{{ Str::words(strip_tags($job->description), 10) }}</p>
                                            <div class="bg-light p-3 border">
                                                <p class="mb-0">
                                                    <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                    <span class="ps-1">{{ $job->location }}</span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                    <span class="ps-1">{{ $job->JobType->name }}</span>
                                                </p>
                                                {{-- <p class="mb-0">
                                                    <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                    <span class="ps-1">{{ $job->category->name }}</span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                    <span class="ps-1">Experience {{ $job->experience }} Years</span>
                                                </p> --}}
                                                @if(!is_null($job->salary))
                                                <p class="mb-0">
                                                    <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                    <span class="ps-1">{{ $job->salary }}</span>
                                                </p>
                                                @endif
                                            </div>

                                            <div class="d-grid mt-3">
                                                <a href="{{ route('jobDetialPage', $job->id) }}" class="btn btn-primary btn-lg">Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            @endforeach
                            <div class="col-md-12">
                                {{ $jobs->withQueryString()->links() }}
                            </div>
                        @else
                            <div class="col-md-12">Jobs not found</div>
                        @endif                  
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
    jQuery("#searchForm").submit(function(e){
        e.preventDefault();

        var url = '{{ route("jobs") }}?';

        var keyword = $("#keyword").val();
        var location = $("#location").val();
        var category = $("#category").val();
        var experience = $("#experience").val();
        var sort = $("#sort").val();

        var checkedJobTypes = $("input:checkbox[name='job_type']:checked").map(function(){
            return $(this).val();
        }).get();

        // If keyword has a value
        if (keyword != "") {
            url += '&keyword='+keyword;
        }

        // If location has a value
        if (location != "") {
            url += '&location='+location;
        }

        // If category has a value
        if (category != "") {
            url += '&category='+category;
        }

        // If experience has a value
        if (experience != "") {
            url += '&experience='+experience;
        }

        // If user has checked job types
        if (checkedJobTypes.length > 0) {
            url += '&jobType='+checkedJobTypes;
        }

        url += '&sort='+sort;

        window.location.href=url;
        
    });

    jQuery("#sort").change(function(){
        jQuery("#searchForm").submit();
    });

</script>
@endsection