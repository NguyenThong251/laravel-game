<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', systemconfig('site_title') ?? 'Linyou') - 后台管理系统</title>
    <meta name="keywords" content="{{ systemconfig('site_title') ?? 'Linyou' }}">
    <meta name="description" content="{{ systemconfig('site_title') ?? 'Linyou' }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('/css/base.css') }}">

    <style>
        .lyear-wrapper {
            position: relative;
        }

        .lyear-login {
            display: flex !important;
            min-height: 100vh;
            align-items: center !important;
            justify-content: center !important;
        }

        .login-center {
            background: #fff;
            min-width: 29.25rem;
            padding: 2.14286em 3.57143em;
            border-radius: 5px;
            margin: 2.85714em;
        }

        .login-header {
            margin-bottom: 1.5rem !important;
        }

        .login-center .has-feedback.feedback-left .form-control {
            padding-left: 38px;
            padding-right: 12px;
        }

        .login-center .has-feedback.feedback-left .form-control-feedback {
            left: 0;
            right: auto;
            width: 38px;
            height: 38px;
            line-height: 38px;
            z-index: 4;
            color: #dcdcdc;
        }

        .login-center .has-feedback.feedback-left.row .form-control-feedback {
            left: 15px;
        }

    </style>
</head>

<body>

    <div class="lyear-login">
        <div class="login-center">
            <div class="login-header text-center">
                <a href="#">
                    <img alt="light year admin" src="/images/logo-sidebar.png" style="max-width: 300px;">
                </a>
            </div>
            <form action="{{ route("admin.post_login") }}" method="post">
                @csrf
                
                <div class="form-group has-feedback feedback-left">
                    <input type="text" placeholder="Please enter 您的username" class="form-control" name="name" id="name" value=""/>
                    <span class="mdi mdi-account form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class="form-group has-feedback feedback-left">
                    <input type="password" placeholder="Please enter 密码" class="form-control" id="password" name="password" value="" />
                    <span class="mdi mdi-lock form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class="form-group has-feedback feedback-left row">
                    <div class="col-xs-7">
                        <input type="text" name="captcha" class="form-control" placeholder="验证码">
                        <span class="mdi mdi-check-all form-control-feedback" aria-hidden="true"></span>
                    </div>
                    <div class="col-xs-5">
                        <img src="{{captcha_src('flat')}}" class="pull-right" id="captcha" style="cursor: pointer;"
                            onclick="this.src=this.src+'?d='+Math.random();" title="点击refresh" alt="captcha">
                    </div>
                </div>

                @if(systemconfig('is_backend_google_auth'))
                <div class="form-group has-feedback feedback-left">
                    <input type="number" placeholder="Please enter 谷歌验证码" class="form-control" id="code" name="code" value="" placeholder="如果未设置则不填"/>
                    <span class="mdi mdi-lock form-control-feedback" aria-hidden="true"></span>
                </div>
                @endif

                <div class="form-group">
                    {{-- <input class="btn btn-block btn-primary ajax-submit-btn" type="button" value="立即登录"> --}}
                    <input class="btn btn-block btn-primary" data-operate="ajax-submit" type="button" value="立即登录">
                </div>
                {{-- 
                <div>
                    <p style="color: red;"><b>账号：youke   密码：123456</b></p>
                </div>
                --}}
            </form>
            <hr>
            <footer class="col-sm-12 text-center">
                <p class="m-b-0">Copyright © {{ date('Y') }} <a href="{{ env('ADMIN_URL',env('APP_URL')) }}">{{ env('ADMIN_URL',env('APP_URL')) }}</a>. All right reserved</p>
            </footer>
        </div>
    </div>
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="{{ asset('/js/layer/layer.js') }}"></script>
    <script src="{{ asset('/js/ajax-submit-form.js') }}"></script>
</body>

</html>
