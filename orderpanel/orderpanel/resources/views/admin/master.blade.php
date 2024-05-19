<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title','Order Panel') || Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <link rel="icon" href="https://bdchinashop.com/uploads/logo/logo_64bc2c549f104.PNG" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset ('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  {{-- <link rel="stylesheet" href="{{ asset ('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}"> --}}
  <!-- iCheck -->
  {{-- <link rel="stylesheet" href="{{ asset ('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}"> --}}
  <!-- JQVMap -->
  {{-- <link rel="stylesheet" href="{{ asset ('admin/plugins/jqvmap/jqvmap.min.css') }}"> --}}
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset ('admin/dist/css/adminlte.min.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset ('admin/dist/css/custom.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset ('admin/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset ('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- overlayScrollbars -->
  {{-- <link rel="stylesheet" href="{{ asset ('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> --}}
  <!-- Daterange picker -->
  {{-- <link rel="stylesheet" href="{{ asset ('admin/plugins/daterangepicker/daterangepicker.css') }}"> --}}
  <!-- summernote -->
  {{-- <link rel="stylesheet" href="{{ asset ('admin/plugins/summernote/summernote-bs4.css') }}"> --}}

  @stack('css')
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  @include('admin.include.header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->

    <!-- Sidebar -->
    @include('admin.include.sidebar')
    <!-- /.sidebar -->


  <!-- Content Wrapper. Contains page content -->

    <!-- /.content-header -->

    <!-- Main content -->
    @yield('content')

    <!-- /.content -->

  <!-- /.content-wrapper -->

  <!-- /.footer -->

  @include('admin.include.footer')


  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset ('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset ('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset ('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
{{-- <script src="{{ asset ('admin/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset ('admin/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{ asset ('admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ asset ('admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset ('admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset ('admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset ('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset ('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset ('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset ('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset ('admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{ asset ('admin/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{-- <script src="{{ asset ('admin/dist/js/pages/dashboard.js')}}"></script> --}}
<!-- AdminLTE for demo purposes -->
<script src="{{ asset ('admin/dist/js/demo.js')}}"></script>
<script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
      })
</script>
@stack('js')
</body>
</html>
