 $(function(){
    $('#tagname').on('click', function(){
        var tag = '{name}';
        var cursorPos = $('#divInput-edit-template').prop('selectionStart');
        var v = $('#divInput-edit-template').emojioneArea()[0].emojioneArea.getText();
        var textBefore = v.substring(0,  cursorPos );
        var textAfter  = v.substring( cursorPos, v.length );
        $("#divInput-edit-template").emojioneArea()[0].emojioneArea.setText(textBefore+tag+textAfter);
    });
 }); 