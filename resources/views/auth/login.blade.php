@extends('layouts.auth-app')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/auth.css') }}">
@endpush
@section('content')

<div class="auth-section auth-height">
    <div class="auth-content-card">
        <div class="container">
            <div class="auth-card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="auth-content">
                            <div class="auth-content-info">
                                <h2>Plasma Pen | Plasma Fibroblast Devices and World Class training</h2>
                                <p>30 Years Ago, Louise Walsh Introduced the UK to Semi-Permanent Makeup</p>
                                <div class="auth-illustration">
                                    <img src="{{ assets('assets/images/auth-1.png') }}" alt="" height="300">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 auth-form-info">
                        <div class="auth-form">
                            <div class="brand-logo">
                                <img src="{{ assets('assets/images/logo.svg') }}" alt="logo">
                            </div>
                            <h2>Admin Login</h2>
                            <p>To Get Into Plasmapen® Control Panal</p>
                            <form class="pt-4" action="{{ route('admin.authenticate') }}" method="post" id="login-form"> @csrf
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <button class="auth-form-btn" id="login" type="submit">Login</button>
                                    <button class="auth-form-btn d-none" id="wait" type="button">Please Wait <img style="padding: 0px;" width="30" src="{{ assets('assets/images/spinner4.svg') }}" alt=""></button>
                                </div>
                            </form>
                            <div class="mt-1 forgotpsw-text">
                                <a href="javascript:void(0);">I forgot my password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $.validator.addMethod("emailValidate", function(value) {
        return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
    }, 'Please enter valid email address.');

    $('#login-form').validate({
        rules: {
            email: {
                required: true,
                emailValidate: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            email: {
                required: 'Please enter email address',
            },
            password: {
                required: 'Please enter your password',
            },
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            let formData = new FormData(form);
            $.ajax({
                type: 'post',
                url: form.action,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#wait").removeClass('d-none')
                    $("#login").addClass('d-none')
                },
                success: function(response) {
                    if (response.status) {
                        window.location = response.route;
                        return false;
                    } else {
                        $(".toastdesc").text(response.message).addClass('text-danger');
                        launch_toast();
                        return false;
                    }
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON(data.responseText);
                    console.error(jsonValue.message);
                },
                complete: function() {
                    $("#login").removeClass('d-none')
                    $("#wait").addClass('d-none')
                },
            })
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });
</script>
@endpush