

function numInp(number) {
    var exiting = $('#general_input').val();
    var new_d = exiting + number.toString();

    $('#general_input').val(new_d);
    $('#general_input').focus()
}

function backSpace() {
    var exiting = $('#general_input').val();
    var new_d = exiting.substring(0,exiting.length - 1);

    $('#general_input').val(new_d);
    $('#general_input').focus()
}

function hideNumKeyboard() {
    document.getElementById('numericKeyboard').style.display = 'none';
}

function showNumKeyboard() {
    document.getElementById('numericKeyboard').style.display = '';
}

