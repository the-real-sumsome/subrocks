var alerts = 0; 
$('#leftlayoutset' ).submit(
    function( e ) {
        var data = new FormData(this);
        jQuery.each(jQuery('#fileToUpload')[0].files, function(i, file) {
            data.append('file-'+i, file);
        });

        $.ajax( {
            url: '/post/channel_update_new',
            type: 'POST',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(result){
                alerts++;
                addAlert("editsuccess_" + alerts, "Successfully updated your channel!");
                showAlert("#editsuccess_" + alerts);
                console.log("DEBUG: " + result);
            }
        } );
        e.preventDefault();
    } 
);

function getData() {
    var layout = draggables2json();

    $.ajax({
    url: 'set_customization.php',
    method: 'POST',
    dataType: 'text',
    data: {
        layout: layout,
    },
    success: function(response) {
        alert("Successfully updated your channel layout.");
    }
    });
};

function onDragStart(event) {
  event
    .dataTransfer
    .setData('text/plain', event.target.id);

  event
    .currentTarget
    .style
    .backgroundColor = '#f2f2f2;';
}

function onDrop(event) {
  const id = event
    .dataTransfer
    .getData('text');

    const draggableElement = document.getElementById(id);
    const dropzone = event.target;
    dropzone.appendChild(draggableElement);

    event
    .dataTransfer
    .clearData();
}

function onDragOver(event) {
  event.preventDefault();
}

function draggables2json() {
  draggables = $("#layout").children();
  outj = {};
  for (di=0; di < draggables.length; di++) {
    outj[di] = draggables[di].textContent.replace(/\s+$/, '');
  }
  return JSON.stringify(outj);
}