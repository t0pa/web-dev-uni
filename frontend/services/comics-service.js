let ComicService = {

    getAllComics: function () {
        RestClient.get("comics", function (data) {
            ComicService.renderComicCards(data);
        }, function (xhr, status, error) {
            console.error('Error fetching comics:', error);
        });
    },

    renderComicCards: function (comics) {
        const container = document.querySelector(".row-cols-xl-4");
        container.innerHTML = '';
        
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
                            <a class="btn btn-outline-dark mt-auto" onclick="ComicService.helperFunctions.navigateToComicDetails(${comic.id})">View options</a>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
           
        });
    },

    
 getComicDetails: function () {
      const id = localStorage.getItem('selectedComicId');

    if (!id) {
        console.error('No comic ID found in localStorage');
        return;
    }

        RestClient.get('comics/' + id, function (comic) {
        RestClient.get("library/user", function (library) {
            RestClient.get("wishlist", function (wishlist) {
                const isInLibrary = library.some(lib => lib.id === comic.id);
                const isInWishlist = wishlist.some(wish => wish.id === comic.id);
                ComicService.renderComicDetails(comic, isInLibrary, isInWishlist);
            });
        });
    });
    },

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


    renderComicDetails: function (comic,isInLibrary = false, isInWishlist = false) {

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
                            ${!isInLibrary || !isInWishlist
                            ? '<button class="btn btn-secondary" onclick="ComicService.addToWishlist">Add to Wishlist</button>' 
                            :'<button class="btn btn-secondary" onclick="ComicService.removeFromWishlist">Remove from Wishlist</button>' }            
                             
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
                        <input type="text" class="form-control" placeholder="Write a review...">
                        <button class="btn btn-success mt-2">Submit Review</button>
                    </div>

                    ${(comic.reviews || []).map(review => `
                        <div class="border p-3 mb-2">
                            <strong>${review.author}:</strong> 
                            <span class="text-warning">${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}</span>
                            <p>${review.content}</p>
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
        // Optionally refresh library view or update UI here
        ComicService.getComicDetails(); // reload comic details
    }, function(xhr, status, error) {
        console.error('Error adding comic to library:', error);
        alert('Failed to add comic to library.');
    });
},


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
        ComicService.getComicDetails(); // reload to update button
    }, function(xhr, status, error) {
        toastr.error('Failed to add comic to wishlist.');
        console.error(error);
    });
},
removeFromWishlist: function(comicId) {
    RestClient.delete(`wishlist/${comicId}`, {}, function(response) {
        toastr.success('Comic removed from your wishlist!');
        ComicService.getComicDetails();
    }, function(xhr, status, error) {
        toastr.error('Failed to remove comic from wishlist.');
        console.error(error);
    });
}

};
