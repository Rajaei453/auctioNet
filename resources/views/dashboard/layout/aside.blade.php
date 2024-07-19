<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ asset ('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Reservations</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset ('dist/img/admin.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Users/Providers
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.users')}}" class="nav-link {{ request()->is('admin/users') ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.providers')}}" class="nav-link {{ request()->is('admin/providers') ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>providers</p>
                </a>
              </li>
            </ul>
            <li class="nav-item">
              <a href="{{ route('admin.categories')}}" class="nav-link {{ request()->is('admin/categories') ? 'active' : ''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                  categories
              </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.notifications')}}" class="nav-link {{ request()->is('admin/notifications') ? 'active' : ''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                  Notifications
              </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.reservations')}}" class="nav-link {{ request()->is('admin/reservations') ? 'active' : ''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                  Reservations
              </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.reviews')}}" class="nav-link {{ request()->is('admin/reviews') ? 'active' : ''}}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                  Reviews
              </p>
              </a>
            </li>
          </li>


        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
