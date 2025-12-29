@extends('layouts.admin')

@section('content')
    <div class="mb-4" style="margin-top: -80px;">
        <h3 class="fw-bold text-dark">Analytiques</h3>
        <p class="text-muted">Vue d'ensemble des performances de la compagnie.</p>
    </div>

    <div class="row g-4">
        <!-- Chart 1: Users Growth -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Nouveaux utilisateurs par mois</h5>
                    {!! $users_chart->renderHtml() !!}
                </div>
            </div>
        </div>

        <!-- Chart 2: Policies by Formula -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Répartition par formule</h5>
                    {!! $polices_chart->renderHtml() !!}
                </div>
            </div>
        </div>

        <!-- Chart 3: Claims Status -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Statuts des sinistres</h5>
                    {!! $sinistres_chart->renderHtml() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js Service -->
    {!! $users_chart->renderChartJsLibrary() !!}

    <!-- Render Charts -->
    {!! $users_chart->renderJs() !!}
    {!! $polices_chart->renderJs() !!}
    {!! $sinistres_chart->renderJs() !!}
@endsection