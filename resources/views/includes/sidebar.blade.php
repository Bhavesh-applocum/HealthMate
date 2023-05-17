<div class="sidebar bg-primary-purple">
    <div class="inner">
        <ul>
            
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ (request()->is('dashboard')) ? 'active' : ''  }}" >
                    <div class="img_wrapper"><img src="{{ asset('/images/dashboard.svg') }}" alt="dashboard"width="23" height="23"></div>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.clients.index') }}" class="{{ (request()->is('admin/client')) ? 'active' : '' }}">
                    <div class="img_wrapper"><img src="{{ asset('/images/client.svg') }}" alt="client"width="23" height="23"></div>
                    <span>Clients</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.candidates.index') }}" class="{{ (request()->is('admin/candidate')) ? 'active' : '' }}" >
                    <div class="img_wrapper"><img src="{{ asset('/images/users.svg') }}" alt="users" width="23" height="23"></div>
                    <span>Candidates</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.jobs.index') }}" class="{{ (request()->is('admin/jobs')) ? 'active' : ''  }}" >
                    <div class="img_wrapper"><img src="{{ asset('/images/todo-white.svg') }}" alt="users" width="23" height="23"></div>
                    <span>Contracts</span>
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