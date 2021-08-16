function dropdownchannel() {
    if ($("#channel-customize").first().is(":hidden")) {
      $("#channel-customize").slideDown("slow");
    } else {
      $("#channel-customize").slideUp("slow");
    }
  }
  
  function pictures_table() {
      document.getElementById("misc-table").style.display = "none";
      document.getElementById("bg-table").style.display = "none";
      document.getElementById("pictures-table").style.display = "block";
      document.getElementById("layout-table").style.display = "none";
      document.getElementById("pictures").className = "selected-user";
      document.getElementById("text").className = "non";
      document.getElementById("bg").className = "non";
  };
  
  function text_table() {
      document.getElementById("misc-table").style.display = "block";
      document.getElementById("bg-table").style.display = "none";
      document.getElementById("layout-table").style.display = "none";
      document.getElementById("pictures-table").style.display = "none";
      document.getElementById("pictures").className = "non";
      document.getElementById("layout-table").style.display = "none";
      document.getElementById("text").className = "selected-user";
      document.getElementById("bg").className = "non";
  };
  
  function bg_table() {
      document.getElementById("misc-table").style.display = "none";
      document.getElementById("bg-table").style.display = "block";
      document.getElementById("layout-table").style.display = "none";
      document.getElementById("pictures-table").style.display = "none";
      document.getElementById("pictures").className = "non";
      document.getElementById("pictures").className = "non";
      document.getElementById("text").className = "non";
      document.getElementById("bg").className = "selected-user";
  };
  
  function layout_table() {
      document.getElementById("misc-table").style.display = "none";
      document.getElementById("bg-table").style.display = "none";
      document.getElementById("layout-table").style.display = "block";
      document.getElementById("pictures-table").style.display = "none";
      document.getElementById("pictures").className = "non";
      document.getElementById("pictures").className = "non";
      document.getElementById("text").className = "non";
      document.getElementById("bg").className = "selected-user";
  };