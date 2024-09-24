<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6-->
    <link rel="stylesheet" href="{{ PUBLIC_PATH.'assets/admin/bootstrap/css/bootstrap.min.css' }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ PUBLIC_PATH.'assets/admin/font-awesome/css/font-awesome.min.css' }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ PUBLIC_PATH.'assets/admin/ionicons/css/ionicons.min.css' }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ PUBLIC_PATH.'assets/admin/dist/css/AdminLTE.min.css' }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ PUBLIC_PATH.'assets/admin/plugins/iCheck/square/blue.css' }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('admin') }}"><b>Admin</b></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Register</p>
        <form method="post" action="{{ url('register') }}">
            {{ csrf_field() }}
            @if (!empty($errors))
                <div class="validation_error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {!! session('success') !!}
            </div>
            @endif
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" autocomplete="off" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div></br>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" autocomplete="off" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div></br>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" name="email" id="email" autocomplete="off" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div></br>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password" id="pass" autocomplete="off" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div></br>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div><!-- /.col -->
            </div>
        </form>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ PUBLIC_PATH.'assets/admin/plugins/jQuery/jQuery-2.1.4.min.js' }}"></script>
<!-- Bootstrap 3.3.6-->
<script src="{{ PUBLIC_PATH.'assets/admin/bootstrap/js/bootstrap.min.js' }}"></script>
<!-- iCheck -->
<script src="{{ PUBLIC_PATH.'assets/admin/plugins/iCheck/icheck.min.js' }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
