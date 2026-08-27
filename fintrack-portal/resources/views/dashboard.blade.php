<x-app-layout>
<div class="ft-page">
    <div class="ft-page-head">
        <div>
            <h1 class="ft-page-title">Dashboard</h1>
            <p class="ft-page-subtitle">Welcome back, John 👋</p>
        </div>
        <div>
            <button class="ft-button" style="background:#fff; color:#344054; border:1px solid #dfe4ec; padding:8px 14px; font-size:12px; font-weight:600; display:flex; align-items:center; gap:8px; box-shadow:none;">
                <i data-lucide="calendar" style="width:14px; height:14px;"></i>
                <span>This Month</span>
                <i data-lucide="chevron-down" style="width:12px; height:12px;"></i>
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="ft-stat-grid">
        <!-- Total Income -->
        <div class="ft-card ft-stat">
            <div class="ft-stat-top">
                <span style="color:#667085; font-size:11px; font-weight:600;">Total Income</span>
                <span class="ft-stat-icon" style="color:#039855; background:#ecfdf3;">
                    <i data-lucide="arrow-down-left" style="width:16px; height:16px;"></i>
                </span>
            </div>
            <div class="ft-stat-value">{{ $summary['total_income'] }}</div>
            <div class="ft-stat-note" style="color:#12b76a; display:flex; align-items:center; gap:4px; margin-top:6px;">
                <i data-lucide="trending-up" style="width:12px; height:12px;"></i>
                <span>20% from last month</span>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="ft-card ft-stat">
            <div class="ft-stat-top">
                <span style="color:#667085; font-size:11px; font-weight:600;">Total Expenses</span>
                <span class="ft-stat-icon" style="color:#d92d20; background:#fef3f2;">
                    <i data-lucide="arrow-up-right" style="width:16px; height:16px;"></i>
                </span>
            </div>
            <div class="ft-stat-value">{{$summary['total_expenses']}}</div>
            <div class="ft-stat-note" style="color:#f04438; display:flex; align-items:center; gap:4px; margin-top:6px;">
                <i data-lucide="trending-down" style="width:12px; height:12px;"></i>
                <span>8% from last month</span>
            </div>
        </div>

        <!-- Balance -->
        <div class="ft-card ft-stat">
            <div class="ft-stat-top">
                <span style="color:#667085; font-size:11px; font-weight:600;">Balance</span>
                <span class="ft-stat-icon" style="color:#2f56eb; background:#eff3ff;">
                    <i data-lucide="wallet" style="width:16px; height:16px;"></i>
                </span>
            </div>
            <div class="ft-stat-value">{{$summary}}</div>
            <div class="ft-stat-note" style="color:#12b76a; display:flex; align-items:center; gap:4px; margin-top:6px;">
                <i data-lucide="trending-up" style="width:12px; height:12px;"></i>
                <span>12% from last month</span>
            </div>
        </div>

        <!-- Transactions Count -->
        <div class="ft-card ft-stat">
            <div class="ft-stat-top">
                <span style="color:#667085; font-size:11px; font-weight:600;">Transactions</span>
                <span class="ft-stat-icon" style="color:#7c3aed; background:#f3e8ff;">
                    <i data-lucide="arrow-left-right" style="width:16px; height:16px;"></i>
                </span>
            </div>
            <div class="ft-stat-value">25</div>
            <div class="ft-stat-note" style="color:#12b76a; display:flex; align-items:center; gap:4px; margin-top:6px;">
                <i data-lucide="trending-up" style="width:12px; height:12px;"></i>
                <span>5 from last month</span>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="ft-layout-grid">
        <!-- Recent Transactions Panel -->
        <div class="ft-card ft-panel">
            <div class="ft-panel-head" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:18px;">
                <div class="ft-panel-title" style="font-size:14px; font-weight:700;">Recent Transactions</div>
                <a href="{{ route('transaction.index') }}" class="ft-link" style="text-decoration:none;">View all</a>
            </div>
            <table class="ft-table">
                <thead>
                    <tr>
                        <th style="font-size:10px; font-weight:700; color:#98a2b3; text-transform:uppercase; border-bottom:1px solid #f2f4f7; padding-bottom:8px;">Description</th>
                        <th style="font-size:10px; font-weight:700; color:#98a2b3; text-transform:uppercase; border-bottom:1px solid #f2f4f7; padding-bottom:8px;">Type</th>
                        <th style="font-size:10px; font-weight:700; color:#98a2b3; text-transform:uppercase; border-bottom:1px solid #f2f4f7; padding-bottom:8px;">Amount</th>
                        <th style="font-size:10px; font-weight:700; color:#98a2b3; text-transform:uppercase; border-bottom:1px solid #f2f4f7; padding-bottom:8px;">Date</th>
                        <th style="font-size:10px; font-weight:700; color:#98a2b3; text-transform:uppercase; border-bottom:1px solid #f2f4f7; padding-bottom:8px;">Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Salary Payment</td>
                        <td><span class="ft-badge income">Income</span></td>
                        <td>₦100,000</td>
                        <td>Jun 23, 2026</td>
                        <td>John Doe</td>
                    </tr>
                    <tr>
                        <td>Freelance Work</td>
                        <td><span class="ft-badge income">Income</span></td>
                        <td>₦50,000</td>
                        <td>Jun 21, 2026</td>
                        <td>Mary Jane</td>
                    </tr>
                    <tr>
                        <td>Transport</td>
                        <td><span class="ft-badge expense">Expense</span></td>
                        <td>₦10,000</td>
                        <td>Jun 23, 2026</td>
                        <td>John Doe</td>
                    </tr>
                    <tr>
                        <td>Internet Subscription</td>
                        <td><span class="ft-badge expense">Expense</span></td>
                        <td>₦6,000</td>
                        <td>Jun 22, 2026</td>
                        <td>Mary Jane</td>
                    </tr>
                    <tr>
                        <td>Office Supplies</td>
                        <td><span class="ft-badge expense">Expense</span></td>
                        <td>₦25,000</td>
                        <td>Jun 20, 2026</td>
                        <td>David Smith</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Sidebar Actions & Charts Panel -->
        <div style="display:flex; flex-direction:column; gap:18px;">
            <!-- Quick Actions Card -->
            <div class="ft-card ft-panel">
                <div class="ft-panel-head" style="margin-bottom:15px;">
                    <div class="ft-panel-title" style="font-size:14px; font-weight:700;">Quick Actions</div>
                </div>
                <div class="ft-actions">
                    <a href="{{ route('income.create') }}" class="ft-action income" style="display:flex; align-items:center; justify-content:center; gap:6px; text-decoration:none;">
                        <i data-lucide="plus" style="width:14px; height:14px;"></i>
                        <span>Add Income</span>
                    </a>
                    <a href="{{ route('expense.create') }}" class="ft-action expense" style="display:flex; align-items:center; justify-content:center; gap:6px; text-decoration:none;">
                        <i data-lucide="plus" style="width:14px; height:14px;"></i>
                        <span>Add Expense</span>
                    </a>
                </div>
            </div>

            <!-- Spending Overview Placeholder Card -->
            <div class="ft-card ft-panel" style="min-height: 250px; display:flex; flex-direction:column;">
                <div class="ft-panel-head" style="margin-bottom:15px;">
                    <div class="ft-panel-title" style="font-size:14px; font-weight:700;">Spending Overview</div>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; border: 2px dashed #e9edf4; border-radius:8px; background:#fafbfc; margin:10px 0;">
                    <i data-lucide="pie-chart" style="width:32px; height:32px; color:#98a2b3; margin-bottom:8px;"></i>
                    <span style="font-size:12px; font-weight:600; color:#667085;">Chart Placeholder</span>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
