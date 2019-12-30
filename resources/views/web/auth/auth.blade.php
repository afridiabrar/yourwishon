@extends('web.layout.app')
@section('content')
<style>
	.modal-dialog {    max-width: 500px;    margin: 1.75rem auto;}
	</style>
<div class="main-wrapper">
    <!-- breadcrumb-area start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- breadcrumb-list start -->
                    <ul class="breadcrumb-list">
                    <li class="breadcrumb-item"><a href="{{ route('index')}}">Home</a></li>
                        <li class="breadcrumb-item active">Authencation</li>
                    </ul>
                    <!-- breadcrumb-list end -->
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area end -->
    <!-- main-content-wrap start -->
    <div class="main-content-wrap section-pb lagin-and-register-page mt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                    <div class="login-register-wrapper">
                        <!-- login-register-tab-list start -->
                        <div class="login-register-tab-list nav">
                            <a class="active" data-toggle="tab" href="#lg1">
                                <h4> login </h4>
                            </a>
                            <a data-toggle="tab" href="#lg2">
                                <h4> register </h4>
                            </a>
                        </div>
                        <!-- login-register-tab-list end -->
                        @include('web.layout.error')
                        <div class="tab-content">
                            <div id="lg1" class="tab-pane active">
                                <div class="login-form-container">
                                    <div class="login-register-form">
                                    <form action="{{ route('login')}}" method="post">
                                        @csrf
                                            <div class="login-input-box">
                                                <input type="email" name="email" required placeholder="Enter Email">
                                                
                                                    <div class="icon">
                                                        <input type="password" id="myInput" name="password" placeholder="Password">
                                                        <a class="cvb" href="javascript:void(0)">
                                                                <i onclick="myFunction()" class = "icon icon ion-ios-eye"></i>
                                                        </a>
                                                    </div>
                                            </div>
                                            <div class="button-box">
                                                <div class="login-toggle-btn">
                                                    <input type="checkbox">
                                                    <label>Remember me</label>
                                                    <a href="#" data-toggle="modal" data-target="#exampleModal">Forgot Password?</a>
                                                </div>
                                                <div class="button-box">
                                                    <button class="login-btn btn" type="submit"><span>Login</span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <script>
                                    function myFunction() {
                                      var x = document.getElementById("myInput");
                                      if (x.type === "password") {
                                        x.type = "text";
                                      } else {
                                        x.type = "password";
                                      }
                                    }
                            </script>
                            <div id="lg2" class="tab-pane">
                                <div class="login-form-container">
                                    <div class="login-register-form">
                                    <form action="{{ route('register')}}" method="post" id="form_submit">
                                        @csrf
                                            <div class="login-input-box">
                                                <input type="text" required value="{{ old('f_name') }}"  name="f_name" placeholder="First Name">
                                                <input type="text" required value="{{ old('l_name') }}" name="l_name" placeholder="Last Name">
                                                <input type="number" required value="{{ old('phone_no') }}" name="phone_no" placeholder="Phone No">
                                                <input name="email" required placeholder="Email" type="email">
                                                <input type="password" required name="password" placeholder="Password">
                                                <input type="password" required name="confirm_password" placeholder="Confirm Password">
                                            </div>
                                            <div class="button-box">
                                                    <div class="login-toggle-btn">
                                                            <input id="checkbox" type="checkbox" />
                                                        <a style="margin-right: 74%;" href="javascript:void(0)" onclick="hello()">Term & Condition</a>
                                                    </div>
                                                    <div class="button-box">
                                                        <button id="register_button" class="register-btn btn" type="button"><span>Register</span></button>
                                                    </div>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="conf"></div>

    <!-- main-content-wrap end -->
</div>    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/>
<script>
    function hello()
    {
        var csrf = '{{csrf_token()}}';
            $.post("{{ route('show_term') }}",{'_token':csrf},function (e) {
                $(".conf").html(e);
                $('#exampleModalLong').modal({ show: true });
                $('.modal-backdrop').hide();
                // $("#register_button").removeAttr('disabled');
                // $("#disclaimer").removeAttr('disabled');
            });
    }
   $(document).ready(function () {
    var ckbox = $('#checkbox');
    $('#register_button').on('click',function () {

        if (ckbox.is(':checked')) {
            
            $("#form_submit").submit();
        } else {
            var notification = alertify.notify('Please Accept Our Term & Condition', 'error', 5, function(){  console.log('dismissed'); }); 
        }
    });
});
</script>

{{-- modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    <form action="{{ route('send_mail') }}" method="post">
            @csrf
        <div class="modal-body">          
    <div class="form-group">
      <label for="exampleInputEmail1">Email address</label>
      <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send</button>
        </div>
    </form>

      </div>
    </div>
  </div>
  {{-- modal end --}}
<script>
    window.onload = function()
    {
        @if(Session::has('tab')) 
        $("a[href='#{{ Session::get('tab')}}']").click();
    @endif
    }
 </script>
@endsection
