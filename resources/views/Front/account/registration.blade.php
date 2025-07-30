@extends('front.layouts.app')

@section('main')

<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form action="" name="registrationForm" id="registrationForm">
                        <div class="mb-3">
                            <label for="" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="" class="mb-2">Email*</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Enter Password">
                            <p></p>
                        </div> 
                        <button class="btn btn-primary mt-2">Register</button>
                    </form>                    
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a  href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom_js')
<script>
    jQuery('#registrationForm').submit(function(e){
        e.preventDefault();
        jQuery.ajax({
            url: '{{ route("account.processRegistration") }}',  // or route in Laravel
            type: 'POST',
            data: jQuery('#registrationForm').serializeArray(),
            dataType: 'json',
            success: function(response) {
                if(response.status == false){
                    var errors = response.errors;
                    if(errors.name){
                        jQuery('#name').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.name);
                    }else{
                        jQuery('#name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.email){
                        jQuery('#email').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.email);
                    }else{
                        jQuery('#email').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.password){
                        jQuery('#password').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.password);
                    }else{
                        jQuery('#password').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if(errors.confirm_password){
                        jQuery('#confirm_password').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(errors.confirm_password);
                    }else{
                        jQuery('#confirm_password').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                }else{
                    jQuery('#name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                    jQuery('#email').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                    
                    jQuery('#password').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();
                    
                    jQuery('#confirm_password').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html();

                    window.location.href = "{{ route('account.login') }}";
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
</script>
@endsection