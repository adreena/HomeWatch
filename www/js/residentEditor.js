require(['jquery',
         'vendor/jquery-ui',
         'vendor/jquery.scrollTo-min',
         'vendor/jquery.validate.min'],

function ($) {
    "use strict";

    var SCROLL_SPEED = 200;
    var SCROLL_OPTIONS = {offset: -100}

    var RESIDENT_EDITOR_ID = "#resident-editor";
    var RESIDENT_DISPLAY_ID = "#resident-display";

    var residentEditor;
    
    $(function () {
        initResidentEditor();
        
        // Init editor reset buttons
        $(document).find('.button.editor-reset').click(resetEditor);
    });

    // =============================================================================================
    // RESIDENT CONFIG
    // =============================================================================================
    function initResidentEditor() {
        // Init the edit and delete buttons
        //$(RESIDENT_DISPLAY_ID).find('.delete-resident').click(deleteResident);
        $(RESIDENT_DISPLAY_ID).find('.edit-resident').click(editResident);
        
        // Init the editor form
        residentEditor = $(RESIDENT_EDITOR_ID)[0];
        residentEditor.reset();
        
        $(residentEditor).validate({
            submitHandler: updateResident,
            rules: {
	            name: "required",
	            username: "required",
	            room: "required"
	        },
	        errorElement: "div",
            errorPlacement: function(error, element) {
                $(element).prev().before(error);
            },
	        onkeyup: false
        });
        
        setEditorEnabled(residentEditor, false);
    }
    
    function editResident(event) {
        var editButton = event.target;
        var residentData = getRowData(editButton);
        setResidentEditorData(residentData);
        editConfig(residentEditor, residentData);
        
        setEditorEnabled(residentEditor, true);
    }

    function updateResident() {
        $.post('/manager/update-resident.php', getResidentEditorData())
        .done(function(data) {
            residentEditor.reset();
            location.reload();
        })
        .fail(function(data) {
            alert("Error Updating Resident: " + data.statusText);
        });

        return false;
    }

    function deleteResident(event) {
        var deleteButton = event.target;
        var residentID = getRowData(deleteButton).id;

        $.post('/manager/delete-resident.php', {id: residentID})
        .done(function(data) { window.location.reload(); })
        .fail(function(data) { alert("Error deleting resident: " + data.statusText); });
    }

    function getResidentEditorData() {
        var residentEditorContents = $(residentEditor).contents();

        return {
            id: residentEditorContents.find('input[name=id]').val(),
            name: residentEditorContents.find('input[name=name]').val(),
            username: residentEditorContents.find('input[name=username]').val(),
            room: residentEditorContents.find('input[name=room]').val(),
            location: residentEditorContents.find('input[name=location]').val(),
            roomstatus: residentEditorContents.find('input[name=roomstatus]').val()
        };
    }

    function setResidentEditorData(resident) {
        var residentEditorContents = $(residentEditor).contents();
        residentEditorContents.find('input[name=name]').val(resident.name);
        residentEditorContents.find('input[name=username]').val(resident.username);
        residentEditorContents.find('input[name=room]').val(resident.room);
        residentEditorContents.find('input[name=location]').val(resident.location);
        residentEditorContents.find('input[name=roomstatus]').val(resident.roomstatus);
        residentEditorContents.find('input[name=id]').val(resident.id);
    }

    // =============================================================================================
    // GENERAL CONFIG
    // =============================================================================================
    function getRowData(rowButton) {
        var row = $(rowButton).closest("tr");

        return {
            id: row.attr('id').match(/\d+/)[0],
            name: $(row.children(".name")[0]).text(),
            username: $(row.children(".username")).text(),
            room: $(row.children(".room")).text(),
            location: $(row.children(".location")).text(),
            roomstatus: $(row.children(".roomstatus")).text()
        };
    }

    function editConfig(editor, data) {
        $.scrollTo(editor, SCROLL_SPEED, SCROLL_OPTIONS);

        // Make the legend red and add (EDITING "NAME") to text
        var legend = $(editor).find("legend")[0];
        legend.style.color = 'red';
        legend.innerHTML = legend.innerHTML.replace(/\(.*$/, ""); // Remove any existing (EDITING "NAME") text
        legend.innerHTML += " (EDITING \"" + data.name + "\")";
    }

    function resetEditor(event) {
        var clearButton = event.target;
        var form = $(clearButton).closest("form")[0];
        form.reset();

        var legend = $(form).find("legend")[0];
        legend.style.color = 'black';

        legend.innerHTML = legend.innerHTML.replace(/\(.*$/, "");
        
        setEditorEnabled(residentEditor, false);
    }
    
    function setEditorEnabled(editor, enabled) {
        $(editor).find('input,button').attr('disabled', !enabled);;
    }
});
