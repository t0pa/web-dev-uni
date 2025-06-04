var UserService = {
 init: function () {
   var token = localStorage.getItem("user_token");
   if (token && token !== undefined) {
     window.location.replace("index.html");
   }
   
   var currentPage = window.location.pathname.split("/").pop();

    if (currentPage === "login.html") {

   $("#loginForm").validate({
     submitHandler: function (form) {
       var entity = Object.fromEntries(new FormData(form).entries());
       UserService.login(entity);
     },
   });
  }

  if (currentPage === "register.html") {
    $("#registerForm").validate({
      submitHandler: function (form) {
        var entity = Object.fromEntries(new FormData(form).entries());
        UserService.register(entity);
      },
    });

  }



 },
 login: function (entity) {
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "auth/login",
     type: "POST",
     data: JSON.stringify(entity),
     contentType: "application/json",
     dataType: "json",
     success: function (result) {
       console.log(result);
       localStorage.setItem("user_token", result.data.token);
       window.location.replace("index.html"); 
 },
     error: function (XMLHttpRequest, textStatus, errorThrown) {
       toastr.error(XMLHttpRequest?.responseText ?  XMLHttpRequest.responseText : 'Error');
     },
   });
 },


 register: function (entity) {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "auth/register", // Make sure this matches your backend route
    type: "POST",
    data: JSON.stringify(entity),
    contentType: "application/json",
    dataType: "json",
    success: function (result) {
      console.log(result);
      toastr.success("Registration successful! Redirecting to login...");
      setTimeout(() => {
        window.location.replace("login.html");
      }, 1500); // Wait a bit to show the success toast
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Registration failed.');
    },
  });
},


 logout: function () {
   localStorage.clear();
   window.location.replace("login.html");
 },
   
   generateMenuItems: function(){
        const token = localStorage.getItem("user_token");
        if (!token) {
            window.location.replace("login.html");
            return;
        }

        const user = Utils.parseJwt(token).user;
          
        // Clear previously generated dynamic menu items
        $("#tabs").empty(); // Clears all items in #tabs
        $(".sb-sidenav-menu .nav").empty(); // Clears sidebar menu items if they are also dynamic

        let navItems = '';
        let sidebarItems = '<div class="sb-sidenav-menu-heading">Core</div>' +
                           '<a class="nav-link" href="#dashboard">' +
                           '    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>' +
                           '    Dashboardd' +
                           '</a>' +
                           '<div class="sb-sidenav-menu-heading">Addons</div>';

        switch(user.role) {
            case Constants.USER_ROLE:
                navItems += '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded " href="#students">Studentsa</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#highcharts">Highcharts</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#forms">Forms</a>'+
                            '</li>';

                sidebarItems += '<a class="nav-link" href="#charts2">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>' +
                                '    Charts' +
                                '</a>' +
                                '<a class="nav-link" href="#explore">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>' +
                                '    Explore Comics' +
                                '</a>' +
                                '<a class="nav-link" href="#profile">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>' +
                                '    Profile' +
                                '</a>' +
                                '<a class="nav-link" href="#students">' + // Also add to sidebar if applicable
                                '    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>' +
                                '    Studentsss' +
                                '</a>'+
                                '<a class="nav-link" href="#library">' + // Also add to sidebar if applicable
                                '    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>' +
                                '    Library' +
                                '</a>'; // Add other user-specific sidebar links
                                

                break;
            case Constants.ADMIN_ROLE:
                navItems += '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded " href="#students">Students</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#highcharts">Highcharts</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#forms">Forms</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#formsADMIN">FormsADMIN</a>'+
                            '</li>';

                sidebarItems += '<a class="nav-link" href="#charts2">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>' +
                                '    Charts' +
                                '</a>' +
                                '<a class="nav-link" href="#explore">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>' +
                                '    Explore Comics' +
                                '</a>' +
                                '<a class="nav-link" href="#library">' + // Also add to sidebar if applicable
                                '    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>' +
                                '    Library' +
                                '</a>' +
                                '<a class="nav-link" href="#profile">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>' +
                                '    Profile' +
                                '</a>' +
                                '<a class="nav-link" href="#wishlist">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>' +
                                '    Wishlist' +
                                '</a>' +
                                '<a class="nav-link" href="#admin-settings">' + // Example admin specific sidebar link
                                '    <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>' +
                                '    ADMIN Settings' +
                                '</a>';
                break;
            default:
                window.location.replace("login.html");
                return; // Important: exit the function
        }

        $("#tabs").html(navItems + '<li><button class="btn btn-primary" onclick="UserService.logout()">Logout</button></li>');
        $(".sb-sidenav-menu .nav").html(sidebarItems); // Populate the sidebar
    }
    
};
