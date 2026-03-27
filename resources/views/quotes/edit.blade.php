@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Quotes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('quotes') }}">Quotes</a></li>
                            <li class="breadcrumb-item active">Edit Quotes</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('quotes.update', ['id' => $quote->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Form Edit Quotes</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="quote">Quote</label>
                                            <textarea class="form-control" id="quote" rows="3" name="quote">{{ $quote->quote }}</textarea>
                                            @error('quote')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="author">Author</label>
                                            <input type="text" class="form-control" id="author"
                                                placeholder="Enter author" name="author" value="{{ $quote->author }}">
                                            @error('author')
                                            <small>
                                                <p class="text-danger">{{ $message }}</p>
                                            </small>
                                            @enderror
                                        </div>

                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->

                        </div>
                </form>
            </div>
            <!-- /.row -->
    </div><!-- /.container-fluid -->
    </section>
    </div>
@endsection
