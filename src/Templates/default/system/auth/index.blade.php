@extends('eosadm::main_layout')

@section('page-plugin')
@endsection

@section('page-script')
@endsection

@section('main-body')
    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Secondary sidebar -->
            <div class="sidebar sidebar-secondary sidebar-default">
                <div class="sidebar-content">

                    <!-- Search task -->
                    <div class="sidebar-category">
                        <div class="category-title">
                            <span>搜索</span>
                            <ul class="icons-list">
                                <li><a href="#" data-action="collapse"></a></li>
                            </ul>
                        </div>

                        <div class="category-content">
                            <form action="#">
                                <div class="has-feedback has-feedback-left">
                                    <input type="search" class="form-control" placeholder="Type and hit Enter">
                                    <div class="form-control-feedback">
                                        <i class="icon-search4 text-size-base text-muted"></i>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /search task -->

                </div>
            </div>
            <!-- /secondary sidebar -->


            <!-- Main content -->
            <div class="content-wrapper">

                <div class="navbar navbar-default navbar-xs navbar-component">
                    <ul class="nav navbar-nav no-border visible-xs-block">
                        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
                    </ul>

                    <div class="navbar-collapse collapse" id="navbar-filter">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="{{admurl('system/auth', ['o' => 'save'])}}" class="dropdown-toggle"><i class="icon-nbsp position-left"></i> 添加规则 </a>
                            </li>
                        </ul>

                    </div>
                </div>

                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">{{ $page_title }}</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="reload" id="reload"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-break">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>权限名称</th>
                                <th>上级id</th>
                                <th>显示</th>
                                <th>权限</th>
                                <th>状态</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $key => $value)
                            <tr>
                                <td>{{$value->id}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->pid}}</td>

                                <td>
                                    @if($value->auth == 0)-@endif
                                    @if($value->auth == 1)受限@endif
                                </td>
                                <td>
                                    @if($value->show == 0)-@endif
                                    @if($value->show == 1)显示@endif
                                </td>
                                <td>
                                    @if($value->status == 0)-@endif
                                    @if($value->status == 1)正常@endif
                                </td>
                                <td>{{$value->created_at}}</td>
                                <td>{{$value->updated_at}}</td>
                                <td>
                                    <a class="label bg-info-300" href="{{admurl('system/auth', ['o' => 'save', 'id' => $value->id])}}"><i class="icon-compose"></i> 编辑</a>
                                    <ajax class="label bg-danger-300" href="{{admurl('system/auth', ['o' => 'del', 'id' => $value->id])}}" done="{{admurl('system/model')}}" prompt="warning" prompt_title="操作确认" prompt_text="删除模型不会同时删除相关联的表和文件,是否确认?" prompt_color="#FF7043" prompt_btn_text="确定删除"><i class="icon-trash"></i> 删除</ajax>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

@endsection