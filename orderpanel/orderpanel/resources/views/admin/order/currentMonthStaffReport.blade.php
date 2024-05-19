@extends('admin.master')
@section('title', 'Current month staff report')
@section('content')
<div class="content-wrapper" style="min-height: 114px;">
    <div class="content-header">
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Order Status Report</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="https://www.dhakabaazar.com/adminpanel" style="color:black">Home</a></li>
                <li class="breadcrumb-item active">Order Status Report</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>          <section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title"> Staff Order List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>New</th>
                                        <th>Pending</th>
                                        <th>Pending Payment</th>
                                        <th>Courier</th>
                                        <th>Print</th>
                                        <th>Cancel</th>
                                        <th>Delivered</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $report['username'] }}</td>
                                            <td>{{ $report['new'] }}</td>
                                            <td>{{ $report['pending'] }}</td>
                                            <td>{{ $report['pendingPayment'] }}</td>
                                            <td>{{ $report['courier'] }}</td>
                                            <td>{{ $report['invoice'] }}</td>
                                            <td>{{ $report['cancelled'] }}</td>
                                            <td>{{ $report['delivered'] }}</td>
                                            <td>
                                                {{-- Calculate the row total --}}
                                                {{ $report['new'] + $report['pending'] + $report['pendingPayment'] + $report['courier'] + $report['invoice'] + $report['cancelled'] + $report['delivered'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>

                </div>

            </div>
        </div>

</div>
</section>


</div>
@endsection
