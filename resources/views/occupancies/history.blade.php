@extends('layouts.app')

@section('title', 'Occupancy History')
@section('page-title', 'Occupancy History')
@section('page-subtitle', 'Checked-out Rooms')

@section('content')

    <div class="row mb-3">
        <div class="col-md-3">
            <div id="roomFilter"></div>
        </div>
        <div class="col-md-2">
            <div id="typeFilter"></div>
        </div>
        <div class="col-md-2">
            <div id="startDate"></div>
        </div>
        <div class="col-md-2">
            <div id="endDate"></div>
        </div>
        <div class="col-md-2">
            <div id="filterBtn"></div>
        </div>
    </div>

    <div id="historyGrid"></div>

@endsection

@push('scripts')
    <script>
        let grid;

        $(function() {

            // ROOM FILTER
            $("#roomFilter").dxSelectBox({
                placeholder: "Room",
                dataSource: "/api/rooms",
                displayExpr: "room_code",
                valueExpr: "id",
                showClearButton: true
            });

            // TYPE FILTER
            $("#typeFilter").dxSelectBox({
                placeholder: "Type",
                dataSource: [{
                        id: "employee",
                        name: "Employee"
                    },
                    {
                        id: "guest",
                        name: "Guest"
                    }
                ],
                displayExpr: "name",
                valueExpr: "id",
                showClearButton: true
            });

            // DATE FILTER
            $("#startDate").dxDateBox({
                type: "date",
                placeholder: "Start Date"
            });

            $("#endDate").dxDateBox({
                type: "date",
                placeholder: "End Date"
            });

            // GRID
            grid = $("#historyGrid").dxDataGrid({
                dataSource: loadData,
                showBorders: true,
                columnAutoWidth: true,
                paging: {
                    pageSize: 10
                },

                columns: [{
                        caption: "Room",
                        dataField: "room.room_code"
                    },
                    {
                        caption: "Type",
                        dataField: "type"
                    },
                    {
                        caption: "Occupant",
                        cellTemplate(container, options) {
                            const name = options.data.type === 'employee' ?
                                options.data.employee.name :
                                options.data.guest_name;
                            $("<span>").text(name).appendTo(container);
                        }
                    },
                    {
                        caption: "Check In",
                        dataField: "check_in_at",
                        dataType: "date"
                    },
                    {
                        caption: "Check Out",
                        dataField: "check_out_at",
                        dataType: "date"
                    }
                ]
            }).dxDataGrid("instance");

            // FILTER BUTTON
            $("#filterBtn").dxButton({
                text: "Apply Filter",
                type: "default",
                onClick() {
                    grid.refresh();
                }
            });

        });

        // LOAD DATA WITH PARAM
        function loadData() {
            return $.getJSON('/api/room-occupancies/history', {
                room_id: $("#roomFilter").dxSelectBox("instance").option("value"),
                type: $("#typeFilter").dxSelectBox("instance").option("value"),
                start_date: $("#startDate").dxDateBox("instance").option("value"),
                end_date: $("#endDate").dxDateBox("instance").option("value"),
            });
        }
    </script>
@endpush
