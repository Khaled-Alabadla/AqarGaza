document.addEventListener('DOMContentLoaded', () => {
    const propertyGrid = document.querySelector('.property-grid');
    const searchButton = document.querySelector('.btn-search');
    const paginationDotsContainer = document.querySelector('.pagination');
    const selectBoxes = document.querySelectorAll('.select-box');

    const properties = [
        {
            id: 1,
            image: '../../assets/img/landing.jpg',
            price: '1250.00',
            location: 'القدس، فلسطين',
            city: 'القدس',
            beds: 3,
            baths: 2,
            area: 150,
            status: 'مستقل بموقع بناء خاص بي',
            type: 'sale',
            propertyType: 'منزل',
            currency: 'USD',
        },
        {
            id: 2,
            image: '../../assets/img/landing.jpg',
            price: '980.00',
            location: 'غزة، فلسطين',
            city: 'غزة',
            beds: 2,
            baths: 1,
            area: 120,
            status: 'شقة سكنية عصرية',
            type: 'rent',
            propertyType: 'شقة',
            currency: 'USD',
        },
        {
            id: 3,
            image: '../../assets/img/landing.jpg',
            price: '1500.00',
            location: 'رام الله، فلسطين',
            city: 'رام الله',
            beds: 4,
            baths: 3,
            area: 200,
            status: 'فيلا فاخرة بحمام سباحة',
            type: 'sale',
            propertyType: 'فيلا',
            currency: 'USD',
        },
        {
            id: 4,
            image: '../../assets/img/landing.jpg',
            price: '750.00',
            location: 'نابلس، فلسطين',
            city: 'نابلس',
            beds: 2,
            baths: 1,
            area: 90,
            status: 'شقة للإيجار الشهري',
            type: 'rent',
            propertyType: 'شقة',
            currency: 'ILS',
        },
        {
            id: 5,
            image: '../../assets/img/landing.jpg',
            price: '1100.00',
            location: 'الخليل، فلسطين',
            city: 'الخليل',
            beds: 3,
            baths: 2,
            area: 160,
            status: 'منزل عائلي كبير',
            type: 'sale',
            propertyType: 'منزل',
            currency: 'JOD',
        },
        {
            id: 6,
            image: '../../assets/img/landing.jpg',
            price: '600.00',
            location: 'بيت لحم، فلسطين',
            city: 'بيت لحم',
            beds: 1,
            baths: 1,
            area: 60,
            status: 'استوديو مفروش',
            type: 'rent',
            propertyType: 'استوديو',
            currency: 'ILS',
        },
        {
            id: 7,
            image: '../../assets/img/landing.jpg',
            price: '1300.00',
            location: 'يافا، فلسطين',
            city: 'يافا',
            beds: 4,
            baths: 2,
            area: 180,
            status: 'منزل بتصميم عصري',
            type: 'sale',
            propertyType: 'منزل',
            currency: 'USD',
        },
        {
            id: 8,
            image: '../../assets/img/landing.jpg',
            price: '950.00',
            location: 'حيفا، فلسطين',
            city: 'حيفا',
            beds: 3,
            baths: 2,
            area: 140,
            status: 'شقة مطلة على البحر',
            type: 'rent',
            propertyType: 'شقة',
            currency: 'ILS',
        },
        {
            id: 9,
            image: '../../assets/img/landing.jpg',
            price: '1700.00',
            location: 'عكا، فلسطين',
            city: 'عكا',
            beds: 5,
            baths: 4,
            area: 250,
            status: 'قصر تاريخي',
            type: 'sale',
            propertyType: 'فيلا',
            currency: 'JOD',
        },
        {
            id: 10,
            image: '../../assets/img/landing.jpg',
            price: '800.00',
            location: 'خانيونس، فلسطين',
            city: 'خانيونس',
            beds: 2,
            baths: 1,
            area: 110,
            status: 'شقة سكنية',
            type: 'rent',
            propertyType: 'شقة',
            currency: 'USD',
        },
        {
            id: 11,
            image: '../../assets/img/landing.jpg',
            price: '1600.00',
            location: 'رفح، فلسطين',
            city: 'رفح',
            beds: 3,
            baths: 2,
            area: 170,
            status: 'منزل مستقل',
            type: 'sale',
            propertyType: 'منزل',
            currency: 'USD',
        },
        {
            id: 12,
            image: '../../assets/img/landing.jpg',
            price: '700.00',
            location: 'الوسطى، فلسطين',
            city: 'الوسطى',
            beds: 2,
            baths: 1,
            area: 100,
            status: 'شقة للإيجار',
            type: 'rent',
            propertyType: 'شقة',
            currency: 'ILS',
        },
        {
            id: 13,
            image: '../../assets/img/landing.jpg',
            price: '1050.00',
            location: 'شمال غزة، فلسطين',
            city: 'شمال غزة',
            beds: 3,
            baths: 2,
            area: 130,
            status: 'منزل صغير',
            type: 'sale',
            propertyType: 'منزل',
            currency: 'JOD',
        },
    ];

    const propertiesPerPage = 9; // 3 rows * 3 columns
    let currentPage = 1;
    let filteredProperties = [...properties]; // Start with all properties

    function renderProperties(page, propertiesToDisplay) {
        propertyGrid.innerHTML = '';
        const start = (page - 1) * propertiesPerPage;
        const end = start + propertiesPerPage;
        const currentProperties = propertiesToDisplay.slice(start, end);

        if (currentProperties.length === 0) {
            propertyGrid.innerHTML = '<p style="text-align: center; width: 100%; padding: 20px;">لا توجد عقارات مطابقة لعملية البحث.</p>';
            paginationDotsContainer.innerHTML = ''; // Clear pagination if no results
            return;
        }

        currentProperties.forEach((property) => {
            const card = document.createElement('div');
            card.classList.add('property-card');
            card.innerHTML = `
                <div class="property-image">
                    <img src="${property.image}" alt="Property Image">
                    <button class="favorite-btn" data-id="${property.id}"><i class="far fa-heart"></i></button>
                </div>
                <div class="property-info">
                    <span class="property-price">${property.price}$</span>
                    <p class="property-location">${property.location}</p>
                    <div class="property-features">
                        <div><i class="fas fa-bed"></i> <span>${property.beds} غرف</span></div>
                        <div><i class="fas fa-bath"></i> <span>${property.baths} حمام</span></div>
                        <div><i class="fas fa-ruler-combined"></i> <span>${property.area} م²</span></div>
                    </div>
                    <p class="property-status">${property.status}</p>
                </div>
            `;
            propertyGrid.appendChild(card);
        });

        // Add event listeners to favorite buttons
        document.querySelectorAll('.favorite-btn').forEach((button) => {
            button.addEventListener('click', (event) => {
                const icon = event.currentTarget.querySelector('i');
                icon.classList.toggle('far'); // Outline heart
                icon.classList.toggle('fas'); // Solid heart
                console.log(`Property ${event.currentTarget.dataset.id} favorited status changed!`);
            });
        });
    }

    function setupPagination(propertiesToDisplay) {
        const totalPages = Math.ceil(propertiesToDisplay.length / propertiesPerPage);
        paginationDotsContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const dot = document.createElement('span');
            dot.classList.add('pagination-dot');
            if (i === currentPage) {
                dot.classList.add('active');
            }
            dot.dataset.page = i;
            dot.addEventListener('click', (event) => {
                currentPage = parseInt(event.target.dataset.page);
                renderProperties(currentPage, propertiesToDisplay);
                updatePaginationDots();
            });
            paginationDotsContainer.appendChild(dot);
        }
    }

    function updatePaginationDots() {
        document.querySelectorAll('.pagination-dot').forEach((dot) => {
            dot.classList.remove('active');
            if (parseInt(dot.dataset.page) === currentPage) {
                dot.classList.add('active');
            }
        });
    }

    // Filter Dropdown Functionality
    selectBoxes.forEach((selectBox) => {
        const selectedOption = selectBox.querySelector('.selected-option');
        const optionsContainer = selectBox.querySelector('.options-container');
        const options = optionsContainer.querySelectorAll('.option');

        selectedOption.addEventListener('click', () => {
            // Close other open select boxes
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
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!selectBox.contains(event.target)) {
                selectBox.classList.remove('active');
            }
        });
    });

    // Search functionality
    searchButton.addEventListener('click', () => {
        const selectedType = document.querySelector('.select-box[data-filter-name="type"]').dataset.selectedValue;
        const selectedPropertyType = document.querySelector('.select-box[data-filter-name="propertyType"]').dataset.selectedValue;
        const selectedCity = document.querySelector('.select-box[data-filter-name="city"]').dataset.selectedValue;
        const selectedArea = document.querySelector('.select-box[data-filter-name="area"]').dataset.selectedValue;
        const selectedCurrency = document.querySelector('.select-box[data-filter-name="currency"]').dataset.selectedValue;
        const selectedPriceRange = document.querySelector('.select-box[data-filter-name="price"]').dataset.selectedValue;

        filteredProperties = properties.filter((property) => {
            const matchesType = !selectedType || selectedType === 'all' || property.type === selectedType;
            const matchesPropertyType = !selectedPropertyType || selectedPropertyType === 'all' || property.propertyType === selectedPropertyType;
            const matchesCity = !selectedCity || selectedCity === 'all' || property.city === selectedCity;
            const matchesCurrency = !selectedCurrency || selectedCurrency === 'all' || property.currency === selectedCurrency;

            // Area filtering
            let matchesArea = true;
            if (selectedArea && selectedArea !== 'all') {
                const [minArea, maxArea] = selectedArea.includes('+')
                    ? [parseInt(selectedArea.replace('+', '')), Infinity]
                    : selectedArea.split('-').map(Number);
                matchesArea = property.area >= minArea && property.area <= maxArea;
            }

            // Price filtering
            let matchesPrice = true;
            if (selectedPriceRange && selectedPriceRange !== 'all') {
                const [minPrice, maxPrice] = selectedPriceRange.includes('+')
                    ? [parseInt(selectedPriceRange.replace('+', '')), Infinity]
                    : selectedPriceRange.split('-').map(Number);
                const propertyPrice = parseFloat(property.price); // Ensure price is a number
                matchesPrice = propertyPrice >= minPrice && propertyPrice <= maxPrice;
            }

            return matchesType && matchesPropertyType && matchesCity && matchesCurrency && matchesArea && matchesPrice;
        });

        currentPage = 1; // Reset to first page for new filter results
        renderProperties(currentPage, filteredProperties);
        setupPagination(filteredProperties);
    });

    // Initial render
    renderProperties(currentPage, filteredProperties);
    setupPagination(filteredProperties);
});
