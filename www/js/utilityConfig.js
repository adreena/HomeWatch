require(['jquery',
         'vendor/jquery.scrollTo-min',
         'vendor/jquery.validate.min',
         'vendor/jquery.jdpicker'],

function ($) {
    "use strict";

    var SCROLL_SPEED = 200;
    var SCROLL_OPTIONS = {offset: -100}

    var UTILITY_EDITOR_ID = "#utility-editor";
    var UTILITY_DISPLAY_ID = "#utility-display";

    var utilityEditor;

    $(function () {
        initUtilityEditor();
        
        // Init editor reset buttons
        $(document).find('.button.editor-reset').click(resetEditor);
    });

    function initUtilityEditor() {
        refreshUtilityDatePickers();
        
        // Init the edit and delete buttons
        $(UTILITY_DISPLAY_ID).find('.delete-utility').click(deleteUtility);
        $(UTILITY_DISPLAY_ID).find('.edit-utility').click(editUtility);
        
        // Init the editor form 
        utilityEditor = $(UTILITY_EDITOR_ID)[0];
        utilityEditor.reset();

        $(utilityEditor).validate({
            submitHandler: submitUtility,
            rules: {
	            price: "required",
	            startdate: "required",
	            enddate: "required"
	        },
	        errorElement: "div",
            errorPlacement: function(error, element) {
                $(element).prev().before(error);
            },
	        onkeyup: false
        });
    }
    
    function refreshUtilityDatePickers() {
        var dateInputs = $("[name=startdate], [name=enddate]");
        
        // HACK: If the date input is currently in a date picker, it is necessary to extract it
        // before creating a new one.
        var jdpickers = $(dateInputs).parent(".jdpicker_w");
        $(dateInputs).insertBefore(".jdpicker_w");
        jdpickers.remove();
        
        // Make jdPicker give us controls that always show.
        dateInputs.attr('type', 'hidden');
        dateInputs.jdPicker({
            date_format: 'YYYY-mm-dd',
            start_of_week: 0
        });
    }
    
    // =================================================================================================
    // UTILITY CONFIG
    // =================================================================================================
    function editUtility(event) {
        var editButton = event.target;
        var utilityData = getRowData(editButton);
        setUtilityEditorData(utilityData);
        editConfig(utilityEditor, utilityData);
    }

    function submitUtility() {
        $.post('/manager/submit-utility.php', getUtilityEditorData())
        .done(function(data) {
            utilityEditor.reset();
            location.reload();
        })
        .fail(function(data) {
            alert("Error Submitting Utility Costs: " + data.statusText);
        });

        return false;
    }

    function deleteUtility(event) {
        var deleteButton = event.target;
        var utilityID = getRowData(deleteButton).id;

        $.post('/manager/delete-utility.php', {id: utilityID})
        .done(function(data) { window.location.reload(); })
        .fail(function(data) { alert("Error deleting utility cost configuration: " + data.statusText); });
    }

    function getUtilityEditorData() {
        var utilityEditorContents = $(utilityEditor).contents();

        return {
            id: utilityEditorContents.find('input[name=id]').val(),
            type: utilityEditorContents.find('select[name=type]').val(),
            price: utilityEditorContents.find('input[name=price]').val(),
            startdate: utilityEditorContents.find('input[name=startdate]').val(),
            enddate: utilityEditorContents.find('input[name=enddate]').val()
        };
    }

    function setUtilityEditorData(utility) {
        var utilityEditorContents = $(utilityEditor).contents();
        utilityEditorContents.find('input[name=type]').val(utility.type);
        utilityEditorContents.find('input[name=price]').val(utility.price);
        utilityEditorContents.find('input[name=startdate]').val(utility.startdate);
        utilityEditorContents.find('input[name=enddate]').val(utility.enddate);
        utilityEditorContents.find('input[name=id]').val(utility.id);
        
        refreshUtilityDatePickers();
    }

    // =================================================================================================
    // GENERAL CONFIG
    // =================================================================================================
    function getRowData(rowButton) {
        var row = $(rowButton).closest("tr");

        return {
            id: row.attr('id').match(/\d+/)[0],
            type: $(row.children(".type")).text(),
            price: $(row.children(".price")).text(),
            startdate: $(row.children(".startdate")).text(),
            enddate: $(row.children(".enddate")).text()
        };
    }

    function editConfig(editor, data) {
        $.scrollTo(editor, SCROLL_SPEED, SCROLL_OPTIONS);

        // Make the legend red and add (EDITING "NAME") to text
        var legend = $(editor).find("legend")[0];
        legend.style.color = 'red';
        legend.innerHTML = legend.innerHTML.replace(/\(.*$/, ""); // Remove any existing (EDITING "NAME") text
        legend.innerHTML += " (EDITING \"" + data.startdate + " to " + data.enddate + "\")";
    }

    function resetEditor(clearButton) {
        var form = $(clearButton).closest("form")[0];
        form.reset();

        var legend = $(form).find("legend")[0];
        legend.style.color = 'black';

        legend.innerHTML = legend.innerHTML.replace(/\(.*$/, "");
    }
});

