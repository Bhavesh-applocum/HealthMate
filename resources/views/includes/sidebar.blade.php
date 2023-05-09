<div class="sidebar bg-primary-purple">
    <div class="inner">
        <ul>
            
            <li>
                <a href="" class="{{ (request()->is('dashboard')) ? 'active' : ''  }}" >
                    <div class="img_wrapper"><img src="{{ asset('/images/dashboard.svg') }}" alt="dashboard"width="23" height="23"></div>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- <li>
                <a href="#" class="{{ (request()->is('admin/user-admin/create')) ? 'active' : '' }}">
                    <div class="img_wrapper"><i class="fa-solid fa-plus"></i></div>
                    <span>add user</span>
                </a>
            </li> -->
            <li>
                <a href="#" class="{{ (request()->is('admin/users')) ? 'active' : '' }}" >
                    <div class="img_wrapper"><img src="{{ asset('/images/users.svg') }}" alt="users" width="23" height="23"></div>
                    <span>users</span>
                </a>
            </li>
            <li>
                <a href="#" class="{{ (request()->is('admin/todo*')) ? 'active' : ''  }}" >
                    <div class="img_wrapper"><img src="{{ asset('/images/todo-white.svg') }}" alt="users" width="23" height="23"></div>
                    <span>todo</span>
                </a>
            </li>
            <!-- <li><a href="javascript:;">Users</a></li> -->
            <!-- <li><a href="javascript:;">Posts</a></li>
                <li><a href="javascript:;">Categories</a></li>
                <li><a href="javascript:;">Tags</a></li>
                <li><a href="javascript:;">Comments</a></li>
                <li><a href="javascript:;">Settings</a></li> -->
        </ul>
    </div>
</div>