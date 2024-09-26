<!--左侧导航-->
<aside class="lyear-layout-sidebar">

    <!-- logo -->
    <div id="logo" class="sidebar-header">
        <a href="{{ route('admin.main') }}"><img src="{{ asset('/images/logo-sidebar.png') }}" title="Logo" alt="Logo" /></a>
    </div>
    <div class="lyear-layout-sidebar-scroll">

        <nav class="sidebar-main">
            <ul class="nav nav-drawer">
                <ul class="nav nav-subnav" style="display: block;">
                    <li>
<a class="multitabs" href="http://127.0.0.1:8000/admin/members" title="Member Management">
<i class="mdi mdi-checkbox-multiple-blank-circle-outline"></i>
Member Management
</a>
</li>
                                            <li>
<a class="multitabs" href="http://127.0.0.1:8000/admin/memberbanks" title="Member bank card management">
<i class="mdi mdi-checkbox-multiple-blank-circle-outline"></i>
Member bank card management
</a>
</li>
                                            <li>
<a class="multitabs" href="http://127.0.0.1:8000/admin/gamerecords" title="Member game record management">
<i class="mdi mdi-checkbox-multiple-blank-circle-outline"></i>
Member game record management
</a>
</li>
                </ul>
                @foreach (app(App\Services\MenuService::class)->getPermissionsByGuard() as $permissions)
                
                
                @if($loop->first)
                <li class="nav-item active">
                @else
                <li class="nav-item @if($permissions->children) nav-item-has-subnav @endif">
                @endif

                    @if($permissions->route_name)
                    <a class="multitabs" href="{{ route($permissions->route_name) }}" title="{{ $permissions->getLangName() }}">
                    @else  
                    <a href="javascript:void(0)" title="{{ $permissions->getLangName() }}">
                    @endif
                    
                        <i class="{{ $permissions->icon }}"></i>
                        <span>{{ $permissions->getLangName() }}</span>
                    </a>

                    @if($permissions->children)
                    <ul class="nav nav-subnav">
                        @foreach ($permissions->children as $permission)
                        @if($permission->is_show && $permission->isItemShow())
                        <li>
                            <a class="multitabs" href="{{ $permission->route_name ? route($permission->route_name) : '#' }}" title="{{ $permission->getLangName() }}">
                                <i class="{{ $permission->icon }}"></i>
                                {{ $permission->getLangName() }}
                            </a>
                        </li>
                        @endif
                        @endforeach
                       
                    </ul>
                    @endif
                </li>
                @endforeach

                {{-- 
                <li class="nav-item active">
                    <a class="multitabs" href="{{ route("admin.index.index") }}">
                <i class="mdi mdi-home"></i>
                <span>Backstage Home</span>
                </a>
                </li>

                <li class="nav-item nav-item-has-subnav">
                    <a href="javascript:void(0)">
                        <i class="mdi mdi-format-align-justify"></i>
                        <span>Administrator Management</span>
                    </a>
                    <ul class="nav nav-subnav">
                        <li>
                            <a class="multitabs" href="{{ route('admin.users.index') }}">
                                <i class="mdi mdi-home"></i>
                                Administrators List
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item nav-item-has-subnav">
                    <a href="javascript:void(0)"><i class="mdi mdi-format-align-justify"></i>
                        <span>Form</span></a>
                    <ul class="nav nav-subnav">
                        <li> <a class="multitabs" href="lyear_forms_elements.html">Basic Elements</a> </li>
                        <li> <a class="multitabs" href="lyear_forms_radio.html">Radio button</a> </li>
                        <li> <a class="multitabs" href="lyear_forms_checkbox.html">Checkbox</a> </li>
                        <li> <a class="multitabs" href="lyear_forms_switch.html">switch</a> </li>
                    </ul>
                </li>

                <li class="nav-item nav-item-has-subnav">
                    <a href="javascript:void(0)"><i class="mdi mdi-menu"></i> <span>Multi-level menu</span></a>
                    <ul class="nav nav-subnav">
                        <li> <a href="#!">First level menu</a> </li>
                        <li class="nav-item nav-item-has-subnav">
                            <a href="#!">First level menu</a>
                            <ul class="nav nav-subnav">
                                <li> <a href="#!">Secondary menu</a> </li>
                                <li class="nav-item nav-item-has-subnav">
                                    <a href="#!">Secondary menu</a>
                                    <ul class="nav nav-subnav">
                                        <li> <a href="#!">Level 3 menu</a> </li>
                                        <li> <a href="#!">Level 3 menu</a> </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li> <a href="#!">First level menu</a> </li>
                    </ul>
                </li>
                --}}
            </ul>
        </nav>

    </div>

</aside>
<!--End 左侧导航-->
