@extends('eosadm::layout')

@section('page-plugin')

@endsection

@section('page-script')
    <script type="text/javascript" src="/resources/default/js/pages/login.js"></script>
@endsection

@section('body')
    <body class="login-container login-cover">

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- login -->
                <div class="panel-body login-form">
                    <div class="text-center">
                        <div class="icon-object border-blue-300"><i class="icon-screen3"></i></div>
                        <h5 class="content-group">{{env('APP_NAME')}}管理系统</h5>
                    </div>

                    <div class="form-group has-feedback has-feedback-left">
                        <input type="text" class="form-control" id="Ex_un32" placeholder="用户名">
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group has-feedback has-feedback-left">
                        <input type="password" class="form-control" id="Ep_pw33" placeholder="密码">
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" id="Ea_sb31" class="btn bg-blue btn-block" data-loading-text="<i class='icon-spinner10 spinner position-left'></i> 登陆中...">登 陆 <i class="icon-arrow-right14 position-right"></i></button>
                    </div>


                    <span class="help-block text-center no-margin">如果您不是系统人员,请不要尝试登陆此系统. 为防范非法操作,您的登陆信息将被收集.</span>
                </div>

                <!-- /login -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->


    <!-- Footer -->
    <div class="footer text-muted text-center">
        &copy; 2018. Powered by <a href="#" target="_blank">osIdea</a>
    </div>
    <!-- /footer -->
@endsection