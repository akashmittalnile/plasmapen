$( document ).ready(function() {
    $(".sidebar-dropdown > a").click(function() {
    $(".sidebar-submenu").slideUp(200);
      if (
      $(this)
        .parent()
        .hasClass("active")
    ) {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .parent()
        .removeClass("active");
    } else {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .next(".sidebar-submenu")
        .slideDown(200);
      $(this)
        .parent()
        .addClass("active");
    }
  });

  $(".toggle-sidebar").click(function() {
    $(".main-site").toggleClass("toggled");
  });
  $("#show-sidebar").click(function() {
   $(".main-site").addClass("toggled");
  });
     
});

function launch_toast() {
  var x = document.getElementById("toast")
  x.className = "show";
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
}

