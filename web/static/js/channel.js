function selectWatch(id) {
    if(id == "#info-panel") {
        $("#info-panel").fadeIn(75);
        $("#favorite-panel").fadeOut(5);
        $("#playlist-panel").fadeOut(5);
        $("#flag-panel").fadeOut(5);
    }

    if(id == "#favorite-panel") {
        $("#info-panel").fadeOut(5);
        $("#favorite-panel").fadeIn(75);
        $("#playlist-panel").fadeOut(5);
        $("#flag-panel").fadeOut(5);
    }

    if(id == "#share-panel") {
        $("#info-panel").fadeOut(5);
        $("#favorite-panel").fadeOut(5);
        $("#playlist-panel").fadeIn(75);
        $("#flag-panel").fadeOut(5);
    }

    if(id == "#flag-panel") {
        $("#info-panel").fadeOut(5);
        $("#favorite-panel").fadeOut(5);
        $("#playlist-panel").fadeOut(5);
        $("#flag-panel").fadeIn(75);
    }
}

function main() {
    $("#www-main").fadeIn(75);
    $("#www-grid").fadeOut(75);
}

function grid() {
    $("#www-main").fadeOut(75);
    $("#www-grid").fadeIn(75);
}