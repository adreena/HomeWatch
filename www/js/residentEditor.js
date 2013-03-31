
// TODO: use require js
// Requires:
// - jquery-ui
// - jquery.scrollto

var SCROLL_SPEED = 200;
var SCROLL_OPTIONS = {offset: -100}

var residentEditorID = "#resident-editor";

var residentEditor;

$(window).load(function() {

    residentEditor = $(residentEditorID)[0];

    residentEditor.reset();

}); // window load

// =================================================================================================
// RESIDENT CONFIG
// =================================================================================================
function editResident(editButton) {
    var residentData = getRowData(editButton);
    setResidentEditorData(residentData);
    editConfig(residentEditor, residentData);
}

function updateResident() {
    $.post('/manager/update-resident.php', getResidentEditorData())
    .done(function(data) {
        residentEditor.reset();
        location.reload();
    })
    .fail(function(data) {
        alert("Error Submitting Resident: " + data.statusText);
    });

    return false;
}

function deleteResident(deleteButton) {
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

// =================================================================================================
// GENERAL CONFIG
// =================================================================================================
function getRowData(rowButton) {
    var row = $(rowButton).closest("tr");

    return {
        id: row.attr('id').match(/\d+/)[0],
        name: row.children(".name")[0].innerHTML,
        username: row.children(".username")[0].innerHTML,
        room: row.children(".room")[0].innerHTML,
        location: row.children(".location")[0].innerHTML,
        roomstatus: row.children(".roomstatus")[0].innerHTML
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

function resetEditor(clearButton) {
    var form = $(clearButton).closest("form")[0];
    form.reset();

    var legend = $(form).find("legend")[0];
    legend.style.color = 'black';

    legend.innerHTML = legend.innerHTML.replace(/\(.*$/, "");
}

