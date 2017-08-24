@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        User Management
        <small>Users</small>
    </h1>
</section>

<section class="content">
    <div class="row">        
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Users List</h3>
                </div>
                <div class="box-body">
                    <table id="listUser" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Unique Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Online Status</th>
                                <th>Photo</th>                  
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    {{$user['unique_id']}}
                                </td>
                                <td>
                                    {{$user['name']}}
                                </td>
                                <td>
                                    {{ $user['email'] }}
                                </td>
                                <td>
                                    {{ $user['phone'] }}
                                </td>
                                <td>                             
                                    {!!Helpers::showUserOnlineStatus($user['onlinestatus'],$user['is_login'])!!}                              
                                </td>
                                <td>
                                    <?php
                                    if (File::exists(public_path($uploadUserThumbPath . $user['photo'])) && $user['photo'] != '') {
                                        ?>
                                        <img src="{{ url($uploadUserThumbPath.$user['photo']) }}" alt="{{$user['photo']}}"  height="50" width="50">
                                    <?php } else { ?>
                                        <img src="{{ asset('/uploads/user/thumb/default.png')}}" class="user-image" alt="Default Image" height="50" width="50">
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="{{ url('/admin/edituser') }}/{{$user['id']}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;

                                    <a onclick="return confirm('Are you sure you want to delete this record?')" href="{{ url('/admin/deleteuser') }}/{{$user['id']}}"><i class="i_delete fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6"><center>No Record Found</center></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!--/.col (right) -->
    </div>
    <!-- /.row -->
</section>
@endsection
@section('script')

<script>
    var dataCount = '<?php echo count($users) ?>';
    if(dataCount > 0){
        $(function () {
            $("#listUser").DataTable();
        });
    }
</script>
@endsection