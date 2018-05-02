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

                    {{--<div class="sidebar-category">--}}
                        {{--<div class="category-title">--}}
                            {{--<span>更多功能</span>--}}
                            {{--<ul class="icons-list">--}}
                                {{--<li><a href="#" data-action="collapse"></a></li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}

                        {{--<div class="category-content no-padding">--}}
                            {{--<ul class="navigation navigation-alt navigation-accordion">--}}
                                {{--<li><a href="{{admurl('system/field', ['o' => 'save', 'model_name' => I('model_name')])}}"><i class="icon-nbsp"></i> 添加字段</a></li>--}}
                                {{--<li><a href="{{admurl('system/model')}}"><i class="icon-grid5"></i> 模型管理</a></li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="sidebar-category">
                        <div class="category-title">
                            <span>模型列表</span>
                            <ul class="icons-list">
                                <li><a href="#" data-action="collapse"></a></li>
                            </ul>
                        </div>

                        <div class="category-content no-padding">
                            <ul class="navigation navigation-alt navigation-accordion">
                                @foreach($model as $value)
                                    <li @if($value -> model_name == I('model_name')) class="active" @endif><a href="{{admurl('system/field', ['model_name' => $value->model_name])}}">@if($value -> model_name == I('model_name')) <i class="icon-radio-checked"></i> @else <i class="icon-radio-unchecked"></i> @endif {{$value->name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

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
                                <a href="{{admurl('system/field', ['o' => 'save', 'model_name' => I('model_name')])}}" class="dropdown-toggle"><i class="icon-nbsp position-left"></i> 添加字段 </a>
                            </li>
                            <li class="dropdown">
                                <a href="{{admurl('system/model')}}" class="dropdown-toggle"><i class="icon-grid5 position-left"></i> 模型管理 </a>
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
                                <th>名称</th>
                                <th>字段</th>
                                <th>类型</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $key => $value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->field}}</td>
                                    <td>{{$value->type_text}}</td>
                                    <td>{{$value->created_at}}</td>
                                    <td>{{$value->updated_at}}</td>
                                    <td>
                                        <a class="label bg-info-300" href="{{admurl('system/field', ['o' => 'save', 'id' => $value->id])}}"><i class="icon-compose"></i> 编辑</a>
                                        <ajax class="label bg-danger-300" href="{{admurl('system/field', ['o' => 'del', 'id' => $value->id])}}" done="{{admurl('system/field', ['model_name' => I('model_name') ])}}" prompt="warning" prompt_title="操作确认" prompt_text="此操作不会联动所对应的表字段,是否确认?" prompt_color="#FF7043" prompt_btn_text="确定删除"><i class="icon-trash"></i> 删除</ajax>
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