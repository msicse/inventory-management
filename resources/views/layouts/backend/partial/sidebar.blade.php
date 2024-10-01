<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src=" {{ asset('images/users/' . Auth::user()->image) }}" width="50" height="50" alt="User" />
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name }}</div>
            <div class="email">{{ Auth::user()->email }}</div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="{{ route('settings.profile') }}"><i class="material-icons">person</i>Profile</a></li>
                    <li>
                        <a href="{{ route('settings.password') }}">
                            <i class="material-icons">enhanced_encryption</i>
                            Change Pass
                        </a>
                    </li>


                    <li role="seperator" class="divider"></li>
                    <li><a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                                class="material-icons">input</i>Sign Out</a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->

    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <li class="header">MAIN NAVIGATION</li>

            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>
            @can("role-list")
            <li class="{{ Request::is('roles*') ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}">
                    <i class="material-icons">groups</i>
                    <span>Roles</span>
                </a>
            </li>
            @endcan
            @can("user-list")
            <li class="{{ Request::is('users*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}">
                    <i class="material-icons">people</i>
                    <span>Users</span>
                </a>
            </li>
            @endcan
            <li class="{{ Request::is('product-types') ? 'active' : '' }}">
                <a href="{{ route('product-types.index') }}">
                    <i class="material-icons">view_sidebar</i>
                    <span>Product Type</span>
                </a>
            </li>
            <li class="{{ Request::is('products*') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}">
                    <i class="material-icons">view_module</i>
                    <span>Products</span>
                </a>
            </li>
            <li class="{{ Request::is('suppliers*') ? 'active' : '' }}">
                <a href="{{ route('suppliers.index') }}">
                    <i class="material-icons">nordic_walking</i>
                    <span>Suppliers</span>
                </a>
            </li>
            <li class="{{ Request::is('purchases*') ? 'active' : '' }}">
                <a href="{{ route('purchases.index') }}">
                    <i class="material-icons">add_shopping_cart</i>
                    <span>Purchases</span>
                </a>
            </li>

            <li class="{{ Request::is('purchased-products*') ? 'active' : '' }}">
                <a href="{{ route('purchased.products') }}">
                    <i class="material-icons">add_shopping_cart</i>
                    <span>Purchased Products</span>
                </a>
            </li>

            <li class="{{ Request::is('departments*') ? 'active' : '' }}">
                <a href="{{ route('departments.index') }}">
                    <i class="material-icons">corporate_fare</i>
                    <span>Departments</span>
                </a>
            </li>
            <li class="{{ request()->is('employees*') ? 'active' : '' }}">
                <a href="{{ route('employees.index') }}">
                    <i class="material-icons">wc</i>
                    <span>Employees</span>
                </a>
            </li>
            <li class="{{ Request::is('inventories*') ? 'active' : '' }}">
                <a href="{{ route('inventories.index') }}">
                    <i class="material-icons">store_mall_directory</i>
                    <span>Inventories</span>
                </a>
            </li>
            <li class="{{ Request::is('transections*') ? 'active' : '' }}">
                <a href="{{ route('transections.index') }}">
                    <i class="material-icons">published_with_changes</i>
                    <span>Product Distribution</span>
                </a>
            </li>

            <li class="{{ Request::is('onboarding*') ? 'active' : '' }}">
                <a href="{{ route('onboardings') }}">
                    <i class="material-icons">published_with_changes</i>
                    <span>Onboarding Acknowledgement</span>
                </a>
            </li>

            <li class="{{ Request::is('management*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">widgets</i>
                    <span>Management</span>
                </a>
                <ul class="ml-menu">
                    <li class="{{ Request::is('management/employees*') ? 'active' : '' }}">
                        <a href="{{ route('management.employees') }}" class=" waves-effect waves-block">
                            <span>Employees</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('management/products*') ? 'active' : '' }}">
                        <a href="{{ route('management.products') }}" class=" waves-effect waves-block">
                            <span>Products</span>
                        </a>
                    </li>

                </ul>
            </li>


            <li class="header">Reports</li>

            <li class="{{ Request::is('reports/employees*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}">
                    <i class="material-icons">published_with_changes</i>
                    <span>Employees</span>
                </a>
            </li>

            <li class="{{ Request::is('reports/transections*') ? 'active' : '' }}">
                <a href="{{ route('reports.transections') }}">
                    <i class="material-icons">published_with_changes</i>
                    <span>Transections</span>
                </a>
            </li>
            <li class="{{ Request::is('reports/stocks*') ? 'active' : '' }}">
                <a href="{{ route('reports.stocks') }}">
                    <i class="material-icons">published_with_changes</i>
                    <span>Stocks</span>
                </a>
            </li>
            <!-- <li class="menu-toggle waves-effect waves-block toggled">
                <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                    <i class="material-icons">widgets</i>
                    <span>Employee</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="{{ route('employees.create') }}" class=" waves-effect waves-block">
                            <span>Add Employee</span>
                        </a>
                        <a href="{{ route('employees.index') }}" class=" waves-effect waves-block">
                            <span>All Employees</span>
                        </a>
                    </li>

                </ul>
            </li> -->


            @auth
                <li class="header">Settings</li>
                <li class="{{ Request::is('settings/profile*') ? 'active' : '' }}">

                    <a href="{{ route('settings.profile') }}">
                        <i class="material-icons">manage_accounts</i>
                        <span>Update Profile</span>
                    </a>
                </li>
                <li class="{{ Request::is('settings/password*') ? 'active' : '' }}">

                    <a href="{{ route('settings.password') }}">
                        <i class="material-icons">enhanced_encryption</i>
                        <span>Change Password</span>
                    </a>
                </li>
                <li>

                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="material-icons">input</i>
                        <span>Logout</span>
                    </a>
                </li>
            @endauth



        </ul>
    </div>
    <!-- #Menu -->
    <!-- Footer -->
    <div class="legal">
        <div class="copyright">
            &copy;
            <script>
                document.write(new Date().getFullYear());
            </script>
            All rights reserved | by
            <a href="https://rsc-bd.org">RSC IT</a>
        </div>
        <div class="version">
           Version  1.0.1
        </div>
    </div>
    <!-- #Footer -->
</aside>

Copyright ©
