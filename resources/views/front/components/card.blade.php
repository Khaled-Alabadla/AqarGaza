<div class="card">
    <div class="property-card">
        <div class="property-image">
            <img src="{{ asset($property->main_image) }}" alt="Property Image">
            <button class="favorite-btn"><i class="far fa-heart"></i></button>
        </div>
        <div class="property-info">
            <h2 class="property-name">{{ $property->title }}</h2>
            <span class="property-price">{{ $property->price }}
                @if ($property->currency == 'USD')
                    دولار
                @elseif ($property->currency == 'JOD')
                    دينار
                @else
                    شيكل
                @endif
            </span>
            <p class="property-type">
                @if ($property->type == 'rent')
                    تأجير
                @else
                    بيع
                @endif
            </p>
            <p class="property-location">{{ $property->city->name }}، {{ $property->zone->name }}</p>

            <div class="property-features">
                @if ($property->rooms)
                    <div><i class="fas fa-bed"></i> <span>{{ $property->rooms }} غرف</span></div>
                @endif
                @if ($property->bathrooms)
                    <div><i class="fas fa-bath"></i> <span>{{ $property->bathrooms }}
                            {{ $property->bathrooms == 1 ? 'حمام' : 'حمامات' }}</span></div>
                @endif
                @if ($property->area)
                    <div><i class="fas fa-ruler-combined"></i> <span>{{ $property->area }} م²</span></div>
                @endif
            </div>
            <a class="btn_card" href="{{ route('front.properties.show', $property->id) }}">المزيد من التفاصيل</a>
        </div>
    </div>
</div>
