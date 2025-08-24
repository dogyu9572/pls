<div class="sidebar">
    <div class="sidebar-header">
        <h3>{{ \App\Models\Setting::getValue('site_title', '관리자') }}</h3>
    </div>
    <ul class="sidebar-menu">
        @php
            $currentPath = request()->path();
            $currentFullUrl = request()->fullUrl();
        @endphp
        @foreach(\App\Models\AdminMenu::getMainMenus() as $menu)
            @php
                // 현재 메뉴가 활성화되어야 하는지 확인
                $isActive = false;
                $hasActiveChild = false;

                // URL이 있는 메뉴인 경우 직접 확인
                if($menu->url) {
                    $menuPath = trim($menu->url, '/');
                    // URL이 비어있지 않은 경우에만 비교
                    if(!empty($menuPath)) {
                        // 정확한 경로 매칭만 사용 (하위 경로는 제외)
                        $isActive = $currentPath === $menuPath;
                    }
                }

                // 서브메뉴가 있는 경우 (메뉴 URL 여부와 관계없이)
                if($menu->children && $menu->children->count() > 0) {
                    // 자식 메뉴 중 현재 경로와 일치하는 것이 있는지 확인
                    foreach($menu->children as $child) {
                        if($child->url && !empty(trim($child->url, '/'))) {
                            $childPath = trim($child->url, '/');
                            // 정확한 경로 매칭 또는 해당 경로로 시작하는 경우만 활성화
                            if($currentPath === $childPath || str_starts_with($currentPath, $childPath.'/')) {
                                $hasActiveChild = true;
                                break;
                            }
                        }
                    }
                }

                // 자식 메뉴가 활성화되어 있다면 부모도 활성화
                if($hasActiveChild) {
                    $isActive = true;
                }
            @endphp
            <li class="{{ $isActive ? 'active' : '' }}">
                @if($menu->url)
                    <a href="{{ is_string($menu->url) ? url($menu->url) : $menu->url }}">
                        @if($menu->icon)
                            <i class="fa {{ $menu->icon }}"></i>
                        @endif
                        <span>{{ $menu->name }}</span>
                    </a>
                @else
                    <a href="#" class="has-submenu {{ $isActive ? 'open' : '' }}">
                        @if($menu->icon)
                            <i class="fa {{ $menu->icon }}"></i>
                        @endif
                        <span>{{ $menu->name }}</span>
                        <i class="fa fa-angle-down submenu-icon"></i>
                    </a>
                    @if($menu->children && $menu->children->count() > 0)
                        <ul class="sidebar-submenu">
                            @foreach($menu->children as $child)
                                @if($child->is_active)
                                    @php
                                        $childUrl = is_string($child->url) ? $child->url : '';
                                        $childPath = trim($childUrl, '/');
                                        $isChildActive = false;

                                        // URL이 비어있지 않은 경우에만 활성화 여부 확인
                                        if(!empty($childPath)) {
                                            $isChildActive = $currentPath === $childPath || str_starts_with($currentPath, $childPath.'/');
                                        } else {
                                            // URL이 비어 있는 경우 현재 접속한 URL과 같은지 확인
                                            $fullChildUrl = is_string($child->url) ? url($child->url) : '';
                                            $isChildActive = !empty($fullChildUrl) && $currentFullUrl === $fullChildUrl;
                                        }
                                    @endphp
                                    <li class="{{ $isChildActive ? 'active' : '' }}">
                                        <a href="{{ is_string($child->url) ? url($child->url) : $child->url }}">
                                            @if($child->icon)
                                                <i class="fa {{ $child->icon }}"></i>
                                            @endif
                                            <span>{{ $child->name }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                @endif
            </li>
        @endforeach
    </ul>
</div>
