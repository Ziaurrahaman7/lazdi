
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <!--<img src="logo.png" alt="Logo" class="brand-image img-circle elevation-3"-->
      <!--     style="opacity: .8">-->
      <span class="brand-text font-weight-light">Panel</span>
    </a>

    @php
        $route  = Route::current()->getName();
    @endphp

    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="{{route('dashboard')}}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                {{-- <i class="right fas fa-angle-left"></i> --}}
              </p>
            </a>
          </li>

          <li class="nav-item {{ ( $route == 'order.index' || $route == 'order.create' || $route == 'order.edit' || $route == 'order.show' || $route == 'order-status-report' || $route == 'showProductSales' || $route == 'currentMonthstaffReport') ? 'menu-open' : ' ' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-circle"></i>
              <p>
                Orders
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href=" {{ route('order.create') }}" class="nav-link {{ ( $route == 'order.create') ? 'active' : '' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Add New Order</p>
                  </a>
                </li>
            </ul>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('order.index') }}" class="nav-link {{ ( $route == 'order.index') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Order List</p>
                  </a>
                </li>
            </ul>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('order-status-report')}}" class="nav-link {{ ( $route == 'order-status-report') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Order Status Report</p>
                  </a>
                </li>
            </ul>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('showProductSales')}}" class="nav-link {{ ( $route == 'showProductSales') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Order Product Report</p>
                  </a>
                </li>
            </ul>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('currentMonthstaffReport')}}" class="nav-link {{ ( $route == 'currentMonthstaffReport') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Current Month Staff Report</p>
                  </a>
                </li>
            </ul>
        </li>

          <li class="nav-item {{ ( $route == 'redx-product-list' || $route == 'pathao-product-list' || $route == 'others-product-list') ? 'menu-open' : ' ' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-circle"></i>
              <p>
                Courier Report
                <i class="right fas fa-truck"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('redx-product-list')}}" class="nav-link {{ ( $route == 'redx-product-list') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Redx</p>
                  </a>
                </li>
            </ul>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('pathao-product-list')}}" class="nav-link {{ ( $route == 'pathao-product-list') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Pathao</p>
                  </a>
                </li>
            </ul>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('steadfast.product.list')}}" class="nav-link {{ ( $route == 'steadfast-product-list') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>SteadFast</p>
                  </a>
                </li>
            </ul>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('others-product-list')}}" class="nav-link {{ ( $route == 'others-product-list') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Other</p>
                  </a>
                </li>
            </ul>

            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('Courier.Booked.List')}}" class="nav-link {{ ( $route == 'courier-booked-list') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Courier Booked</p>
                  </a>
                </li>
            </ul>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('courier.check')}}" class="nav-link {{ ( $route == 'courier-check') ? 'active' : ' ' }}">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Courier check</p>
                  </a>
                </li>
            </ul>

          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
</aside>
