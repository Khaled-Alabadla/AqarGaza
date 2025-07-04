document.addEventListener('DOMContentLoaded', () => {
    const selectBoxes = document.querySelectorAll('.select-box');
    const propertyGrid = document.querySelector('.property-grid');
    const paginationContainer = document.querySelector('.pagination') || document.createElement('div');
    paginationContainer.classList.add('pagination');
    const zoneSelectBox = document.querySelector('.select-box[data-filter-name="zone"]');
    const zoneOptionsContainer = zoneSelectBox.querySelector('.options-container');

    function fetchProperties(url = '/properties') {
        const filters = {};
        selectBoxes.forEach((selectBox) => {
            filters[selectBox.dataset.filterName] = selectBox.dataset.selectedValue || 'all';
        });

        const queryString = new URLSearchParams(filters).toString();
        const fetchUrl = queryString ? `${url}?${queryString}` : url;
        console.log(fetchUrl);

        fetch(fetchUrl, {
            method: 'GET',
            headers: {
                Accept: 'application/json',
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
                data.properties.data.forEach((property) => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `
                    <div class="property-card">
                        <div class="property-image">
                            <img src="/Uploads/${property.main_image}" alt="Property Image">
                            <button class="favorite-btn" data-id="${property.id}">
                                <i class="${property.is_favorited ? 'fas' : 'far'} fa-heart"></i>
                            </button>
                        </div>
                        <div class="property-info">
                            <h2 class="property-name">${property.title}</h2>
                            <span class="property-price">${property.price}
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

                paginationContainer.innerHTML = data.properties.links;

                document.querySelectorAll('.favorite-btn').forEach((button) => {
                    button.addEventListener('click', (event) => {
                        const icon = event.currentTarget.querySelector('i');
                        icon.classList.toggle('far');
                        icon.classList.toggle('fas');
                    });
                });
            })
            .catch((error) => console.error('Error fetching properties:', error));
    }

    function fetchZones(cityId) {
        if (cityId === 'all') {
            zoneOptionsContainer.innerHTML = '<div class="option" data-value="all">الكل</div>';
            zoneSelectBox.querySelector('.selected-option').textContent = 'المنطقة';
            zoneSelectBox.dataset.selectedValue = 'all';
            fetchProperties();
            return;
        }

        fetch(`/api/zones/${cityId}`, {
            headers: {
                Accept: 'application/json',
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                zoneOptionsContainer.innerHTML = '<div class="option" data-value="all">الكل</div>';
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
                zoneSelectBox.querySelector('.selected-option').textContent = 'المنطقة';
                zoneSelectBox.dataset.selectedValue = 'all';
                fetchProperties();
            })
            .catch((error) => console.error('Error fetching zones:', error));
    }

    selectBoxes.forEach((selectBox) => {
        const selectedOption = selectBox.querySelector('.selected-option');
        const optionsContainer = selectBox.querySelector('.options-container');
        const options = optionsContainer.querySelectorAll('.option');

        selectedOption.addEventListener('click', () => {
            selectBoxes.forEach((otherSelectBox) => {
                if (otherSelectBox !== selectBox) {
                    otherSelectBox.classList.remove('active');
                }
            });
            selectBox.classList.toggle('active');
        });

        options.forEach((option) => {
            option.addEventListener('click', () => {
                selectedOption.textContent = option.textContent;
                selectBox.dataset.selectedValue = option.dataset.value;
                options.forEach((opt) => opt.classList.remove('selected'));
                option.classList.add('selected');
                selectBox.classList.remove('active');
                if (selectBox.dataset.filterName === 'city') {
                    fetchZones(option.dataset.value);
                } else {
                    fetchProperties();
                }
            });
        });

        document.addEventListener('click', (event) => {
            if (!selectBox.contains(event.target)) {
                selectBox.classList.remove('active');
            }
        });
    });

    paginationContainer.addEventListener('click', (event) => {
        const target = event.target.closest('a');
        if (target) {
            event.preventDefault();
            fetchProperties(target.href);
        }
    });

    fetchProperties();
});
