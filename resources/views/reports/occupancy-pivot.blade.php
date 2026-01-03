@extends('layouts.app')

@section('title', 'Occupancy Report')
@section('page-title', 'Occupancy Report')
@section('page-subtitle', 'Room Usage Summary')

@section('content')

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-info">
                Pivot report showing room usage summary (Employee vs Guest).
                You can expand, collapse, and export this report.
            </div>
        </div>
    </div>

    <div id="occupancyPivot"></div>

@endsection

@push('scripts')
    <script>
        $(function() {

            $("#occupancyPivot").dxPivotGrid({
                showBorders: true,
                height: 600,

                dataSource: {
                    store: {
                        load: () => $.getJSON('/api/stats/room-occupancy')
                    },
                    fields: [{
                            dataField: "room_code",
                            area: "row",
                            caption: "Room"
                        },
                        {
                            dataField: "type",
                            area: "column",
                            caption: "Type"
                        },
                        {
                            dataField: "total_usage",
                            area: "data",
                            summaryType: "sum",
                            caption: "Total Usage"
                        }
                    ]
                }
            });


        });
    </script>
@endpush
