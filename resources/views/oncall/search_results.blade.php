@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Laporan Lembur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('laporan-lembur') }}">Home</a></li>
                            <li class="breadcrumb-item active">Lembur</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                               
                            </div>
                           
                            <div class="card-body ">
                            @if(isset($results) && count($results) > 0)
                                <table class="table table-bordered table-hover" id="laporan">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Approved</th>
                                            <th>Interval</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($results as $result)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $result->user_name }}</td>
                                            <td>{{ $result->start_date }}</td>
                                            <td>{{ $result->end_date }}</td>
                                            <td>{{ $result->updated_by }}</td>
                                            <td>{{ $result->interval }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <form action="{{ route('laporan-lembur') }}" method="GET">
                                    <div class="form-group row">
                                        <div class="col-2">
                                            <label>Tanggal Awal:</label>
                                                <div class="input-group date" id="start_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#start_date" name="start_date" required/>
                                                    <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="col-2">
                                            <label for="end_date" class="form-label">Tanggal Akhir:</label>
                                            <div class="input-group date" id="end_date" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#end_date" name="end_date" required/>
                                                <div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

@endsection