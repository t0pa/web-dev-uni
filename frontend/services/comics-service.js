    let ComicService = {

        getAllComics: function () {
            RestClient.get("comics", function (data) {
                ComicService.renderComicCards(data);
            }, function (xhr, status, error) {
                console.error('Error fetching comics:', error);
            });
        },

        renderComicCards: function (comics) {
  let container = document.querySelector(".row-cols-xl-4");
  if (!container) {
    container = document.getElementById("comic-list");
  }
  container.innerHTML = '';
        const token = localStorage.getItem("user_token");

const user = Utils.parseJwt(token).user;
  comics.forEach(comic => {
    const card = document.createElement("div");
    card.className = "col mb-5";
    card.innerHTML = `
      <div class="card h-100">
        <img class="card-img-top" src="${comic.cover_image || 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg'}" alt="${comic.title}" />
        <div class="card-body p-4">
          <div class="text-center">
            <h5 class="fw-bolder">${comic.title}</h5>
            ${comic.price ? `$${comic.price}` : ''}
          </div>
        </div>
        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
          <div class="text-center">
            ${
              user.role === 'admin'
                ? `<button class="btn btn-primary me-2" onclick="ComicService.editComic(${comic.id})">Edit</button>
                   <button class="btn btn-danger" onclick="ComicService.deleteComic(${comic.id})">Delete</button>`
                : `<a class="btn btn-outline-dark mt-auto" onclick="ComicService.helperFunctions.navigateToComicDetails(${comic.id})">View options</a>`
            }
          </div>
        </div>
      </div>
    `;
    container.appendChild(card);
  });
},

    getComicDetails: function () {
    const id = localStorage.getItem('selectedComicId');
    if (!id) return console.error('No comic ID found in localStorage');

    // Get comic details
    RestClient.get('comics/' + id, function (comic) {
        // Get user's library
        RestClient.get("library/user", function (library) {
            // Get user's wishlist
            RestClient.get("wishlist/user", function (wishlist) {
                // Get all reviews for this comic
                RestClient.get(`reviews/comic/${id}`, function (reviews) {
                    // Get current logged-in user
                    RestClient.get("user/me", function (currentUser) {
                        const isInLibrary = library.some(lib => lib.id === comic.id);
                        const isInWishlist = wishlist.some(wish => wish.id === comic.id);

                        // Find current user's review, if it exists
                        const userReview = reviews.find(r => r.username === currentUser.username);

                        comic.reviews = reviews;

                        // Pass userReview to rendering function
                        ComicService.renderComicDetails(comic, isInLibrary, isInWishlist, userReview);
                    }, function (xhr, status, error) {
                        console.error("Failed to fetch current user:", error);
                    });
                }, function (xhr, status, error) {
                    console.error("Failed to fetch reviews:", error);
                });
            }, function (xhr, status, error) {
                console.error("Failed to fetch wishlist:", error);
            });
        }, function (xhr, status, error) {
            console.error("Failed to fetch library:", error);
        });
    }, function (xhr, status, error) {
        console.error("Failed to fetch comic details:", error);
    });
}

    ,
    helperFunctions: {
    navigateToComicDetails: function (comicId) {
        // Save to localStorage
        localStorage.setItem('selectedComicId', comicId);

        // Navigate manually
    setTimeout(() => {
            window.location.hash = '#comic-details';
            location.reload();
        }, 4); //
    },

    // Add more helper functions as needed
    },


        renderComicDetails: function (comic,isInLibrary = false, isInWishlist = false,userReview = null) {

            var rating = comic.rating || 4.4; // Default rating if not provided
            const container = document.querySelector(".container");
            container.innerHTML = `
                <div class="card mb-4">
                    <div class="row g-0">
                        <div class="col-md-3">
                            <img src="${comic.cover_image}" class="img-fluid rounded-start" alt="Comic Cover">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h3 class="card-title">${comic.title}</h3>
                                <p class="text-muted">By ${comic.author}</p>
                                <p><strong>Genre:</strong> ${comic.genre}</p>
                                <p><strong>Chapters:</strong> ${comic.total_chapters}</p>
                                <p><strong>Rating:</strong>  
                                    <span class="text-warning">
                                        ${'★'.repeat(Math.floor(rating))}${'☆'.repeat(5 - Math.floor(rating))}
                                    </span> (${rating}/5)
                                </p>
                                ${!isInLibrary 
                                ? `<button class="btn btn-primary" onclick="ComicService.addToLibrary(${comic.id})">Add to Library</button>` 
                                : `<button class="btn btn-danger" onclick="ComicService.removeFromLibrary(${comic.id})">Remove from Library</button>`
                                }
                            
                                ${!isInLibrary
                                    ? (isInWishlist
                                        ? `<button class="btn btn-secondary" onclick="ComicService.removeFromWishlist(${comic.id})">Remove from Wishlist</button>` 
                                        : `<button class="btn btn-secondary" onclick="ComicService.addToWishlist(${comic.id})">Add to Wishlist</button>`)
                                    : ''
                                }

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
        <div class="card-header">
            <h4>Reviews</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                
            <textarea id="reviewText" class="form-control" placeholder="Write a review...">${userReview ? userReview.comment : ''}</textarea>

        <select id="reviewRating" class="form-select mt-2">
            ${[5, 4, 3, 2, 1].map(num => `
                <option value="${num}" ${userReview && userReview.rating == num ? 'selected' : ''}>
                    ${'★'.repeat(num)}${'☆'.repeat(5 - num)} (${num})
                </option>
            `).join('')}
        </select>

<button class="btn btn-${userReview ? 'warning' : 'success'} mt-2" 
        onclick="ComicService.${userReview ? 'updateReview' : 'submitReview'}(${comic.id}, ${userReview?.id})">
    ${userReview ? 'Update Review' : 'Submit Review'}
</button>

${userReview ? `<button class="btn btn-danger mt-2 ms-2" onclick="ComicService.deleteReview(${comic.id}, ${userReview.id})">Delete Review</button>` : ''}

            </div>
            ${(comic.reviews || []).map(review => `
                <div class="border p-3 mb-2">
                    <strong> ${review.username}:</strong>
                    <span class="text-warning">${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}</span>
                    <p>${review.comment}</p>
                </div>
            `).join('')}
        </div>
    </div>

            `;
        },
        getLibraryComics: function () {
        RestClient.get("library/user", function (data) {
            if (!data || data.length === 0) {
                document.querySelector(".row-cols-xl-4").innerHTML = "<p>No comics in your library.</p>";
                return;
            }
            

            ComicService.renderComicCards(data);
            
            // Reload once only when loading library comics
            if (!sessionStorage.getItem('libraryReloaded')) {
                sessionStorage.setItem('libraryReloaded', 'true');
                location.reload();
            } else {
                sessionStorage.removeItem('libraryReloaded');
            }
        

        }, function (xhr, status, error) {
            console.error('Error fetching user library comics:', error);
        });
    },

    addToLibrary: function(comicId) {
        RestClient.post(`library/${comicId}`, {}, function(response) {
            toastr.success('Comic added to your library!');
            console.log('Comic added successfully:', response);

            // Automatically remove from wishlist if it exists
            ComicService.removeFromWishlist(comicId);

            ComicService.getComicDetails(); // refresh UI
        }, function(xhr, status, error) {
            console.error('Error adding comic to library:', error);
        });
    }
    ,


    removeFromLibrary: function(comicId) {
        RestClient.delete(`library/${comicId}`, {}, function(response) {

            console.log('Comic removed:', response);
            // Show success message
            toastr.info('Comic removed from your library!');
            
            // Optionally refresh or update the UI
            ComicService.getComicDetails(); // reload comic details
        }, function(xhr, status, error) {
            console.error('Error removing comic from library:', error);
            alert('Failed to remove comic from library.');
        });
    },


    getWishlistComics: function () {
        RestClient.get("wishlist/user", function (data) {
            if (!data || data.length === 0) {
                document.querySelector(".row-cols-xl-4").innerHTML = "<p>No comics in your wishlist.</p>";
                return;
            }
            

            ComicService.renderComicCards(data);
            
            // Reload once only when loading library comics
            if (!sessionStorage.getItem('wishlistReloaded')) {
                sessionStorage.setItem('wishlistReloaded', 'true');
                location.reload();
            } else {
                sessionStorage.removeItem('wishlistReloaded');
            }
        

        }, function (xhr, status, error) {
            console.error('Error fetching user wishlist comics:', error);
        });
    },
    addToWishlist: function(comicId) {
        RestClient.post(`wishlist/${comicId}`, {}, function(response) {
            toastr.success('Comic added to your wishlist!');
            console.log('Comic added to wishlist successfully:', response);
            ComicService.getComicDetails(); // reload to update button

        }, function(xhr, status, error) {
            toastr.error('Failed to add comic to wishlist.');
            console.error(error);
        });
    },
    removeFromWishlist: function(comicId) {
        RestClient.delete(`wishlist/${comicId}`, {}, function(response) {
            toastr.success('Comic removed from your wishlist!');
            console.log('Comic removed from wishlist successfully:', response);
            ComicService.getComicDetails();
        }, function(xhr, status, error) {
            toastr.error('Failed to remove comic from wishlist.');
            console.error(error);
        });
    },

    submitReview: function(comicId) {
        const comment = document.getElementById("reviewText").value.trim();
        const rating = document.getElementById("reviewRating").value;

        if (!comment) {
            toastr.warning("Please write a comment before submitting.");
            return;
        }

        RestClient.post(`reviews/comic/${comicId}`, { comment, rating }, function(response) {
            toastr.success("Review submitted!");
             ComicService.addToLibrary(comicId);
            ComicService.getComicDetails(); // refresh with new review
        }, function(xhr, status, error) {
            console.error("Failed to submit review:", error);
            toastr.error("Could not submit review.");
        });
    },

    updateReview: function(comicId) {
    const comment = document.getElementById("reviewText").value.trim();
    const rating = document.getElementById("reviewRating").value;

    if (!comment) {
        toastr.warning("Please write a comment before updating.");
        return;
    }

    RestClient.put(`reviews/comic/${comicId}`, { comment, rating }, function(response) {
        toastr.success("Review updated!");
        ComicService.getComicDetails(); // Refresh details
    }, function(xhr, status, error) {
        console.error("Failed to update review:", error);
        toastr.error("Could not update review.");
    });
}
,
deleteReview: function(comicId) {
    if (!confirm("Are you sure you want to delete your review?")) return;

    RestClient.delete(`reviews/comic/${comicId}`,{}, function(response) {
        toastr.success("Review deleted!");
        ComicService.getComicDetails(); // Refresh details
    }, function(xhr, status, error) {   
        console.error("Failed to delete review:", error);
        toastr.error("Could not delete review.");
    });
},

  openModal: function (comic = null) {
    if (comic) {
      $('#comic-id').val(comic.id);
      $('#comic-title').val(comic.title);
      $('#comic-author').val(comic.author);
      $('#comic-genre').val(comic.genre);
      $('#comic-chapters').val(comic.total_chapters);
      $('#comic-cover').val(comic.cover_image);
    } else {
      $('#comic-form')[0].reset();
      $('#comic-id').val('');
    }
    const modal = new bootstrap.Modal(document.getElementById('comicModal'));
    modal.show();
  },
saveComic: function () {
  const comicData = {
    title: $('#comic-title').val(),
    author: $('#comic-author').val(),
    genre: $('#comic-genre').val(),
    total_chapters: $('#comic-chapters').val(),
    cover_image: $('#comic-cover').val()
  };
  const id = $('#comic-id').val();

  if (id) {
    RestClient.put(`comics/${id}`, comicData, function(response) {
      ComicService.getAllComics();
        toastr.success('Comic updated successfully!');
      bootstrap.Modal.getInstance(document.getElementById('comicModal')).hide();
    }, function(xhr, status, error) {
      console.error('Failed to update comic:', error);
      toastr.error('Failed to update comic.');
      alert('Failed to update comic.');
    });
  } else {
    RestClient.post(`comics`, comicData, function(response) {
      ComicService.getAllComics();
        toastr.success('Comic added successfully!');
      bootstrap.Modal.getInstance(document.getElementById('comicModal')).hide();
    }, function(xhr, status, error) {
      console.error('Failed to add comic:', error);
        toastr.error('Failed to add comic.');
      alert('Failed to add comic.');
    });
  }
},

editComic: function (id) {
  RestClient.get(`comics/${id}`, function (comic) {
    ComicService.openModal(comic);

    toastr.info('Comic details loaded for editing.');
  }, function(xhr, status, error) {
    // Handle error when fetching comic details for editing
    console.error('Failed to fetch comic for edit:', error);
    alert('Failed to fetch comic details.');
    toastr.error('Failed to fetch comic details.');
  });
},

deleteComic: function (id) {
  if (confirm('Are you sure you want to delete this comic?')) {
    RestClient.delete(`comics/${id}`, {}, function(response) {
      ComicService.getAllComics();
        toastr.success('Comic deleted successfully!');
    }, function(xhr, status, error) {
      console.error('Failed to delete comic:', error);
        toastr.error('Failed to delete comic.');
      alert('Failed to delete comic.');
    });
  }
}


    };
// Export the ComicService object for use in other modules          