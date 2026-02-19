@extends('layouts.app')

@section('title', 'View Project')
@section('module-name', 'Projects')
@section('sidebar-section-title', 'PROJECT MENU')

{{-- ── LEFT SIDEBAR — projects/show module menu ───────────── --}}
@section('sidebar-menu')
    @include('projects.partials._sidebar')
@endsection

{{-- ── PAGE HEADER ─────────────────────────────────────────── --}}
@section('header')
    @include('projects.partials._header')
@endsection

{{-- ── MAIN CONTENT ────────────────────────────────────────── --}}
@section('content')
    @include('projects.partials._project-header-card')
    @include('projects.partials._task-list')

    {{-- ── MODALS (must be inside a yielded section to render) ── --}}
    @include('projects.partials._task-create-modal')
    @include('projects.partials._task-edit-modal')
@endsection

{{-- ── RIGHT EDGE TABS ─────────────────────────────────────── --}}
{{-- NOTE: right-tabs are rendered via layouts/partials/right-slideouts.blade.php --}}
{{-- If that partial has @yield('right-tabs'), keep @section. Otherwise move content there. --}}
@section('right-tabs')
    @include('projects.partials._right-tabs')
@endsection

{{-- ── SLIDEOUT PANELS ─────────────────────────────────────── --}}
@section('slideout-panels')
    @include('projects.partials._slideout-panels')
    @include('projects.partials._slideout-team')
    @include('projects.partials._slideout-settings')
@endsection

{{-- ── STYLES + SCRIPTS ─────────────────────────────────────── --}}
@push('scripts')
    @include('projects.partials._scripts')
@endpush
