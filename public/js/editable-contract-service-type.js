var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                jqTds[0].innerHTML = '<input type="text" class="form-control small" readonly="true" value="' + aData[0] + '">';                
                jqTds[1].innerHTML = '<input type="text" class="form-control small" value="' + aData[1] + '">';                
                jqTds[2].innerHTML = '<a class="edit" href="">Save</a>';
                jqTds[3].innerHTML = '<a class="cancel" href="">Cancel</a>';
            }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);                
                oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 2, false);
                oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 3, false);
                oTable.fnDraw();
            }

            function cancelEditRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);                
                oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 2, false);
                oTable.fnDraw();
            }

            var oTable = $('#editable-listing').dataTable({
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 15,
                "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records per page",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
                },
                "aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [2,3]
                    }
                ]
            });

            jQuery('#editable-listing_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            jQuery('#editable-listing_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown

            var nEditing = null;

            $('#editable-listing_new').click(function (e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '', '',
                        '<a class="edit" href="">Edit</a>', '<a class="cancel" data-mode="new" href="">Cancel</a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                editRow(oTable, nRow);
                nEditing = nRow;
            });

            $('#editable-listing a.delete').live('click', function (e) {
                e.preventDefault();

                if (confirm("Are you sure to delete this row ?") == false) {
                    return;
                }

                var nRow = $(this).parents('tr')[0];                
                var aData = oTable.fnGetData(nRow);
                
                $.ajax({
                    url: base_url + "/ajax/deleteContractServiceType",
                    data: {
                        'id': aData[0]
                    },
                    success: function(data) {                            
                        if(data == 1){
                            oTable.fnDeleteRow(nRow);
                            alert("Service Type Deleted!");
                        } else {
                            alert("Service Type couldn't be deleted!");
                        }
                        
                    },
                });
            });

            $('#editable-listing a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#editable-listing a.edit').live('click', function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];    

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Save") {
                    var jqInputs = $('input', nRow);
                                        
                    $.ajax({
                        url: base_url + "/ajax/saveContractServiceType",
                        data: {
                            'id': jqInputs[0].value,
                            'service_type': jqInputs[1].value
                        },
                        success: function(data) {
                            if(data == false) {                                
                                alert("Service Type couldn't be saved!");
                                return;
                            } else if (data == 'updated') {
                                alert("Service Type Updated!");
                            } else if (data != false && !isNaN(data)) {
                                var jqInputs = $('input', nRow);
                                jqInputs[0].value = data;
                                // var jqTds = $('>td', nRow);
                                // jqTds[0].innerHTML = data;
                                alert("Service Type Saved!");
                            }

                            /* Editing this row and want to save it */
                            saveRow(oTable, nEditing);
                            nEditing = null;
                        },
                    });

                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

}();