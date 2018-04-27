<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="admin-path" content="{{ config('admin.perfix') }}">
    <title>{{$page_title}} | {{ env('APP_NAME') }}管理系统</title>

    <!-- Global stylesheets -->
    <link href="/resources/default/css/core/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/resources/default/css/core/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/resources/default/css/core/core.css" rel="stylesheet" type="text/css">
    <link href="/resources/default/css/core/components.css" rel="stylesheet" type="text/css">
    <link href="/resources/default/css/core/colors.css" rel="stylesheet" type="text/css">
    <link href="/resources/default/js/plugins/fakeLoader/fakeLoader.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="/resources/default/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="/resources/default/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="/resources/default/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="/resources/default/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="/resources/default/js/plugins/ui/nicescroll.min.js"></script>
    <script type="text/javascript" src="/resources/default/js/plugins/ui/drilldown.js"></script>
    <script type="text/javascript" src="/resources/default/js/plugins/ui/fab.min.js"></script>
    <script type="text/javascript" src="/resources/default/js/plugins/notifications/pnotify.min.js"></script>
    <script type="text/javascript" src="/resources/default/js/plugins/fakeLoader/fakeLoader.min.js"></script>

    <!-- /core JS files -->

    <!-- Theme JS files -->
    @section('page-plugin')

    @show

    <script type="text/javascript" src="/resources/default/js/core/app.js"></script>



    @section('page-script')

    @show
    <!-- /theme JS files -->

</head>
@section('body')

@show

<script type="text/javascript" src="/resources/default/js/core/core.js"></script>
</html>
