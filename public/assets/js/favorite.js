document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const fabFavorites = document.querySelector('.fab-favorites');
    const favoritesSidebar = document.querySelector('.favorites-sidebar');
    const favoritesCloseBtn = document.querySelector('.favorites-close-btn');
    const favoritesListUl = document.querySelector('.favorites-list');
    const allFavoritesBtn = document.querySelector('.all-favorites-btn');
    const propertyGrid = document.querySelector('.property-grid');
    const noFavoritesMessage = document.querySelector('#no-favorites-message');
    const pagination = document.querySelector('.pagination');

    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    const currencyMap = {
        USD: 'دولار',
        JOD: 'دينار',
        ILS: 'شيكل',
    };

    // Function to show toast notification
    function showNotification(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        // Hide toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Shared function to toggle favorite status
    function toggleFavorite(propertyId, button) {
        const icon = button.querySelector('i');
        const isFavorited = icon.classList.contains('fas');

        fetch('/favorites', {
            method: isFavorited ? 'DELETE' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                Accept: 'application/json',
            },
            body: JSON.stringify({ property_id: propertyId }),
        })
            .then((response) => {
                if (response.status === 401) {
                    showNotification('يرجى تسجيل الدخول لتعديل المفضلة', 'error');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                    return;
                }
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                console.log('Toggle favorite response:', data);
                if (data.success) {
                    // Show success notification
                    showNotification(data.message, 'success');
                    // Update all heart icons for this property
                    document.querySelectorAll(`.favorite-btn[data-id="${propertyId}"]`).forEach((btn) => {
                        const btnIcon = btn.querySelector('i');
                        btnIcon.classList.toggle('far', isFavorited);
                        btnIcon.classList.toggle('fas', !isFavorited);
                        btn.classList.toggle('active', !isFavorited);
                    });
                    // Update favorites modal if open
                    if (favoritesSidebar && favoritesSidebar.getAttribute('aria-hidden') === 'false') {
                        updateFavoritesModal();
                    }
                    // Update favorites page if on /favorites
                    if (window.location.pathname.includes('/favorites')) {
                        updateFavoritesPage();
                    }
                } else {
                    showNotification(data.message || 'حدث خطأ. يرجى المحاولة مرة أخرى.', 'error');
                }
            })
            .catch((error) => {
                console.error('Error toggling favorite:', error);
                showNotification('حدث خطأ أثناء تحديث المفضلة.', 'error');
            });
    }

    // Attach favorite button handlers
    function attachFavoriteButtonHandlers() {
        document.querySelectorAll('.favorite-btn').forEach((button) => {
            button.removeEventListener('click', handleFavoriteClick); // Prevent duplicate listeners
            button.addEventListener('click', handleFavoriteClick);
        });
    }

    function handleFavoriteClick(event) {
        event.stopPropagation(); // Prevent card click from triggering
        const propertyId = this.dataset.id;
        toggleFavorite(propertyId, this);
    }

    // Update favorites modal
    function updateFavoritesModal() {
        if (!favoritesListUl) {
            console.error('Favorites list element not found');
            return;
        }
        favoritesListUl.innerHTML = '<p>جارٍ تحميل المفضلة...</p>';

        fetch('/api/favorites', {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                favoritesListUl.innerHTML = '';
                if (data.favorites.length === 0) {
                    favoritesListUl.innerHTML =
                        '<p style="text-align: center; padding: 20px; color: var(--text-color-light);">لا توجد عقارات في المفضلة.</p>';
                } else {
                    const fragment = document.createDocumentFragment();
                    data.favorites.data.forEach((property) => {
                        const li = document.createElement('a');
                        li.className = 'favorites-item';
                        li.setAttribute('href', `properties/${property.id}`);
                        li.dataset.propertyId = property.id;
                        li.innerHTML = `
                            <div class="favorite-item" >
                                <img src="http://localhost:8000/${property.main_image}" alt="${property.title}" class="favorites-item-image">
                                <div class="favorite-info">
                                    <h4>${property.title}</h4>
                                    <p>${Number(property.price).toLocaleString()} ${currencyMap[property.currency]}</p>

                                </div>
                            </div>
                        `;
                        fragment.appendChild(li);
                    });
                    favoritesListUl.appendChild(fragment);
                    attachFavoriteButtonHandlers();
                }
            })
            .catch((error) => {
                console.error('Error fetching favorites:', error);
                favoritesListUl.innerHTML =
                    '<p style="text-align: center; padding: 20px; color: var(--text-color-light);">حدث خطأ أثناء تحميل المفضلة.</p>';
            });
    }

    // Update favorites page
    function updateFavoritesPage(page = 1) {
        if (!propertyGrid || !noFavoritesMessage || !pagination) {
            console.error('Missing DOM elements:', { propertyGrid, noFavoritesMessage, pagination });
            return;
        }
        propertyGrid.innerHTML = '<p>جارٍ تحميل العقارات...</p>';

        fetch(`/api/favorites?page=${page}`, {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                console.log('Favorites page response:', data);
                propertyGrid.innerHTML = '';
                if (data.favorites.data.length === 0) {
                    noFavoritesMessage.style.display = 'block';
                    pagination.innerHTML = '';
                } else {
                    noFavoritesMessage.style.display = 'none';
                    const fragment = document.createDocumentFragment();
                    data.favorites.data.forEach((property) => {
                        const card = document.createElement('div');
                        card.className = 'card';
                        card.innerHTML = `
                            <div class="property-card">
                                <div class="property-image">
                                    <img src="${property.main_image}" alt="Property Image">
                                    <button class="favorite-btn" data-id="${property.id}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="property-info">
                                    <h2 class="property-name">${property.title}</h2>
                                    <span class="property-price">${Number(property.price).toLocaleString()} ${currencyMap[property.currency]}</span>
                                    <p class="property-type">${property.type === 'rent' ? 'تأجير' : 'بيع'}</p>
                                    <p class="property-location">${property.city.name}، ${property.zone.name}</p>
                                    <div class="property-features">
                                        ${property.rooms ? `<div><i class="fas fa-bed"></i> <span>${property.rooms} غرف</span></div>` : ''}
                                        ${property.bathrooms ? `<div><i class="fas fa-bath"></i> <span>${property.bathrooms} ${property.bathrooms === 1 ? 'حمام' : 'حمامات'}</span></div>` : ''}
                                        ${property.area ? `<div><i class="fas fa-ruler-combined"></i> <span>${property.area} م²</span></div>` : ''}
                                    </div>
                                    <a class="btn_card" href="/properties/${property.id}">المزيد من التفاصيل</a>
                                </div>
                            </div>
                        `;
                        fragment.appendChild(card);
                    });
                    propertyGrid.appendChild(fragment);
                    attachFavoriteButtonHandlers();

                    // Render pagination
                    pagination.innerHTML = '';
                    const totalPages = data.favorites.last_page;
                    const currentPage = data.favorites.current_page;
                    if (totalPages > 1) {
                        const nav = document.createElement('nav');
                        nav.setAttribute('aria-label', 'Pagination');
                        const ul = document.createElement('ul');
                        ul.className = 'pagination-list';
                        for (let i = 1; i <= totalPages; i++) {
                            const li = document.createElement('li');
                            li.innerHTML = `<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
                            ul.appendChild(li);
                        }
                        nav.appendChild(ul);
                        pagination.appendChild(nav);

                        document.querySelectorAll('.page-btn').forEach((btn) => {
                            btn.addEventListener('click', () => {
                                updateFavoritesPage(btn.dataset.page);
                            });
                        });
                    }
                }
            })
            .catch((error) => {
                console.error('Error fetching favorites:', error);
                propertyGrid.innerHTML = '<p>حدث خطأ أثناء تحميل العقارات.</p>';
                noFavoritesMessage.style.display = 'none';
            });
    }

    // Toggle favorites sidebar
    if (fabFavorites && favoritesSidebar && favoritesCloseBtn) {
        fabFavorites.addEventListener('click', (event) => {
            event.stopPropagation();
            favoritesSidebar.classList.toggle('open');
            favoritesSidebar.setAttribute('aria-hidden', favoritesSidebar.classList.contains('open') ? 'false' : 'true');
            if (favoritesSidebar.classList.contains('open')) {
                updateFavoritesModal(); // Populate modal when opened
            }
        });

        favoritesCloseBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            favoritesSidebar.classList.remove('open');
            favoritesSidebar.setAttribute('aria-hidden', 'true');
        });

        document.addEventListener('click', (event) => {
            if (!favoritesSidebar.contains(event.target) && !fabFavorites.contains(event.target) && favoritesSidebar.classList.contains('open')) {
                favoritesSidebar.classList.remove('open');
                favoritesSidebar.setAttribute('aria-hidden', 'true');
            }
        });
    }

    // Handle all favorites button
    if (allFavoritesBtn) {
        allFavoritesBtn.addEventListener('click', (event) => {
            event.preventDefault();
            window.location.href = allFavoritesBtn.getAttribute('href');
            favoritesSidebar.classList.remove('open');
            favoritesSidebar.setAttribute('aria-hidden', 'true');
        });
    }

    // Initialize favorites page if present
    if (window.location.pathname.includes('/favorites')) {
        updateFavoritesPage();
    }

    // Expose toggleFavorite for other scripts
    window.toggleFavorite = toggleFavorite;

    // Initialize favorite buttons
    attachFavoriteButtonHandlers();
});
