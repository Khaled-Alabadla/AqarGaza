document.addEventListener('DOMContentLoaded', () => {
    const selectBoxes = document.querySelectorAll('.select-box');
    const propertyGrid = document.querySelector('.property-grid');
    const paginationContainer = document.querySelector('.pagination') || document.createElement('div');
    paginationContainer.classList.add('pagination');
    const zoneSelectBox = document.querySelector('.select-box[data-filter-name="zone"]');
    const zoneOptionsContainer = zoneSelectBox.querySelector('.options-container');
    const citySelectBox = document.querySelector('.select-box[data-filter-name="city"]');

    // Read initial query parameters
    const urlParams = new URLSearchParams(window.location.search);
    const initialFilters = {
        type: urlParams.get('type') || 'all',
        propertyType: urlParams.get('propertyType') || 'all',
        city: urlParams.get('city') || 'all',
        zone: urlParams.get('zone') || 'all',
        area: urlParams.get('area') || 'all',
        currency: urlParams.get('currency') || 'all',
        price: urlParams.get('price') || 'all',
    };

    // Initialize dropdowns with query parameters
    selectBoxes.forEach((selectBox) => {
        const filterName = selectBox.dataset.filterName;
        const selectedValue = initialFilters[filterName];
        selectBox.dataset.selectedValue = selectedValue;
        const selectedOption = selectBox.querySelector('.selected-option');
        const options = selectBox.querySelectorAll('.option');
        options.forEach((option) => {
            if (option.dataset.value === selectedValue) {
                selectedOption.textContent = option.textContent;
                option.classList.add('selected');
            } else {
                option.classList.remove('selected');
            }
        });
    });

    // Fetch properties with current filters
    function fetchProperties(url = '/properties') {
        const filters = {};
        selectBoxes.forEach((selectBox) => {
            filters[selectBox.dataset.filterName] = selectBox.dataset.selectedValue || 'all';
        });

        const queryString = new URLSearchParams(filters).toString();
        const fetchUrl = queryString ? `${url}?${queryString}` : url;

        fetch(fetchUrl, {
            method: 'GET',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                propertyGrid.innerHTML = '';
                if (data.properties.data.length === 0) {
                    propertyGrid.innerHTML = '<p>لا توجد عقارات مطابقة للفلاتر المحددة.</p>';
                } else {
                    data.properties.data.forEach((property) => {
                        const card = document.createElement('div');
                        card.className = 'card';
                        card.innerHTML = `
                            <div class="property-card">
                                <div class="property-image">
                                    <img src="${property.main_image}" alt="Property Image">
                                    <button class="favorite-btn" data-id="${property.id}">
                                        <i class="${property.is_favorited ? 'fas' : 'far'} fa-heart"></i>
                                    </button>
                                </div>
                                <div class="property-info">
                                    <h2 class="property-name">${property.title}</h2>
                                    <span class="property-price">${Number(property.price).toLocaleString()}
                                        ${
                                            property.currency === 'USD'
                                                ? '<span class="currency">دولار</span>'
                                                : property.currency === 'ILS'
                                                  ? '<span class="currency">شيكل</span>'
                                                  : property.currency === 'JOD'
                                                    ? '<span class="currency">دينار</span>'
                                                    : ''
                                        }
                                    </span>
                                    <p class="property-type">
                                        ${
                                            property.type === 'rent'
                                                ? '<span class="badge badge-rent">إيجار</span>'
                                                : property.type === 'sale'
                                                  ? '<span class="badge badge-sale">بيع</span>'
                                                  : ''
                                        }
                                    </p>
                                    <p class="property-location">${property.city.name}، ${property.zone.name}</p>
                                    <div class="property-features">
                                        ${property.rooms ? `<div><i class="fas fa-bed"></i> <span>${property.rooms} غرف</span></div>` : ''}
                                        ${property.bathrooms ? `<div><i class="fas fa-bath"></i> <span>${property.bathrooms} حمامات</span></div>` : ''}
                                        <div><i class="fas fa-ruler-combined"></i> <span>${property.area} م²</span></div>
                                    </div>
                                    <a class="btn_card" href="/properties/${property.id}">المزيد من التفاصيل</a>
                                </div>
                            </div>
                        `;
                        propertyGrid.appendChild(card);
                    });
                }

                paginationContainer.innerHTML = data.properties.links;

                // Re-attach favorite button listeners
                document.querySelectorAll('.favorite-btn').forEach((button) => {
                    button.addEventListener('click', function () {
                        const propertyId = this.dataset.id;
                        const icon = this.querySelector('i');
                        const isFavorited = icon.classList.contains('fas');

                        fetch('/favorites', {
                            method: isFavorited ? 'DELETE' : 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                Accept: 'application/json',
                            },
                            body: JSON.stringify({ property_id: propertyId }),
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                if (data.success) {
                                    icon.classList.toggle('far', isFavorited);
                                    icon.classList.toggle('fas', !isFavorited);
                                } else {
                                    alert('حدث خطأ. يرجى تسجيل الدخول أو المحاولة مرة أخرى.');
                                }
                            })
                            .catch((error) => {
                                console.error('Error toggling favorite:', error);
                                alert('حدث خطأ أثناء تحديث المفضلة.');
                            });
                    });
                });
            })
            .catch((error) => {
                console.error('Error fetching properties:', error);
                propertyGrid.innerHTML = '<p>حدث خطأ أثناء تحميل العقارات. يرجى المحاولة لاحقًا.</p>';
            });
    }

    // Fetch zones based on city selection
    function fetchZones(cityId) {
        zoneOptionsContainer.innerHTML = '<div class="option" data-value="all">الكل</div>';
        zoneSelectBox.querySelector('.selected-option').textContent = 'المنطقة';
        zoneSelectBox.dataset.selectedValue = 'all';

        if (cityId === 'all') {
            fetchProperties();
            return;
        }

        fetch(`/api/zones/${cityId}`, {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                data.forEach((zone) => {
                    const option = document.createElement('div');
                    option.className = 'option';
                    option.dataset.value = zone.id;
                    option.textContent = zone.name;
                    zoneOptionsContainer.appendChild(option);
                    option.addEventListener('click', () => {
                        zoneSelectBox.querySelector('.selected-option').textContent = option.textContent;
                        zoneSelectBox.dataset.selectedValue = option.dataset.value;
                        zoneSelectBox.querySelectorAll('.option').forEach((opt) => opt.classList.remove('selected'));
                        option.classList.add('selected');
                        zoneSelectBox.classList.remove('active');
                        fetchProperties();
                    });
                });
                fetchProperties();
            })
            .catch((error) => console.error('Error fetching zones:', error));
    }

    // Handle dropdown selections
    selectBoxes.forEach((selectBox) => {
        const selectedOption = selectBox.querySelector('.selected-option');
        const optionsContainer = selectBox.querySelector('.options-container');
        const options = optionsContainer.querySelectorAll('.option');

        selectedOption.addEventListener('click', () => {
            selectBoxes.forEach((otherSelectBox) => {
                if (otherSelectBox !== selectBox) {
                    otherSelectBox.querySelector('.options-container').classList.remove('active');
                }
            });
            optionsContainer.classList.toggle('active');
        });

        options.forEach((option) => {
            option.addEventListener('click', () => {
                selectedOption.textContent = option.textContent;
                selectBox.dataset.selectedValue = option.dataset.value;
                options.forEach((opt) => opt.classList.remove('selected'));
                option.classList.add('selected');
                optionsContainer.classList.remove('active');
                if (selectBox.dataset.filterName === 'city') {
                    fetchZones(option.dataset.value);
                } else {
                    fetchProperties();
                }
            });
        });

        document.addEventListener('click', (event) => {
            if (!selectBox.contains(event.target)) {
                optionsContainer.classList.remove('active');
            }
        });
    });

    // Handle city link clicks
    document.querySelectorAll('.dropdown-menu a[data-city-id]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const cityId = link.dataset.cityId;
            citySelectBox.dataset.selectedValue = cityId;
            const cityOption = citySelectBox.querySelector(`.option[data-value="${cityId}"]`);
            if (cityOption) {
                citySelectBox.querySelector('.selected-option').textContent = cityOption.textContent;
                citySelectBox.querySelectorAll('.option').forEach((opt) => opt.classList.remove('selected'));
                cityOption.classList.add('selected');
            }
            fetchZones(cityId);
            // Update URL without reloading
            const url = new URL(window.location);
            url.searchParams.set('city', cityId);
            url.searchParams.delete('zone'); // Reset zone filter
            url.searchParams.delete('page'); // Reset pagination
            window.history.pushState({}, '', url);
        });
    });

    // Handle pagination clicks
    paginationContainer.addEventListener('click', (event) => {
        const target = event.target.closest('a');
        if (target) {
            event.preventDefault();
            fetchProperties(target.href);
        }
    });

    // Initialize zones if city is pre-selected
    if (initialFilters.city !== 'all') {
        fetchZones(initialFilters.city);
    } else {
        fetchProperties();
    }
});
