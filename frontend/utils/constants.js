let Constants = {

   get_api_base_url: function () {
      if (location.hostname === "localhost" ){
         return "http://localhost/TarikTopic/web-dev-uni/backend/";
      }
      else
      {
         return  "https://comic-app-jhrcg.ondigitalocean.app/"
      }
   },

   //PROJECT_BASE_URL: "http://localhost/TarikTopic/web-dev-uni/backend/",
   USER_ROLE: "user",
   ADMIN_ROLE: "admin"
}
