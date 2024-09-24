<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
    <title>Admin | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6-->
    <link rel="stylesheet" href="{{ PUBLIC_PATH . 'assets/admin/bootstrap/css/bootstrap.min.css' }}" type="text/css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ PUBLIC_PATH . 'assets/admin/font-awesome/css/font-awesome.min.css' }}" type="text/css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ PUBLIC_PATH . 'assets/admin/ionicons/css/ionicons.min.css' }}" type="text/css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ PUBLIC_PATH . 'assets/admin/dist/css/AdminLTE.min.css' }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
</head>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <div class="logo_block">
            <img class="list_table_img" id="img" src="{{ PUBLIC_PATH.'images/flash_img.JPEG' }} " alt="No Logo">
        </div>
        <br><a href="{{ url('admin') }}"><b>Flash</b></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Login</p>
        <form method="post" action="{{ url('admin/admin-login') }}" id="login_form">
            {{ csrf_field() }}
            @if (!empty($errors))
                <div class="validation_error">
                    
                        @foreach ($errors->all() as $error)
                            <p style="color:red;">{{ $error }}</p>
                        @endforeach
                    
                </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              {{ session('success') }}
            </div>
            @endif
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password" id="pass">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-lg-12 col-xs-12">
                    <button type="button" class="btn btn-primary btn-block btn-flat" onclick="validateLogin()">Sign In</button>
                </div><!-- /.col -->
            </div>
        </form>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->


<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
<!-- Bootstrap 3.3.6-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
<script>
    function validateLogin(){
        let email = $("#email").val();
        let pwd = $("#pass").val();
        if(email.trim().length == ""){
            $("#email").focus();
        } else if(pwd.trim().length == "")
        {
            $("#pass").focus();
        }else{
            $("#login_form").submit();
        }
    } 
    $('#login_form').keypress((e) => { 
        if (e.which === 13) { 
            $('#login_form').submit(); 
        } 
    })     
</script>
</body>
</html>
