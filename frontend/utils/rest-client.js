let RestClient = {
   get: function (url, callback, error_callback) {
      $.ajax({
         url: Constants.get_api_base_url() + url,
         type: "GET",
         beforeSend: function (xhr) {
           xhr.setRequestHeader(
             "Authentication",
             localStorage.getItem("user_token")
           );
         },
         success: function (response) {
           if (callback) callback(response);
         },
         error: function (jqXHR, textStatus, errorThrown) {
           if (error_callback) error_callback(jqXHR, textStatus, errorThrown);
         },
       });
   },

   request: function (url, method, data, callback, error_callback, dataType = 'json') {
     $.ajax({
       url: Constants.get_api_base_url() + url,
       type: method,
       contentType: 'application/json',
       dataType: dataType,
       beforeSend: function (xhr) {
         xhr.setRequestHeader(
           "Authentication",
           localStorage.getItem("user_token")
         );
       },
       data: data ? JSON.stringify(data) : null,  // <-- send null if no data
       // Add this to handle empty JSON response gracefully:
       converters: {
         "text json": function(result) {
           return result === "" ? {} : JSON.parse(result);
         }
       }
     })
     .done(function (response, status, jqXHR) {
       if (callback) callback(response);
     })
     .fail(function (jqXHR, textStatus, errorThrown) {
       if (error_callback) {
         error_callback(jqXHR, textStatus, errorThrown);
       } else {
         toastr.error(jqXHR.responseJSON?.message || "Unknown error");
       }
     });
   },

   post: function (url, data, callback, error_callback) {
     RestClient.request(url, "POST", data, callback, error_callback);
   },

   delete: function (url, data, callback, error_callback) {
     RestClient.request(url, "DELETE", data, callback, error_callback,'text');
   },

   patch: function (url, data, callback, error_callback) {
     RestClient.request(url, "PATCH", data, callback, error_callback);
   },

   put: function (url, data, callback, error_callback) {
     RestClient.request(url, "PUT", data, callback, error_callback);
   },
};
