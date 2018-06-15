@extends('eosadm::main_layout')

@section('page-plugin')
    <script type="text/javascript" src="/resources/default/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="/resources/default/js/plugins/forms/selects/select2.min.js"></script>
@endsection

@section('page-script')
    <script type="text/javascript" src="/resources/default/js/pages/system/config/index.js"></script>
@endsection

@section('main-body')
    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">{{$nkey->name}}</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="reload" id="reload"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="tabbable">
                        @if($mode == 'tab')
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            @foreach($group as $key => $value)
                                <li class="{{$value -> key == $page ? 'active' : ''}}"><a href="{{admurl('system/config', ['to' => $value -> key, 'mode' => I('mode')])}}">{{$value -> name}}</a></li>
                            @endforeach
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-plus2"></i> <span class="caret"></span></a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{admurl('system/config?o=group')}}">新增分组</a></li>
                                    <li><a href="{{admurl('system/config?o=config')}}">新增配置</a></li>
                                </ul>
                            </li>
                            <li><a href="javascript:;" id="config_add"></a></li>
                        </ul>
                        @endif

                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="table-responsive">
                                    <form id="form-config">

                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th class="col-md-2">变量标题</th>
                                                <th class="col-md-5">变量值</th>
                                                <th class="col-md-3"></th>
                                                <th class="col-md-2">变量名</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($list as $key => $value)
                                                <tr>
                                                    <td class="showedit" data-show="showedit_{{$key}}">{{$value['title']}} @if($value['lock'] == 0) <a class="editlabel" id="showedit_{{$key}}" href="{{admurl('system/config', ['o' => 'config', 'id' => $value['id']])}}">编辑</a> @endif</td>
                                                    <td>
                                                        @if($value['type'] == 1)
                                                            <input type="text" class="form-control inputShowhelp" data-id="{{$key}}" name="{{$value['name']}}" value="{{$value['value']}}">
                                                        @endif
                                                        @if($value['type'] == 2)
                                                            <textarea class="form-control inputShowhelp" data-id="{{$key}}" name="{{$value['name']}}" rows="5" cols="5">{{$value['value']}}</textarea>
                                                        @endif
                                                        @if($value['type'] == 3)
                                                            <input type="number" class="form-control inputShowhelp" data-id="{{$key}}" name="{{$value['name']}}" value="{{$value['value']}}">
                                                        @endif
                                                        @if($value['type'] == 4)
                                                            <input type="password" class="form-control inputShowhelp" data-id="{{$key}}" name="{{$value['name']}}" value="{{$value['value']}}">
                                                        @endif

                                                        @if($value['type'] == 8)
                                                            <select class="form-control inputShowhelp" name="{{$value['name']}}" data-id="{{$key}}">
                                                                @foreach($value['extra'] as $_value)
                                                                    <option value="{{$_value['value']}}" @if($_value['value'] == $value['value']) selected="selected" @endif>{{$_value['name']}}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                            @if($value['type'] == 9)
                                                                @foreach($value['extra'] as $_value)
                                                                    <label class="radio-inline">
                                                                        <input type="radio" class="styled" data-id="{{$key}}" name="{{$value['name']}}" value="{{$_value['value']}}" @if($_value['value'] == $value['value']) checked="checked" @endif> {{$_value['name']}}
                                                                    </label>
                                                                @endforeach
                                                            @endif
                                                    </td>
                                                    <td><span id="help_{{$key}}" class="help-inline inputhelp" hidden>{{$value['remark']}}</span></td>
                                                    <td>C('{{$value['name']}}')</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td><i class="text-danger">*</i> 为必填</td>
                                                <td>
                                                    <button type="button" class="btn btn-info submit-form" data-loading-text="<i class='icon-spinner10 spinner position-left'></i> 提交中..." data-form="form-config">提交</button>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->
    <script>
//        $(".editlabel").hide();
//        $(".showedit").hover(function(){
//            $("#" + $(this).data('show')).show();
//        },function(){
//            $("#" + $(this).data('show')).hide();
//        });

    </script>

@endsection