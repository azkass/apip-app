@extends('layouts.app')
@section('content')
    @component('components.dashboard', [
        'roleLabels' => $roleLabels ?? [],
        'roleCounts' => $roleCounts ?? [], 
        'totalUsers' => $totalUsers ?? 0,
        'statusLabels' => $statusLabels ?? [],
        'statusCounts' => $statusCounts ?? [],
        'totalProcedures' => $totalProcedures ?? 0,
        'currentYear' => $currentYear ?? date('Y'),
        'userRoles' => $userRoles ?? [],
        'procedureStatuses' => $procedureStatuses ?? []
    ])
    @endcomponent
@endsection
