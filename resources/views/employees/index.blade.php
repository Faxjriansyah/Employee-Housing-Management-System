@extends('layouts.app')

@section('title', 'Employees')
@section('page-title', 'Employees')
@section('page-subtitle', 'Employee Management')

@section('content')

    <div class="mb-3">
        <button id="addEmployeeBtn" class="btn btn-primary">+ Add Employee</button>
    </div>

    <div id="employeeGrid"></div>
    <div id="employeePopup"></div>

@endsection

@push('scripts')
    <script>
        let popup;
        let isEdit = false;
        let currentId = null;
        let grid;

        $(function() {

            // ======================
            // GRID
            // ======================
            grid = $("#employeeGrid").dxDataGrid({
                dataSource: {
                    load: () => $.getJSON('/api/employees')
                },
                showBorders: true,
                columnAutoWidth: true,
                paging: {
                    pageSize: 10
                },

                columns: [{
                        dataField: "employee_code",
                        caption: "Code"
                    },
                    {
                        dataField: "name",
                        caption: "Name"
                    },
                    {
                        dataField: "department.name",
                        caption: "Department"
                    },
                    {
                        dataField: "is_active",
                        caption: "Status",
                        cellTemplate(c, o) {
                            $("<span>")
                                .text(o.value ? 'Aktif' : 'Nonaktif')
                                .css("color", o.value ? 'green' : 'red')
                                .appendTo(c);
                        }
                    },
                    {
                        caption: "Action",
                        cellTemplate(c, o) {

                            $("<button>")
                                .addClass("btn btn-sm btn-warning me-1")
                                .text("Edit")
                                .on("click", () => openPopup(o.data))
                                .appendTo(c);

                            $("<button>")
                                .addClass("btn btn-sm btn-secondary")
                                .text(o.data.is_active ? 'Disable' : 'Enable')
                                .on("click", () => toggleEmployee(o.data.id))
                                .appendTo(c);
                        }
                    }
                ]
            }).dxDataGrid("instance");


            // ======================
            // POPUP
            // ======================
            popup = $("#employeePopup").dxPopup({
                title: "Employee",
                width: 450,
                visible: false,
                closeOnOutsideClick: true,
                showCloseButton: true,
                contentTemplate: () => `
        <div class="mb-2">
            <label>Employee Code</label>
            <input id="empCode" class="form-control" disabled>
        </div>

        <div class="mb-2">
            <label>Name</label>
            <input id="empName" class="form-control">
        </div>

        <div class="mb-2">
            <label>Department</label>
            <div id="deptSelect"></div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button id="deleteBtn" class="btn btn-danger d-none">Delete</button>
            <div>
                <button class="btn btn-secondary me-2" onclick="popup.hide()">Cancel</button>
                <button class="btn btn-primary" onclick="saveEmployee()">Save</button>
            </div>
        </div>
    `
            }).dxPopup("instance");



            // ======================
            // ADD BUTTON
            // ======================
            $("#addEmployeeBtn").click(() => openPopup());


            // ======================
            // OPEN POPUP
            // ======================
            window.openPopup = function(data = null) {

                isEdit = !!data;
                currentId = data?.id ?? null;

                popup.show();

                $("#empName").val(data?.name ?? '');
                $("#empCode").val(data?.employee_code ?? 'AUTO');

                $("#deptSelect").dxSelectBox({
                    dataSource: "/api/departments",
                    displayExpr: "name",
                    valueExpr: "id",
                    value: data?.department_id ?? null
                });

                // Show delete only on edit
                if (isEdit) {
                    $("#deleteBtn").removeClass('d-none');
                } else {
                    $("#deleteBtn").addClass('d-none');
                }
            };

            // ======================
            // SAVE
            // ======================
            window.saveEmployee = function() {

                const name = $("#empName").val();
                const departmentId = $("#deptSelect").dxSelectBox("instance").option("value");

                if (!name || !departmentId) {
                    DevExpress.ui.notify("Name & Department required", "error", 2000);
                    return;
                }

                let payload = {
                    name: name,
                    department_id: departmentId
                };

                const url = isEdit ?
                    `/api/employees/${currentId}` :
                    '/api/employees';

                if (isEdit) payload._method = 'PUT';

                $.ajax({
                        url: url,
                        method: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'Accept': 'application/json'
                        },
                        data: JSON.stringify(payload)
                    })
                    .done(() => {
                        DevExpress.ui.notify("Employee saved", "success", 1500);
                        popup.hide();
                        grid.refresh();
                    })
                    .fail(xhr => {
                        let msg = 'Error';
                        if (xhr.status === 422) {
                            msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join('<br>');
                        }
                        DevExpress.ui.notify(msg, "error", 3000);
                    });
            };


            $(document).on('click', '#deleteBtn', function() {

                if (!confirm('Delete this employee?')) return;

                $.ajax({
                        url: `/api/employees/${currentId}`,
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .done(() => {
                        DevExpress.ui.notify("Employee deleted", "success", 1500);
                        popup.hide();
                        grid.refresh();
                    })
                    .fail(xhr => {
                        DevExpress.ui.notify(xhr.responseJSON?.message ?? 'Error', "error", 3000);
                    });
            });

            // ======================
            // TOGGLE ACTIVE
            // ======================
            window.toggleEmployee = function(id) {
                $.ajax({
                    url: `/api/employees/${id}/toggle`,
                    method: 'PATCH'
                }).done(() => grid.refresh());
            };

        });
    </script>
@endpush
