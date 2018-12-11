@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
       <!--  <div class="user-panel">
          <div class="pull-left image">
            <img src="https://placehold.it/160x160/00a65a/ffffff/&text={{ mb_substr(Auth::user()->name, 0, 1) }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div> -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">Administration</li>
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>


          <!-- <li><a href="{{ backpack_url('source') }}"> <span>Ticket Sources</span></a></li> -->
          <li><a href="{{ backpack_url('ticket') }}"> <span>Tickets DB</span></a></li>
          <li><a href="{{ backpack_url('status') }}"> <span>Ticket Statuses</span></a></li>
          <li><a href="{{ backpack_url('type') }}"> <span>Ticket Category Types</span></a></li>
          <li><a href="{{ backpack_url('category') }}"> <span>Ticket Categories</span></a></li>
          <li><a href="{{ backpack_url('subcategory') }}"> <span>Ticket SubCategories</span></a></li>
          <li><a href="{{ backpack_url('rootcause') }}"> <span>Ticket Root Causes</span></a></li>
          <li><a href="{{ backpack_url('vendor') }}"> <span>Vendors</span></a></li>
          <li><a href="{{ backpack_url('department') }}"> <span>Resolving Departments</span></a></li>
          <li><a href="{{ backpack_url('group') }}"> <span>Resolver Groups</span></a></li>
          <li><a href="{{ backpack_url('resolver') }}"> <span>Resolvers</span></a></li>
          <li><a href="{{ backpack_url('user') }}"> <span>Users</span></a></li>

          <!-- ======================================= -->
          
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
