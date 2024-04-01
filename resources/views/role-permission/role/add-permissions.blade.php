@extends('layout.main')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Role : {{ $role->name }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('master-users/roles') }}">Roles</a></li>
                            <li class="breadcrumb-item active">Edit Roles</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ url('master-users/roles/'.$role->uuid.'/give-permissions') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-4">
                            <!-- general form elements -->
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Give Permissions</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="icheck-primary custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="select-all">
                                                <label  for="select-all">Select All</label>
                                            </div>
                                            @foreach ($permissions as $permission)
                                            <div class="icheck-primary custom-checkbox">
                                                <input class="permission-checkbox" type="checkbox" id="permission_{{ $permission->uuid }}" name="permission[]" value="{{ $permission->uuid }}"
                                                {{ in_array($permission->uuid, $rolePermissions) ? 'checked':'' }}>
                                                <label for="permission_{{ $permission->uuid }}" >{{ $permission->name }}</label>
                                            </div>
                                        @endforeach
                                        </div>
                                        
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <a href="{{ url('master-users/roles') }}" class="btn btn-secondary">kembali</a>
                                        <button type="submit" class="btn btn-info">Update</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->

                        </div>
                </form>
            </div>
            <!-- /.row -->
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#select-all').on('change', function() {
                $('.permission-checkbox').prop('checked', $(this).prop('checked'));
            });

            $('.permission-checkbox').on('change', function() {
                if (!$(this).prop('checked')) {
                    $('#select-all').prop('checked', false);
                } else {
                    var allChecked = true;
                    $('.permission-checkbox').each(function() {
                        if (!$(this).prop('checked')) {
                            allChecked = false;
                            return false;
                        }
                    });
                    $('#select-all').prop('checked', allChecked);
                }
            });
        });

    </script>
@endsection
