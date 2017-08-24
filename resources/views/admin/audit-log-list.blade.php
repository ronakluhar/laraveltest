@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        Audit Log
        <small>Logs</small>
    </h1>
</section>

<section class="content">
    <div class="row">        
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Audit Log List</h3>
                </div>
                <div class="box-body">
                    <table id="listLog" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Logger</th>
                                <th>Action</th>
                                <th>Object</th>
                                <th>URL</th>
                                <th>IP Address</th>                                                  
                                <th>Message</th>                                                  
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditData as $audit)                            
                            <tr>
                                <td>
                                    {{ date('M d, Y h:i A',strtotime($audit['created_at'])) }}
                                </td>
                                <td>
                                    {{ $audit->getUserData->name or ''}}
                                </td>
                                <td>
                                    {{$audit['action']}}
                                </td>
                                <td>
                                    {{$audit['object_type']}}
                                </td>
                                <td>                             
                                    {{$audit['object_id']}}                              
                                </td>
                                <td>                             
                                    {{$audit['ip_address']}}                              
                                </td>
                                <td>                             
                                    {{$audit['message']}}                              
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
    var dataCount = '<?php echo count($auditData) ?>';
    if(dataCount > 0){
    $(function () {
        $("#listLog").DataTable();
    });
    }
</script>
@endsection