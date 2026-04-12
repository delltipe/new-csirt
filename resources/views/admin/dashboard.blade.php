@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    <ul class="nav nav-tabs mb-4" id="adminTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="news-tab" data-bs-toggle="tab" data-bs-target="#news" type="button" role="tab">News</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" role="tab">Events</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="infographics-tab" data-bs-toggle="tab" data-bs-target="#infographics" type="button" role="tab">Infographics</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="warnings-tab" data-bs-toggle="tab" data-bs-target="#warnings" type="button" role="tab">Warnings</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="laws-tab" data-bs-toggle="tab" data-bs-target="#laws" type="button" role="tab">Laws</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="guides-tab" data-bs-toggle="tab" data-bs-target="#guides" type="button" role="tab">Guides</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button" role="tab">Events</button>
        </li>
    </ul>
    <div class="tab-content" id="adminTabContent">
        <div class="tab-pane fade show active" id="news" role="tabpanel">
            @include('admin.partials.news')
        </div>
        <div class="tab-pane fade" id="events" role="tabpanel">
            @include('admin.partials.events')
        </div>
        <div class="tab-pane fade" id="infographics" role="tabpanel">
            @include('admin.partials.infographics')
        </div>
        <div class="tab-pane fade" id="warnings" role="tabpanel">
            @include('admin.partials.warnings')
        </div>
        <div class="tab-pane fade" id="laws" role="tabpanel">
            @include('admin.partials.laws')
        </div>
        <div class="tab-pane fade" id="guides" role="tabpanel">
            @include('admin.partials.guides')
        </div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Logout</button>
    </form>
</div>
@endsection
