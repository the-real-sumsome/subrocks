function showAlert(id) {
    $(id).fadeIn();
}

function addAlert(id, text) {
    $(".alerts").append("<div class='alert' style='display: none;' id=" + id + ">" + text + "</div>");
}