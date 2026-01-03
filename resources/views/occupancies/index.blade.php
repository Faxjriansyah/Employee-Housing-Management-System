@extends('layouts.app')

@section('title', 'Room Occupancy')
@section('page-title', 'Room Occupancy')
@section('page-subtitle', 'Currently Occupied Rooms')

@section('content')
    <div id="occupancyGrid"></div>
@endsection

@push('scripts')
    <script>
        let grid;

        $(function() {

            grid = $("#occupancyGrid").dxDataGrid({
                dataSource: {
                    load: () => $.getJSON('/api/room-occupancies')
                },
                showBorders: true,
                columnAutoWidth: true,
                paging: {
                    pageSize: 10
                },
                searchPanel: {
                    visible: true
                },

                columns: [{
                        caption: "Room",
                        dataField: "room.room_code"
                    },
                    {
                        caption: "Type",
                        dataField: "type",
                        cellTemplate(c, o) {
                            $("<span>").text(o.value.toUpperCase()).appendTo(c);
                        }
                    },
                    {
                        caption: "Occupant",
                        cellTemplate(c, o) {
                            let text = o.data.type === 'employee' ?
                                o.data.employee?.name :
                                o.data.guest_name;
                            $("<span>").text(text ?? '-').appendTo(c);
                        }
                    },
                    {
                        caption: "Check In",
                        dataField: "created_at",
                        dataType: "datetime"
                    },
                    {
                        caption: "Action",
                        cellTemplate(c, o) {
                            $("<button>")
                                .addClass("btn btn-sm btn-danger")
                                .text("Checkout")
                                .on("click", () => checkout(o.data.id))
                                .appendTo(c);
                        }
                    }
                ]
            }).dxDataGrid("instance");

        });

        function checkout(id) {
            const reason = prompt("Checkout reason? (optional)");

            $.ajax({
                    url: `/api/room-occupancies/${id}/checkout`,
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    data: {
                        checkout_reason: reason
                    }
                })
                .done(() => {
                    DevExpress.ui.notify("Checkout success", "success", 1500);
                    grid.refresh();
                });
        }
    </script>
@endpush
