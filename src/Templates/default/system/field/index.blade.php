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

            <!-- Main content -->
            <div class="content-wrapper">
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
                    {{--{!! base_form('system_model') !!}--}}
                </div>
            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

@endsection