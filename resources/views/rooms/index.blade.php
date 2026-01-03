@extends('layouts.app')

@section('title', 'Rooms')

@section('content')
<h3 class="mb-4">Rooms</h3>

<!-- Button untuk create room -->
<div class="mb-3">
    <button class="btn btn-success" onclick="openCreatePopup()">
        <i class="fas fa-plus"></i> Add Room
    </button>
</div>

<div id="roomsGrid"></div>
<div id="assignPopup"></div>
<div id="createEditPopup"></div>
@endsection

@push('scripts')
<script>
let roomsGrid;
let assignPopup;
let createEditPopup;
let selectedRoom = null;
let isEditMode = false;

$(function () {

    // ======================
    // ROOMS GRID
    // ======================
    roomsGrid = $("#roomsGrid").dxDataGrid({
        dataSource: {
            load: () => $.getJSON('/api/rooms')
        },
        showBorders: true,
        columnAutoWidth: true,
        paging: { pageSize: 10 },
        columns: [
            { dataField: "room_code", caption: "Room Code" },
            { 
                dataField: "capacity", 
                caption: "Capacity"
            },
            { dataField: "current_occupancy", caption: "Occupied" },
            {
                dataField: "status",
                caption: "Status",
                cellTemplate(container, options) {
                    const color = options.value === 'available' ? 'green' : 'red';
                    $("<b>")
                        .text(options.value.toUpperCase())
                        .css("color", color)
                        .appendTo(container);
                }
            },
            {
                caption: "Actions",
                width: 120,
                cellTemplate(container, options) {
                    const $container = $(container);
                    
                    // Tombol Assign (hanya jika available)
                    if (options.data.status === 'available') {
                        $("<button>")
                            .addClass("btn btn-sm btn-primary me-1")
                            .text("Assign")
                            .on("click", () => openAssignPopup(options.data))
                            .appendTo($container);
                    }
                    
                    // Tombol Edit
                    $("<button>")
                        .addClass("btn btn-sm btn-warning")
                        .text("Edit")
                        .on("click", () => openEditPopup(options.data))
                        .appendTo($container);
                }
            }
        ]
    }).dxDataGrid("instance");

    // ======================
    // ASSIGN POPUP (existing)
    // ======================
    assignPopup = $("#assignPopup").dxPopup({
        title: "Assign Room",
        width: 450,
        height: "auto",
        visible: false,
        closeOnOutsideClick: true,
        showCloseButton: true,
        contentTemplate: () => `
            <div class="mb-3">
                <label>Assign Type</label>
                <div id="assignType"></div>
            </div>

            <div class="mb-3 d-none" id="employeeBox">
                <label>Employee</label>
                <div id="employeeSelect"></div>
            </div>

            <div class="mb-3 d-none" id="guestBox">
                <label>Guest Name</label>
                <input type="text" id="guestName" class="form-control">
            </div>

            <div class="text-end">
                <button class="btn btn-secondary me-2" onclick="assignPopup.hide()">Cancel</button>
                <button class="btn btn-primary" onclick="submitAssign()">Assign</button>
            </div>
        `
    }).dxPopup("instance");

    // ======================
    // CREATE/EDIT POPUP
    // ======================
    createEditPopup = $("#createEditPopup").dxPopup({
        title: "Create New Room",
        width: 400,
        height: "auto",
        visible: false,
        closeOnOutsideClick: true,
        showCloseButton: true,
        contentTemplate: () => `
            <div class="mb-3">
                <label for="roomCode" class="form-label">Room Code *</label>
                <input type="text" 
                       class="form-control" 
                       id="roomCode" 
                       required
                       ${isEditMode ? 'readonly' : ''}>
                <div class="form-text">Unique room identifier</div>
            </div>
            
            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity *</label>
                <select class="form-control" id="capacity" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            
            <div class="text-end">
                <button type="button" class="btn btn-secondary me-2" onclick="createEditPopup.hide()">
                    Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="submitRoomForm()">
                    ${isEditMode ? 'Update' : 'Create'}
                </button>
            </div>
        `
    }).dxPopup("instance");

});

