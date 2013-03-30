
// TODO: use require js
// Requires:
// - jquery-ui
// - jquery.scrollto

var SCROLL_SPEED = 200;
var SCROLL_OPTIONS = {offset: -100}

var utilityEditorID = "#utility-editor";

var utilityEditor;

$(window).load(function() {

    utilityEditor = $(utilityEditorID)[0];

    utilityEditor.reset();

}); // window load

// =================================================================================================
// UTILITY CONFIG
// =================================================================================================
function editUtility(editButton) {
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

function deleteUtility(deleteButton) {
    var utilityID = getRowData(deleteButton).id;

    $.post('/manager/delete-utility.php', {id: utilityID})
    .done(function(data) { window.location.reload(); })
    .fail(function(data) { alert("Error deleting utility cost configuration: " + data.statusText); });
}

function getUtilityEditorData() {
    var utilityEditorContents = $(utilityEditor).contents();

    return {
        id: utilityEditorContents.find('input[name=id]').val(),
        type: utilityEditorContents.find('input[name=type]').val(),
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
}

// =================================================================================================
// GENERAL CONFIG
// =================================================================================================
function getRowData(rowButton) {
    var row = $(rowButton).closest("tr");

    return {
        id: row.attr('id').match(/\d+/)[0],
        type: row.children(".type")[0].innerHTML,
        price: row.children(".price")[0].innerHTML,
        startdate: row.children(".startdate")[0].innerHTML,
        enddate: row.children(".enddate")[0].innerHTML
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

