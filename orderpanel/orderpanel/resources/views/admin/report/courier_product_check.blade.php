@extends('admin.master')
@section('title', 'Courier Product')
@section('content')
@push('css')
<link rel="stylesheet" href="{{ asset('admin/DataTables/datatables.min.css') }}">
@endpush
<div class="content-wrapper" style="min-height: 105px;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Courier Product </h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#" style="color:black">Home</a>
                        </li>
                        <li class="breadcrumb-item active"> Courier Product </li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>


    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('courier.checked') }}" method="post" id="checkForm">
                @csrf
                <div class="form-group">
                    <label>CID/Invoice/Tracking Code</label>
                    <input type="text" name="check" class="form-control" id="check">
                </div>
                <div class="form-group">
                    <button type="button" class="form-control btn btn-success m-2 check-btn">Check</button>
                </div>
            </form>

            <div id="responseDiv"></div>


        </div>
    </section>

</div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        // Handle form submission
        $('#checkForm').submit(function (e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = $(this).serialize(); // Serialize form data

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // Use the form's action attribute as the URL
                data: formData,
                success: function (response) {
                    // Display the response in the 'responseDiv' element
                    $('#responseDiv').html(response);
                },
                error: function (xhr, status, error) {
                    // Handle errors if needed
                    console.error(xhr.responseText);
                },
            });
        });

        // Handle button clicks
        $('.check-btn').click(function () {
            $('#checkForm').submit(); // Submit the form
        });
    });

</script>
@endpush

