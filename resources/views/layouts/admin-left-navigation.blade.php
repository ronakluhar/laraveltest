<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{asset('css/admin/dist/img/avatar5.png')}}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><a href="#">{{ucfirst(Auth::user()->name)}}</a></p>
      </div>
    </div>
    <ul class="sidebar-menu">
      <li class="header"><center>=================================</center></li>
      <li class="{{ (Request::is('admin/create-user') || Request::is('admin/list-user')) ? 'active treeview' : 'treeview' }}">
        <a href="#">
          <i class="fa fa-dashboard"></i> <span>User Management</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ Request::is('admin/add-user') ? 'active' : '' }}"><a href="{{url('admin/add-user')}}"><i class="fa fa-circle-o"></i>Create User</a></li>
          <li class="{{ Request::is('admin/users') ? 'active' : '' }}"><a href="{{url('admin/users')}}"><i class="fa fa-circle-o"></i> List Users</a></li>
        </ul>
      </li>
      <li class="{{ (Request::is('admin/audit-log') || Request::is('admin/audit-log-chart')) ? 'active treeview' : 'treeview' }}">
        <a href="#">
          <i class="fa fa-dashboard"></i> <span>Audit Log</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="{{ Request::is('admin/audit-log') ? 'active' : '' }}"><a href="{{url('admin/audit-log')}}"><i class="fa fa-circle-o"></i>Audit Log</a></li>
          <li class="{{ Request::is('admin/audit-log-chart') ? 'active' : '' }}"><a href="{{url('admin/audit-log-chart')}}"><i class="fa fa-circle-o"></i>Audit Log Chart</a></li>
        </ul>
      </li>
    </ul>
  </section>
</aside>