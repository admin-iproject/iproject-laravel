@extends('layouts.app')

@section('title', 'View Project')
@section('module-name', 'Projects')

{{-- ── Projects sidebar replaces the left sidebar body completely ───── --}}
@section('sidebar-content')

    {{-- MY PROJECTS --}}
    <div class="px-3 pt-4 pb-2">
        <p class="sidebar-section-title">MY PROJECTS</p>

        <a href="{{ route('projects.index') }}"
           class="sidebar-menu-item {{ request()->routeIs('projects.index') && !request('owner') ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span class="sidebar-menu-item-text">All Projects</span>
        </a>

        <a href="{{ route('projects.index', ['owner' => Auth::id()]) }}"
           class="sidebar-menu-item {{ request('owner') == Auth::id() ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="sidebar-menu-item-text">My Projects</span>
        </a>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Shared With Me</span>
        </a>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <span class="sidebar-menu-item-text">Favourites</span>
        </a>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    {{-- TASKS --}}
    <div class="px-3 py-2">
        <p class="sidebar-section-title">TASKS</p>

        <a href="{{ route('tasks.index') }}" class="sidebar-menu-item {{ request()->routeIs('tasks.index') && !request('owner_id') ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span class="sidebar-menu-item-text">All Tasks</span>
        </a>

        <a href="{{ route('tasks.index', ['owner_id' => Auth::id()]) }}"
           class="sidebar-menu-item {{ request('owner_id') == Auth::id() ? 'active' : '' }}">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="sidebar-menu-item-text">My Tasks</span>
        </a>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="sidebar-menu-item-text">Overdue</span>
        </a>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="sidebar-menu-item-text">Due This Week</span>
        </a>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    {{-- VIEWS --}}
    <div class="px-3 py-2">
        <p class="sidebar-section-title">VIEWS</p>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <span class="sidebar-menu-item-text">List View</span>
        </a>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
            </svg>
            <span class="sidebar-menu-item-text">Kanban Board</span>
        </a>

        <a href="#" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="sidebar-menu-item-text">Gantt Chart</span>
        </a>
    </div>

    <div class="border-t border-gray-100 mx-3"></div>

    {{-- ACTIONS --}}
    <div class="px-3 py-3">
        <a href="{{ route('projects.create') }}" class="sidebar-menu-item">
            <svg class="sidebar-menu-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="sidebar-menu-item-text">New Project</span>
        </a>
    </div>

@endsection

{{-- ── PAGE HEADER ─────────────────────────────────────────── --}}
@section('header')
    @include('projects.partials._header')
@endsection

{{-- ── MAIN CONTENT ────────────────────────────────────────── --}}
@section('content')
    @include('projects.partials._project-header-card')
    @include('projects.partials._task-list')

    {{-- Modals --}}
    @include('projects.partials._task-create-modal')
    @include('projects.partials._task-edit-modal')
@endsection

{{-- ── RIGHT EDGE TABS ─────────────────────────────────────── --}}
@section('right-tabs')
    @include('projects.partials._right-tabs')
@endsection

{{-- ── SLIDEOUT PANELS ─────────────────────────────────────── --}}
@section('slideout-panels')
    @include('projects.partials._slideout-panels')
    @include('projects.partials._slideout-team')
    @include('projects.partials._slideout-settings')
@endsection

{{-- ── SCRIPTS ─────────────────────────────────────────────── --}}
@push('scripts')
    @include('projects.partials._scripts')
@endpush
