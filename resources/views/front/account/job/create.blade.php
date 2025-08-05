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
                <form action="" method="post" id="createJobForm" name="createJobForm">
                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Job Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Title<span class="req">*</span></label>
                                    <input type="text" placeholder="Job Title" id="title" name="title" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Category<span class="req">*</span></label>
                                    <select class="form-select" name="category" id="category">
                                        <option value="">Select a Category</option>
                                        @if($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option> 
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Job Nature<span class="req">*</span></label>
                                    <select class="form-select" name="job_types">
                                        <option value=""> Select Job Nature </option> 
                                        @if($job_types->isNotEmpty())
                                            @foreach ($job_types as $job_type)
                                                <option value="{{ $job_type->id }}">{{ $job_type->name }}</option> 
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input type="number" min="1" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Salary</label>
                                    <input type="text" placeholder="Salary" id="salary" name="salary" class="form-control">
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location<span class="req">*</span></label>
                                    <input type="text" placeholder="location" id="location" name="location" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Description<span class="req">*</span></label>
                                <textarea class="form-control" name="description" id="description" cols="5" rows="5" placeholder="Description"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Benefits</label>
                                <textarea class="form-control" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Responsibility</label>
                                <textarea class="form-control" name="responsibility" id="responsibility" cols="5" rows="5" placeholder="Responsibility"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Qualifications</label>
                                <textarea class="form-control" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Experience<span class="req">*</span></label>
                                <select class="form-select" name="experience" id="experience" class="form-control"> 
                                    <option value=""> Select Job Nature </option>
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Year</option>
                                    <option value="3">3 Year</option>
                                    <option value="4">4 Year</option>
                                    <option value="5">5 Year</option>
                                    <option value="6">6 Year</option>
                                    <option value="7">7 Year</option>
                                    <option value="8">8 Year</option>
                                    <option value="9">9 Year</option> 
                                    <option value="10">10 Year</option> 
                                    <option value="10_plus">10+ Year</option> 
                                </select>
                                <p></p>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Keywords</label>
                                <input type="text" placeholder="keywords" id="keywords" name="keywords" class="form-control">
                            </div>


                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Name<span class="req">*</span></label>
                                    <input type="text" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                                    <p></p>
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location</label>
                                    <input type="text" placeholder="Company Location" id="company_location" name="company_location" class="form-control">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Website</label>
                                <input type="text" placeholder="Company Website" id="company_website" name="company_website" class="form-control">
                            </div>
                        </div> 
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Save Job</button>
                        </div>               
                    </div>  
                </form>            
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
    jQuery('#createJobForm').submit(function(e){
        e.preventDefault();
        jQuery.ajax({
            url: '{{ route("account.saveJob") }}',  // or route in Laravel
            type: 'post',
            data: jQuery('#createJobForm').serializeArray(),
            dataType: 'json',
            success: function(response) {
                if(response.status == false){
                    var errors = response.errors;
                    if(errors.title){
                        jQuery('#title').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.title);
                    }else{
                        jQuery('#title').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.category){
                        jQuery('#category').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.category);
                    }else{
                        jQuery('#category').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.job_types){
                        jQuery('#job_types').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.job_types);
                    }else{
                        jQuery('#job_types').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.vacancy){
                        jQuery('#vacancy').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.vacancy);
                    }else{
                        jQuery('#vacancy').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.location){
                        jQuery('#location').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.location);
                    }else{
                        jQuery('#location').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.description){
                        jQuery('#description').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.description);
                    }else{
                        jQuery('#description').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.company_name){
                        jQuery('#company_name').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.company_name);
                    }else{
                        jQuery('#company_name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.experience){
                        jQuery('#experience').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.experience);
                    }else{
                        jQuery('#experience').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                }else{
                    jQuery('#title').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                    jQuery('#category').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                    jQuery('#job_types').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                    jQuery('#vacancy').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                    
                    jQuery('#location').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                    jQuery('#description').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                    
                    jQuery('#company_name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                    jQuery('#experience').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                    
                    window.location.href = "{{ route('account.myJobs') }}";
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
</script>
@endsection