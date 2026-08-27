<aside class="ft-sidebar">
    <a class="ft-brand" href="{{ route('dashboard') }}"><span class="ft-brand-mark"><i data-lucide="activity" style="width:16px;height:16px;"></i></span><span class="ft-brand-name">FinTrack</span></a>
    <nav class="ft-nav">
        <div class="ft-nav-label">Workspace</div>
        <a class="ft-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i data-lucide="layout-dashboard" class="ft-nav-icon" style="width:16px;height:16px;"></i>
            <span>Dashboard</span>
        </a>
        <a class="ft-nav-link {{ request()->routeIs('income.*') ? 'active' : '' }}" href="{{ route('income.index') }}">
            <i data-lucide="arrow-down-left" class="ft-nav-icon" style="width:16px;height:16px;"></i>
            <span>Incomes</span>
        </a>
        <a class="ft-nav-link {{ request()->routeIs('expense.*') ? 'active' : '' }}" href="{{ route('expense.index') }}">
            <i data-lucide="arrow-up-right" class="ft-nav-icon" style="width:16px;height:16px;"></i>
            <span>Expenses</span>
        </a>
        <a class="ft-nav-link {{ request()->routeIs('transaction.*') ? 'active' : '' }}" href="{{ route('transaction.index') }}">
            <i data-lucide="arrow-left-right" class="ft-nav-icon" style="width:16px;height:16px;"></i>
            <span>Transactions</span>
        </a>
    </nav>
    <div class="ft-user"><span class="ft-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span><div class="ft-user-copy"><div class="ft-user-name">{{ Auth::user()->name ?? 'User' }}</div><div class="ft-user-role">Owner</div></div></div>
</aside>