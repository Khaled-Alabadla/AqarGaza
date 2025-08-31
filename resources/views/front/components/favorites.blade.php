@auth
    <button class="fab-favorites" aria-label="المفضلة">
        <i class="fas fa-heart"></i>
    </button>
    <aside class="favorites-sidebar" aria-hidden="true">
        <div class="favorites-sidebar-header">
            <h3>المفضلة</h3>
            <button class="favorites-close-btn" aria-label="إغلاق المفضلة">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <ul class="favorites-list">
            <!-- Populated by JavaScript -->
        </ul>
        <div class="favorites-sidebar-footer">
            <a href="{{ route('front.favorites.index') }}" class="all-favorites-btn">كل المفضلة</a>
        </div>
    </aside>
@endauth