// ======================
// CREATE/EDIT FUNCTIONS
// ======================
function openCreatePopup() {
    isEditMode = false;
    createEditPopup.option("title", "Create New Room");
    createEditPopup.show();
    
    // Reset form
    $("#roomCode").val("");
    $("#capacity").val("1");
    $("#roomCode").prop("readonly", false);
}

function openEditPopup(room) {
    isEditMode = true;
    selectedRoom = room;
    createEditPopup.option("title", "Edit Room: " + room.room_code);
    createEditPopup.show();
    
    // Fill form with existing data
    $("#roomCode").val(room.room_code);
    $("#capacity").val(room.capacity);
    $("#roomCode").prop("readonly", true);
}

function submitRoomForm() {
    const roomCode = $("#roomCode").val().trim();
    const capacity = $("#capacity").val();
    
    // Validasi
    if (!roomCode) {
        DevExpress.ui.notify("Room code is required", "error", 2000);
        return;
    }
    
    // Format payload
    const payload = {
        room_code: roomCode,
        capacity: parseInt(capacity)
    };
    
    // Determine URL dan method
    const url = isEditMode ? `/api/rooms/${selectedRoom.id}` : '/api/rooms';
    const method = isEditMode ? 'PUT' : 'POST';
    
    // Send request
    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(response) {
            DevExpress.ui.notify(
                isEditMode ? "Room updated successfully" : "Room created successfully",
                "success", 
                2000
            );
            createEditPopup.hide();
            roomsGrid.refresh();
            
            // Reset selected room
            if (isEditMode) selectedRoom = null;
        },
        error: function(xhr) {
            const errorMsg = xhr.responseJSON?.message || 
                           xhr.responseJSON?.errors?.room_code?.[0] || 
                           "Operation failed";
            DevExpress.ui.notify(errorMsg, "error", 3000);
        }
    });
}

// ======================
// EXISTING ASSIGN FUNCTIONS (tidak diubah)
// ======================
function openAssignPopup(room) {
    selectedRoom = room;
    assignPopup.show();

    $("#assignType").dxSelectBox({
        dataSource: [
            { id: "employee", name: "Employee" },
            { id: "guest", name: "Guest" }
        ],
        displayExpr: "name",
        valueExpr: "id",
        onValueChanged(e) {
            if (e.value === 'employee') {
                $("#employeeBox").removeClass('d-none');
                $("#guestBox").addClass('d-none');
                loadEmployees();
            } else {
                $("#guestBox").removeClass('d-none');
                $("#employeeBox").addClass('d-none');
            }
        }
    });
}

function loadEmployees() {
    $("#employeeSelect").dxSelectBox({
        dataSource: {
            load: () => $.getJSON('/api/employees?available=1')
        },
        displayExpr: "name",
        valueExpr: "id",
        placeholder: "Select employee"
    });
}

function submitAssign() {
    const type = $("#assignType").dxSelectBox("instance").option("value");

    let payload = {
        room_id: selectedRoom.id,
        type: type
    };

    if (type === 'employee') {
        payload.employee_id =
            $("#employeeSelect").dxSelectBox("instance").option("value");

        if (!payload.employee_id) {
            DevExpress.ui.notify("Select employee first", "error", 2000);
            return;
        }
    } else {
        payload.guest_name = $("#guestName").val();
        if (!payload.guest_name) {
            DevExpress.ui.notify("Guest name required", "error", 2000);
            return;
        }
    }

    $.ajax({
        url: "/api/room-occupancies",
        method: "POST",
        data: JSON.stringify(payload),
        success() {
            DevExpress.ui.notify("Room assigned successfully", "success", 2000);
            assignPopup.hide();
            roomsGrid.refresh();
        },
        error(xhr) {
            DevExpress.ui.notify(
                xhr.responseJSON?.message ?? "Failed",
                "error",
                2500
            );
        }
    });
}
</script>

<style>
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush