function changeSelectedPerson() {
    var selectedPerson = $('#person-select').val();
    $('.selected-person').text(selectedPerson+' Person');
}