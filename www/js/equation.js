
// TODO: use require js

var functionEditor;
var constantEditor;

$(window).load(function() {

    functionEditor = $("#function-editor")[0];
    constantEditor = $("#constant-editor")[0];

    functionEditor.reset();
    constantEditor.reset();

    $.get("/search/equation-variables.php")
    .done(function(data) {
        var availableTags = $.map(data, function (value, key) { return "\\$" + key + '$'; });
        
        var textbox = $("#function-editor input[name=value]")[0];
        $( "#function-editor input[name=value]" ).autocomplete(
            { source: availableTags,
              focus: function( event, ui ) {
              
                  // Get the text up to the caret position and replace the last variable tag with the focused item value
                  var replaceText = textbox.value.substring(0, textbox.selectionStart);
                  replaceText = replaceText.replace(/\\\$[^\\\$]*\$?$/, ui.item.value);
                  
                  // Apply the text to the textbox
                  var endText = textbox.value.substr(textbox.selectionStart);
                  textbox.value = replaceText + endText;
                  
                  // Set the caret position to the end of the replaced text
                  textbox.selectionStart = textbox.selectionEnd = replaceText.length;

                  // Let jquery-ui know that the event has been handled
                  event.preventDefault();
              },
              select: function( event, ui ) {
                  // Let jquery-ui know that we have already handled inserting the selection text (in the focus handler)
                  event.preventDefault();
              }
        });
    })
    .error(function() {
        alert("Failed to get equation variables: " + data.statusText);
    });
}); // window load

function onFunctionTextChanged(textbox) {
    // Try to match the start of a variable up to the caret position
    var text = textbox.value.substring(0, textbox.selectionStart);        
    var match = text.match(/\\\$[^\\\$]*$/);
    
    // If there is a match, open the autocomplete box for that match
    if (match) {
        $(textbox).autocomplete("search", match[0]);
    }
}

function editFunction(editButton) {
    setFunctionEditorData(getRowData(editButton));
}

function submitFunction() {
    $.post('/engineer/submit-function.php', getFunctionEditorData())
    .done(function(data) {
        functionEditor.reset();
        location.reload();
    })
    .fail(function(data) {
        alert("Error Submitting Function: " + data.statusText);
    });
    
    return false;
}

function deleteFunction(deleteButton) {
    var functionID = getRowData(deleteButton).id;
    
    $.post('/engineer/delete-function.php', {id: functionID})
    .done(function(data) { window.location.reload(); })
    .fail(function(data) { alert("Error deleting function: " + data.statusText); });
}

function getFunctionEditorData() {
    var functionEditorContents = $(functionEditor).contents();
    
    return {
        id: functionEditorContents.find('input[name=id]').val(),
        name: functionEditorContents.find('input[name=name]').val(),
        value: functionEditorContents.find('input[name=value]').val(),
        description: functionEditorContents.find('input[name=description]').val()        
    };
}

function setFunctionEditorData(fn) {
    var functionEditorContents = $(functionEditor).contents();
    functionEditorContents.find('input[name=name]').val(fn.name);
    functionEditorContents.find('input[name=value]').val(fn.value);
    functionEditorContents.find('input[name=description]').val(fn.description);
    functionEditorContents.find('input[name=id]').val(fn.id);
}

function editConstant(editButton) {
    setConstantEditorData(getRowData(editButton));
}

function submitConstant() {
    $.post('/engineer/submit-constant.php', getConstantEditorData())
    .done(function(data) {
        constantEditor.reset();
        location.reload();
    })
    .fail(function(data) {
        alert("Error Submitting Constant: " + data.statusText);
    });
    
    return false;
}

function deleteConstant(deleteButton) {
    var constantID = getRowData(deleteButton).id;
    
    $.post('/engineer/delete-constant.php', {id: constantID}, function(data) { window.location.reload(); })
    .done(function(data) { window.location.reload(); })
    .fail(function(data) { alert("Error deleting contant: " + data.statusText); });
}

function getConstantEditorData() {
    var constantEditorContents = $(constantEditor).contents();
    
    return {
        id: constantEditorContents.find('input[name=id]').val(),
        name: constantEditorContents.find('input[name=name]').val(),
        value: constantEditorContents.find('input[name=value]').val(),
        description: constantEditorContents.find('input[name=description]').val()
    };
}

function setConstantEditorData(constant) {
    var constantEditorContents = $(constantEditor).contents();
    constantEditorContents.find('input[name=name]').val(constant.name);
    constantEditorContents.find('input[name=value]').val(constant.value);
    constantEditorContents.find('input[name=description]').val(constant.description);
    constantEditorContents.find('input[name=id]').val(constant.id);
}

function getRowData(rowButton) {
    var row = $(rowButton).closest("tr");
    
    return {
        id: row.attr('id').match(/\d+/)[0],
        name: row.children(".name")[0].innerHTML,
        value: row.children(".value")[0].innerHTML,
        description: row.children(".description")[0].innerHTML
    };
}

