<aside class="ft-sidebar">
    <a class="ft-brand" href="{{ route('dashboard') }}"><span class="ft-brand-mark">F</span><span class="ft-brand-name">FinTrack</span></a>
    <nav class="ft-nav">
        <div class="ft-nav-label">Workspace</div>
        <a class="ft-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="ft-nav-icon">⌂</span><span>Dashboard</span></a>
        <a class="ft-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}"><span class="ft-nav-icon">⚙</span><span>Settings</span></a>
    </nav>
    <div class="ft-user"><span class="ft-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span><div class="ft-user-copy"><div class="ft-user-name">{{ Auth::user()->name ?? 'User' }}</div><div class="ft-user-role">Owner</div></div></div>
</aside>
