let ProfileService = {
    
getUserProfile: function () {
        RestClient.get("user/me", function (user) {
            ProfileService.renderProfilePage(user);
        }, function (xhr, status, error) {
            console.error("Failed to load user profile:", error);
        });
    },

    renderProfilePage: function (user) {
    const container = document.querySelector(".container");
    container.innerHTML = `
    <div class="main-body">
      <div class="row gutters-sm">
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body text-center">
              <img src="${user.avatar || 'https://bootdey.com/img/Content/avatar/avatar7.png'}" class="rounded-circle" width="150">
              <div class="mt-3">
                <h4>${user.username}</h4>
                <p class="text-secondary mb-1">${user.role || 'User'}</p>
                <p class="text-muted font-size-sm">${user.email}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="card mb-3">
            <div class="card-body">

              <div id="profile-view">
                <div class="row">
                  <div class="col-sm-3"><h6 class="mb-0">Full Name</h6></div>
                  <div class="col-sm-9 text-secondary" id="profile-name">${user.username}</div>
                </div><hr>
                <div class="row">
                  <div class="col-sm-3"><h6 class="mb-0">Email</h6></div>
                  <div class="col-sm-9 text-secondary" id="profile-email">${user.email}</div>
                </div><hr>
                <div class="row">
                  <div class="col-sm-12">
                    <button class="btn btn-info" id="edit-profile-btn">Edit</button>
                  </div>
                </div>
              </div>

              <div id="edit-profile-form" style="display: none;">
                <div class="row mb-3">
                  <div class="col-sm-3"><h6 class="mb-0">Full Name</h6></div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" class="form-control" id="edit-name" value="${user.username}">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-3"><h6 class="mb-0">Email</h6></div>
                  <div class="col-sm-9 text-secondary">
                    <input type="email" class="form-control" id="edit-email" value="${user.email}">
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-9 offset-sm-3">
                    <button class="btn btn-primary px-4" id="save-profile-btn">Save Changes</button>
                    <button class="btn btn-secondary px-4" id="cancel-profile-btn">Cancel</button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
    `;

    // Event listeners
    document.getElementById("edit-profile-btn").addEventListener("click", () => {
        document.getElementById("profile-view").style.display = "none";
        document.getElementById("edit-profile-form").style.display = "block";
    });

    document.getElementById("cancel-profile-btn").addEventListener("click", () => {
        document.getElementById("profile-view").style.display = "block";
        document.getElementById("edit-profile-form").style.display = "none";
    });

    document.getElementById("save-profile-btn").addEventListener("click", () => {
        const updatedName = document.getElementById("edit-name").value;
        const updatedEmail = document.getElementById("edit-email").value;

        const updateData = {
            username: updatedName,
            email: updatedEmail
        };

        RestClient.put("user/update", updateData, function () {
            // On success, update UI
            document.getElementById("profile-name").innerText = updatedName;
            document.getElementById("profile-email").innerText = updatedEmail;

            document.getElementById("profile-view").style.display = "block";
            document.getElementById("edit-profile-form").style.display = "none";
console.log("Saving profile..."); // Debug
toastr.success("Profile updated!");

        }, function (xhr, status, error) {
            console.error("Failed to update profile:", error);
            toastr.error("Failed to update profile.");
        });
    });
}

};