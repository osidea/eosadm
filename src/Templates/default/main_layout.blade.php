@extends('eosadm::layout')

@section('body')
    <body class="navbar-bottom">
    <div class="fakeloader"></div>
    @include('eosadm::main_header')
    @section('main-body')

    @show
    @include('eosadm::main_footer')
    <div id="ajaxModel" class="modal fade" data-backdrop="" data-keyboard="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="/resources/global/img/loading-spinner-grey.gif" alt="" class="loading">
                    <span> &nbsp;&nbsp;载入中... </span>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(".fakeloader").fakeLoader({
                timeToHide:1200,
                bgColor:"#393c41",
                spinner:"spinner1"
            });
        });
    </script>
    </body>
@endsection