<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel" style="padding-bottom: 26px;">
        <div class="pull-left image">
            <?php
                $path = "";
                if(Auth::user()->profile_picture != NULL){
                    $path = Auth::user()->profile_picture; 
                }                                 
            ?>
            <img src="@if(isset(Auth::user()->profile_picture)) {{ PUBLIC_PATH.'images/'. $path }} @else {{ asset('assets/noimage.jpg') }} @endif" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{Auth::user()->first_name}}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>
    <ul class="sidebar-menu">
        <!-- Menu Footer-->

        <li class="nav-item">
            <a href="{{ route('dashboard') }}">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item has-sub-menus">
            <a href="javascript:;">
                <i class="fa fa-user"></i>
                <span>User Management</span>
                <div class="has-sub-menus-openbtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-right.svg') }}" /></div>
                <div class="has-sub-menus-closebtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-down.svg') }}" /></div>
            </a>
            <ul class="treeview-menu">
                <li class="treeview-menu-nav">
                    <a href="{{ route('user-list') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>User List</span>
                    </a>
                </li>

            </ul>
        </li>

        <li class="nav-item has-sub-menus">
            <a href="javascript:;">
                <i class="fa fa-user"></i>
                <span>Driver Management</span>
                <div class="has-sub-menus-openbtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-right.svg') }}" /></div>
                <div class="has-sub-menus-closebtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-down.svg') }}" /></div>
            </a>
            <ul class="treeview-menu">
                <li class="treeview-menu-nav">
                    <a href="{{ route('driver-list') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>Driver List</span>
                    </a>
                </li>

                <li class="treeview-menu-nav">
                    <a href="{{ route('driver-awaiting-list') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>Driver Awaiting Verification</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item has-sub-menus">
            <a href="javascript:;">
                <i class="fa fa-user"></i>
                <span>Car Make</span>
                <div class="has-sub-menus-openbtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-right.svg') }}" /></div>
                <div class="has-sub-menus-closebtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-down.svg') }}" /></div>
            </a>
            <ul class="treeview-menu">
                <li class="treeview-menu-nav">
                    <a href="{{ route('car-make-list') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>Car Make List</span>
                    </a>
                </li>

            </ul>
        </li>

        <li class="nav-item has-sub-menus">
            <a href="javascript:;">
                <i class="fa fa-user"></i>
                <span>Car Model</span>
                <div class="has-sub-menus-openbtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-right.svg') }}" /></div>
                <div class="has-sub-menus-closebtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-down.svg') }}" /></div>
            </a>
            <ul class="treeview-menu">
                <li class="treeview-menu-nav">
                    <a href="{{ route('car-model-list') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>Car Model List</span>
                    </a>
                </li>

            </ul>
        </li>

        <li class="nav-item has-sub-menus">
            <a href="javascript:;">
                <i class="fa fa-user"></i>
                <span>Driver Approval Amount</span>
                <div class="has-sub-menus-openbtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-right.svg') }}" /></div>
                <div class="has-sub-menus-closebtn has-sub-menusbtn"><img src="{{ asset('assets/images/chevron-down.svg') }}" /></div>
            </a>
            <ul class="treeview-menu">
                <li class="treeview-menu-nav">
                    <a href="{{ route('approval-amount-list') }}">
                        <i class="fa fa-circle-o"></i>
                        <span>Driver Approval Amount List</span>
                    </a>
                </li>

            </ul>
        </li>
        
    </ul>
</section>
