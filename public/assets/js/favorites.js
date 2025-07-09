document.addEventListener('DOMContentLoaded', () => {
    const propertyGrid = document.querySelector('.property-grid');
    const paginationContainer = document.querySelector('.pagination');
    const noFavoritesMessage = document.getElementById('no-favorites-message');

    // ** بيانات المفضلة الوهمية **
    // هذه البيانات تمثل العقارات المفضلة.
    // يمكنك استبدالها ببيانات من localStorage أو API لاحقاً.
    const favoritePropertiesData = [
        {
            id: 'fav-property-1',
            image: '../assets/img/landing.jpg',
            price: '1,250.00$',
            location: 'القدس، فلسطين',
            features: [
                { icon: 'fas fa-bed', text: '3 غرف' },
                { icon: 'fas fa-bath', text: '2 حمام' },
                { icon: 'fas fa-ruler-combined', text: '150 م²' },
            ],
            status: 'مستقل بموقع بناء خاص بي',
        },
        {
            id: 'fav-property-2',
            image: '../assets/img/landing.jpg',
            price: '980.00$',
            location: 'غزة، فلسطين',
            features: [
                { icon: 'fas fa-bed', text: '2 غرف' },
                { icon: 'fas fa-bath', text: '1 حمام' },
                { icon: 'fas fa-ruler-combined', text: '120 م²' },
            ],
            status: 'شقة سكنية عصرية',
        },
        {
            id: 'fav-property-3',
            image: '../assets/img/landing.jpg',
            price: '1,500.00$',
            location: 'رام الله، فلسطين',
            features: [
                { icon: 'fas fa-bed', text: '4 غرف' },
                { icon: 'fas fa-bath', text: '3 حمام' },
                { icon: 'fas fa-ruler-combined', text: '200 م²' },
            ],
            status: 'فيلا فاخرة بحمام سباحة',
        },
        {
            id: 'fav-property-4',
            image: '../assets/img/landing.jpg',
            price: '750.00$',
            location: 'نابلس، فلسطين',
            features: [
                { icon: 'fas fa-bed', text: '2 غرف' },
                { icon: 'fas fa-bath', text: '1 حمام' },
                { icon: 'fas fa-ruler-combined', text: '90 م²' },
            ],
            status: 'شقة للإيجار الشهري',
        },
        {
            id: 'fav-property-5',
            image: '../img/landing.jpg',
            price: '1,100.00$',
            location: 'الخليل، فلسطين',
            features: [
                { icong: 'fas fa-bed', text: '3 غرف' },
                { icon: 'fas fa-bath', text: '2 حمام' },
                { icon: 'fas fa-ruler-combined', text: '160 م²' },
            ],
            status: 'منزل عائلي كبير',
        },
        // أضف المزيد من العقارات المفضلة الوهمية هنا
    ];

    const propertiesPerPage = 9; // عدد العقارات في كل صفحة
    let currentPage = 1;

    // ** وظيفة عرض العقارات في الشبكة **
    function renderProperties(page, propertiesToDisplay) {
        propertyGrid.innerHTML = ''; // مسح البطاقات الحالية

        // إذا لا توجد عقارات، أظهر رسالة "لا توجد مفضلة"
        if (propertiesToDisplay.length === 0) {
            noFavoritesMessage.style.display = 'block';
            paginationContainer.innerHTML = ''; // إخفاء ترقيم الصفحات
            return;
        } else {
            noFavoritesMessage.style.display = 'none';
        }

        const start = (page - 1) * propertiesPerPage;
        const end = start + propertiesPerPage;
        const currentProperties = propertiesToDisplay.slice(start, end);

        currentProperties.forEach((property) => {
            const card = document.createElement('div');
            card.classList.add('property-card');
            card.id = property.id; // تعيين ID للبطاقة
            card.innerHTML = `
                <div class="property-image">
                    <img src="${property.image}" alt="Property Image">
                    <button class="favorite-btn active" data-property-id="${property.id}"><i class="fas fa-heart"></i></button>
                </div>
                <div class="property-info">
                    <span class="property-price">${property.price}</span>
                    <p class="property-location">${property.location}</p>
                    <div class="property-features">
                        ${property.features.map((feature) => `<div><i class="${feature.icon}"></i> <span>${feature.text}</span></div>`).join('')}
                    </div>
                    <p class="property-status">${property.status}</p>
                </div>
            `;
            propertyGrid.appendChild(card);
        });

        // ** تفعيل وظيفة زر الإعجاب (القلب) على البطاقات المعروضة **
        document.querySelectorAll('.favorite-btn').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.stopPropagation(); // منع النقر على البطاقة بالكامل
                const icon = event.currentTarget.querySelector('i');
                const propertyId = event.currentTarget.dataset.propertyId;

                // تبديل الكلاسات والأيقونة
                icon.classList.toggle('far');
                icon.classList.toggle('fas');
                event.currentTarget.classList.toggle('active');

                // ** منطق إضافة/إزالة العقار من المفضلة الوهمية **
                if (!event.currentTarget.classList.contains('active')) {
                    // إذا لم يعد نشطاً، قم بإزالته من البيانات
                    const index = favoritePropertiesData.findIndex((p) => p.id === propertyId);
                    if (index !== -1) {
                        favoritePropertiesData.splice(index, 1);
                        // أعد عرض العقارات لتحديث القائمة
                        renderProperties(currentPage, favoritePropertiesData);
                        setupPagination(favoritePropertiesData);
                        // إذا كانت الصفحة الحالية فارغة بعد الإزالة، اذهب للصفحة السابقة
                        if (currentProperties.length === 1 && currentPage > 1 && favoritePropertiesData.length % propertiesPerPage === 0) {
                            currentPage--;
                            renderProperties(currentPage, favoritePropertiesData);
                            setupPagination(favoritePropertiesData);
                        }
                    }
                } else {
                    // هنا يمكن إضافة العقار إذا كان زر الإعجاب قد تم النقر عليه وأصبح active
                    // في هذه الصفحة، نفترض أن العقارات في favoritePropertiesData هي بالفعل مفضلة
                    // وبالتالي هذا الجزء قد لا يكون ضرورياً إلا إذا كنت تضيف عقارات جديدة هنا
                }
            });
        });
    }

    // ** وظيفة إعداد ترقيم الصفحات **
    function setupPagination(propertiesToDisplay) {
        const totalPages = Math.ceil(propertiesToDisplay.length / propertiesPerPage);
        paginationContainer.innerHTML = ''; // مسح نقاط الترقيم الحالية

        if (totalPages <= 1) {
            // لا حاجة لترقيم الصفحات إذا كانت صفحة واحدة أو لا يوجد عقارات
            paginationContainer.style.display = 'none';
            return;
        } else {
            paginationContainer.style.display = 'flex';
        }

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
            paginationContainer.appendChild(dot);
        }
    }

    // ** وظيفة تحديث حالة نقاط الترقيم **
    function updatePaginationDots() {
        document.querySelectorAll('.pagination-dot').forEach((dot) => {
            dot.classList.remove('active');
            if (parseInt(dot.dataset.page) === currentPage) {
                dot.classList.add('active');
            }
        });
    }

    // ** الاستدعاء الأولي لوظائف عرض المفضلة عند تحميل الصفحة **
    renderProperties(currentPage, favoritePropertiesData);
    setupPagination(favoritePropertiesData);
});
