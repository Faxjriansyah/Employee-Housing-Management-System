@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h4 class="mb-4">Dashboard</h4>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Karyawan</h6>
                    <h3 id="totalEmployee">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Karyawan Aktif</h6>
                    <h3 id="activeEmployee">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Kamar Terisi</h6>
                    <h3 id="occupiedRoom">0</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Tamu Minggu Ini</h6>
                    <h3 id="weeklyGuest">0</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Karyawan Aktif vs Nonaktif</h6>
                    <div id="employeeChart" style="height:300px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Status Kamar</h6>
                    <div id="roomChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            $.getJSON('/api/dashboard/stats', function(res) {

                // === SUMMARY CARDS ===
                $('#totalEmployee').text(res.totalEmployee);
                $('#activeEmployee').text(res.activeEmployee);
                $('#occupiedRoom').text(res.occupiedRoom);
                $('#weeklyGuest').text(res.weeklyGuest);

                // === EMPLOYEE CHART ===
                $("#employeeChart").dxPieChart({
                    dataSource: res.employeeChart,
                    series: {
                        argumentField: "status",
                        valueField: "total",
                        label: {
                            visible: true,
                            connector: {
                                visible: true
                            },
                            customizeText(arg) {
                                return arg.argumentText.toUpperCase() + " : " + arg.valueText;
                            }
                        }
                    }
                });

                // === ROOM CHART ===
                $("#roomChart").dxPieChart({
                    dataSource: res.roomChart,
                    series: {
                        argumentField: "status",
                        valueField: "total",
                        label: {
                            visible: true,
                            connector: {
                                visible: true
                            },
                            customizeText(arg) {
                                return arg.argumentText.toUpperCase() + " : " + arg.valueText;
                            }
                        }
                    }
                });

            });

        });
    </script>
@endpush
