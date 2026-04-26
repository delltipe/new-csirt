@extends('layouts.app')

@section('content')
<style>
/* Admin Dashboard Custom Styles - NYC.gov inspired */
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 60px 28px;
}

.admin-header {
    border-bottom: 3px solid var(--ink);
    padding-bottom: 20px;
    margin-bottom: 40px;
}

.admin-title {
    font-family: var(--font-display);
    font-size: 42px;
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.admin-tabs {
    display: flex;
    gap: 0;
    border-bottom: 2px solid var(--border);
    margin-bottom: 40px;
    overflow-x: auto;
    flex-wrap: nowrap;
}

.admin-tab {
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--mid);
    background: transparent;
    border: none;
    padding: 16px 24px;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all var(--ease);
    white-space: nowrap;
}

.admin-tab:hover {
    color: var(--navy);
    background: var(--navy-tint);
}

.admin-tab.active {
    color: var(--navy);
    border-bottom-color: var(--navy);
    background: var(--navy-tint);
}

.tab-content-wrapper {
    background: var(--white);
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

.section-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-title-small {
    font-family: var(--font-display);
    font-size: 24px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 12px 24px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}

.btn-add:hover {
    background: var(--navy-dim);
    color: var(--white);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid var(--border);
    background: var(--white);
}

.data-table thead {
    background: var(--mist);
}

.data-table th {
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ink);
    padding: 16px 20px;
    text-align: left;
    border-bottom: 2px solid var(--border);
}

.data-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    color: var(--ink);
    font-size: 14px;
}

.data-table tbody tr:hover {
    background: var(--navy-tint);
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
    text-decoration: none;
}

.btn-edit:hover {
    background: var(--navy-mid);
    color: var(--white);
}

.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--alert);
    color: var(--white);
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}

.btn-delete:hover {
    background: #991b1b;
}

.btn-logout {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: var(--alert);
    border: 2px solid var(--alert);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 12px 28px;
    cursor: pointer;
    transition: all var(--ease);
    margin-top: 40px;
}

.btn-logout:hover {
    background: var(--alert);
    color: var(--white);
}

.alert-success {
    background: #ECFDF5;
    border-left: 4px solid #10B981;
    color: #065F46;
    padding: 16px 20px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--mid);
}

.empty-state p {
    font-size: 16px;
    margin: 0;
}
</style>

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">Admin Dashboard</h1>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tabs Navigation -->
    <div class="admin-tabs">
        <button class="admin-tab active" onclick="switchTab('news')">News</button>
        <button class="admin-tab" onclick="switchTab('events')">Events</button>
        <button class="admin-tab" onclick="switchTab('infographics')">Infographics</button>
        <button class="admin-tab" onclick="switchTab('warnings')">Warnings</button>
        <button class="admin-tab" onclick="switchTab('laws')">Laws</button>
        <button class="admin-tab" onclick="switchTab('guides')">Guides</button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content-wrapper">
        <!-- NEWS TAB -->
        <div id="news-tab" class="tab-pane active">
            @include('admin.partials.news')
        </div>

        <!-- EVENTS TAB -->
        <div id="events-tab" class="tab-pane">
            @include('admin.partials.events')
        </div>

        <!-- INFOGRAPHICS TAB -->
        <div id="infographics-tab" class="tab-pane">
            @include('admin.partials.infographics')
        </div>

        <!-- WARNINGS TAB -->
        <div id="warnings-tab" class="tab-pane">
            @include('admin.partials.warnings')
        </div>

        <!-- LAWS TAB -->
        <div id="laws-tab" class="tab-pane">
            @include('admin.partials.laws')
        </div>

        <!-- GUIDES TAB -->
        <div id="guides-tab" class="tab-pane">
            @include('admin.partials.guides')
        </div>
    </div>

    <!-- Logout -->
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
            <span>Logout</span>
        </button>
    </form>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab panes
    const panes = document.querySelectorAll('.tab-pane');
    panes.forEach(pane => pane.classList.remove('active'));
    
    // Remove active from all tabs
    const tabs = document.querySelectorAll('.admin-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Show selected pane
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Activate clicked tab
    event.target.classList.add('active');
}
</script>
@endsection