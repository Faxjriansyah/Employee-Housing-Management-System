@extends('layouts.app')

@section('title', 'Room Statistics')
@section('page-title', 'Room Occupancy Statistics')
@section('page-subtitle', 'Usage per Room')

@section('content')
    <div id="roomChart"></div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $.getJSON('/api/stats/room-occupancy', function(data) {

                $("#roomChart").dxChart({
                    dataSource: data,
                    palette: "Soft",
                    title: "Room Occupancy Statistics",

                    commonSeriesSettings: {
                        argumentField: "room_code",
                        type: "bar"
                    },

                    series: [{
                            valueField: "total_usage",
                            name: "Total Usage"
                        },
                        {
                            valueField: "active",
                            name: "Currently Occupied"
                        }
                    ],

                    legend: {
                        verticalAlignment: "bottom",
                        horizontalAlignment: "center"
                    },

                    tooltip: {
                        enabled: true
                    }
                });

            });
        });
    </script>
@endpush
