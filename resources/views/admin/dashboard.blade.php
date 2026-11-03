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
    border-bottom: 3px solid var(--border);
    padding-bottom: 20px;
    margin-bottom: 40px;
}

.admin-title {
    font-family: var(--font-display);
    font-size: 42px;
    font-weight: 800;
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

.tab-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    margin-left: 8px;
    background: var(--alert);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 800;
    border-radius: 10px;
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
    background: var(--alert-dark);
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
    transition: background var(--ease), color var(--ease);
    margin-top: 40px;
}

.btn-logout:hover {
    background: var(--alert);
    color: var(--white);
}

.alert-success {
    background: var(--navy-tint);
    border: 1px solid var(--navy);
    color: var(--navy-dim);
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
        <button class="admin-tab active" onclick="switchTab('news')" data-tab="news">News</button>
        <button class="admin-tab" onclick="switchTab('events')" data-tab="events">Events</button>
        <button class="admin-tab" onclick="switchTab('infographics')" data-tab="infographics">Infographics</button>
        <button class="admin-tab" onclick="switchTab('warnings')" data-tab="warnings">Warnings</button>
        <button class="admin-tab" onclick="switchTab('laws')" data-tab="laws">Laws</button>
        <button class="admin-tab" onclick="switchTab('guides')" data-tab="guides">Guides</button>
        <button class="admin-tab" onclick="switchTab('insiden')" data-tab="insiden">
            Insiden
            @if($pendingIncidents > 0)
                <span class="tab-badge">{{ $pendingIncidents }}</span>
            @endif
        </button>
        <button class="admin-tab" onclick="switchTab('hero')" data-tab="hero">Hero</button>
        <button class="admin-tab" onclick="switchTab('kontak')" data-tab="kontak">
            Kontak
            @if($pendingContacts > 0)
                <span class="tab-badge">{{ $pendingContacts }}</span>
            @endif
        </button>
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

        <!-- INSIDEN TAB -->
        <div id="insiden-tab" class="tab-pane">
            @include('admin.partials.incidents')
        </div>

        <!-- HERO TAB -->
        <div id="hero-tab" class="tab-pane">
            @include('admin.partials.hero')
        </div>

        <!-- KONTAK TAB -->
        <div id="kontak-tab" class="tab-pane">
            @include('admin.partials.contact')
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
const adminTabs = ['news', 'events', 'infographics', 'warnings', 'laws', 'guides', 'insiden', 'hero', 'kontak'];

function activateTab(tabName) {
    if (!adminTabs.includes(tabName)) tabName = 'news';
    document.querySelectorAll('.admin-tab').forEach(tab => {
        tab.classList.toggle('active', tab.dataset.tab === tabName);
    });
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.toggle('active', pane.id === tabName + '-tab');
    });
}

function switchTab(tabName) {
    activateTab(tabName);
    history.replaceState(null, '', '#' + tabName);
}

document.addEventListener('DOMContentLoaded', function () {
    activateTab(window.location.hash.slice(1));
});
window.addEventListener('hashchange', function () {
    activateTab(window.location.hash.slice(1));
});
</script>
@endsection