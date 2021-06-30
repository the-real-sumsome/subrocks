$("#channel-edit").click(function () {
    if ($("#channel-customize").first().is(":hidden")) {
      $("#channel-customize").slideDown("slow");
    } else {
      $("#channel-customize").slideUp("slow");
    }
  });