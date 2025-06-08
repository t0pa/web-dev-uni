var UserService = {
 init: function () {

toastr.options = {
  closeButton: true,
  progressBar: true,
  positionClass: "toast-top-right",
  timeOut: "3000"
};


   var token = localStorage.getItem("user_token");
   if (token && token !== undefined) {
     window.location.replace("index.html");
   }
   
   var currentPage = window.location.pathname.split("/").pop();

    if (currentPage === "login.html") {

  $("#loginForm").validate({
    rules: {
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 3,
        maxlength: 20
      }
    },
    messages: {
      email: {
        required: "Please enter your email",
        email: "Please enter a valid email"
      },
      password: {
        required: "Please enter your password",
        minlength: "Password must be at least 3 characters",
        maxlength: "Password must not exceed 20 characters"
      }
    },
    errorPlacement: function(error, element) {
      // Place error at bottom right of the input's parent
      var parent = element.closest('.form-floating, .mb-3, .mb-md-0');
      parent.css('position', 'relative');
      error.addClass('text-danger');
      error.css({
        position: 'static', // Let it flow naturally below the input
        'font-size': '0.95em',
        'margin-top': '0.30rem',
        'background': 'transparent',
        'padding': 0
      });
      // Place error after the input field
      element.after(error);
    },
    success: function(label) {
      label.remove();
    },
    submitHandler: function (form) {
      const entity = Object.fromEntries(new FormData(form).entries());
      UserService.login(entity);
    }
  });

  }

  if (currentPage === "register.html") {
    $("#registerForm").validate({
       rules: {
      username: {
        required: true,
        minlength: 3
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 8,
        maxlength: 20
      }
    },
    messages: {
      username: {
        required: "Please enter your username",
        minlength: "Username must be at least 3 characters"
      },
      email: {
        required: "Please enter your email",
        email: "Please enter a valid email"
      },
      password: {
        required: "Please enter your password",
        minlength: "Password must be at least 8 characters",
        maxlength: "Password must not exceed 20 characters"
      }
    },
    errorPlacement: function(error, element) {
      // Find the closest parent with class 'form-floating' or 'mb-3' (adjust if needed)
      var parent = element.closest('.form-floating, .mb-3, .mb-md-0');
      parent.css('position', 'relative');
      error.addClass('text-danger');
      error.css({
        position: 'absolute',
        right: '10px',
        bottom: '-20px', // Adjust as needed
        'font-size': '0.9em',
        'background': '#fff',
        'padding': '0 4px'
      });
      parent.append(error);
    },
    success: function(label) {
      label.remove();
    },
      submitHandler: function (form) {
        var entity = Object.fromEntries(new FormData(form).entries());
        UserService.register(entity);
      },
    });

  }



 },
 login: function (entity) {
  $.blockUI({ message: '<h4>Logging in...</h4>' });

  $.ajax({
    url: Constants.PROJECT_BASE_URL + "auth/login",
    type: "POST",
    data: JSON.stringify(entity),
    contentType: "application/json",
    dataType: "json",
    success: function (result) {
      localStorage.setItem("user_token", result.data.token);
      toastr.success("Login successful!");
      setTimeout(() => {
        window.location.replace("index.html");
      }, 1000);
    },
    error: function (xhr) {
      let msg = "Login failed.";
      if (xhr.responseJSON && xhr.responseJSON.message) {
        msg = xhr.responseJSON.message;
      }
      toastr.error(msg);
    },
    complete: function () {
      $.unblockUI();
    }
  });
}
,


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
          
          document.getElementById("sidebar-username").textContent = user.username;

        // Clear previously generated dynamic menu items
        $("#tabs").empty(); // Clears all items in #tabs
        $(".sb-sidenav-menu .nav").empty(); // Clears sidebar menu items if they are also dynamic


        let navItems = '';
        let sidebarItems = '';

        /* <div class="sb-sidenav-menu-heading">Core</div>' +
                           '<a class="nav-link" href="#dashboard">' +
                           '    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>' +
                           '    Dashboardd' +
                           '</a>' +
                           '<div class="sb-sidenav-menu-heading">Addons</div> put this in sidebar items if needed */

        switch(user.role) {
            case Constants.USER_ROLE:
                navItems += /* '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded " href="#students">Studentsa</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#highcharts">Highcharts</a>'+
                            '</li>'+
                            '<li class="nav-item mx-0 mx-lg-1">'+
                                '<a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#forms">Forms</a>'+
                            '</li>'; */

                sidebarItems += /* '<a class="nav-link" href="#charts2">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>' +
                                '    Charts' + */
                                '</a>' +
                                '<a class="nav-link" href="#explore">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>' +
                                '    Explore Comics' +
                                '</a>' +
                                '<a class="nav-link" href="#profile">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>' +
                                '    Profile' +
                                '</a>' +
                                '<a class="nav-link" href="#wishlist">' + // Also add to sidebar if applicable
                                '    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>' +
                                '    Wishlist' +
                                '</a>'+
                                '<a class="nav-link" href="#library">' + // Also add to sidebar if applicable
                                '    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>' +
                                '    Library' +
                                '</a>'; // Add other user-specific sidebar links
                                

                break;
            case Constants.ADMIN_ROLE:
               /*  navItems += '<li class="nav-item mx-0 mx-lg-1">'+
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
                            '</li>'; */

                sidebarItems += 
                                '<a class="nav-link" href="#admin-settings">' + // Example admin specific sidebar link
                                '    <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>' +
                                '    ADMIN Settings' +
                                '</a>' 
                                + '<a class="nav-link" href="#dashboard">' +
                                '    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>' +
                                '    Dashboard' +
                                '</a>' 
                                ;
                break;
            default:
                window.location.replace("login.html");
                return; // Important: exit the function
        }

        $("#tabs").html(navItems + '<li><button class="btn btn-primary" onclick="UserService.logout()">Logout</button></li>');
        $(".sb-sidenav-menu .nav").html(sidebarItems); // Populate the sidebar
    }
    
};
