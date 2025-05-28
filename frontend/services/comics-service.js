let ComicService = {
    getAllComics: function () {
        RestClient.get("comics", function (data) {
            ComicService.renderComicCards(data);
        }, function (xhr, status, error) {
            console.error('Error fetching comics:', error);
        },);
    },

    renderComicCards: function (comics) {
        const container = document.querySelector(".row-cols-xl-4");

        // Clear existing content
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
                            <a class="btn btn-outline-dark mt-auto" href="#comic-details?id=${comic.id}">View options</a>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }
};
