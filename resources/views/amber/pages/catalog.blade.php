<x-amber::layout>
    <x-amber::headline :content="[
        'color' => 'cream',
        'pre_heading' => new \App\Services\Api\MultilingualService(['en' => __('catalog.pre-heading')]),
        'heading' => [
            'text' => new \App\Services\Api\MultilingualService(['en' => seo()->h1]),
            'level' => 'h1'
        ],
        'text' => new \App\Services\Api\MultilingualService(['en' => __('catalog.text')]),
        'button_1' => new \App\Services\Api\MultilingualService(['en' => null]),
        'button_2' => new \App\Services\Api\MultilingualService(['en' => null]),
    ]" />




    <x-amber::section color="paper">
        <div class="ac-container">
            <div class="ac-filter-tabs">
                @foreach(\App\Enums\Catalog\ItemType::cases() as $type)
                    <button @class(['ac-filter-tab', 'is-active' => $loop->first]) data-filter-type="{{ $type->value }}">{{ $type->title(plural: true) }}</button>
                @endforeach
            </div>

            <div class="ac-catalog-layout">

                <!-- Filter Sidebar -->
                <aside class="ac-filters">
                    @if($filters['country']->count() > 0)
                        <div class="ac-filter-group">
                            <label class="ac-filter-group__t" for="filter-country">{{ __('catalog.filter.country') }}</label>
                            <select id="filter-country" class="ac-select">
                                <option value="all">{{ __('catalog.filter.all-countries') }}</option>
                                @foreach($filters['country'] as $item)
                                    <option value="{{ $item['slug'] }}">{{ $item['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- School-specific sub-filters -->
                    <div id="sub-filters-school" style="display:none;">
                        @if($filters['gender']->count() > 0)
                            <div class="ac-filter-group">
                                <label class="ac-filter-group__t" for="filter-gender">{{ __('catalog.filter.gender') }}</label>
                                <select id="filter-gender" class="ac-select">
                                    <option value="all">{{ __('catalog.filter.all-options') }}</option>
                                    @foreach($filters['gender'] as $item)
                                        <option value="{{ $item['slug'] }}">{{ $item['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if($filters['boarding']->count() > 0)
                            <div class="ac-filter-group">
                                <label class="ac-filter-group__t" for="filter-boarding">{{ __('catalog.filter.boarding') }}</label>
                                <select id="filter-boarding" class="ac-select">
                                    <option value="all">{{ __('catalog.filter.all-options') }}</option>
                                    @foreach($filters['boarding'] as $item)
                                        <option value="{{ $item['slug'] }}">{{ $item['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <!-- University-specific sub-filters -->
                    <div id="sub-filters-university" style="display:none;">
                        @if($filters['campus_style']->count() > 0)
                            <div class="ac-filter-group">
                                <label class="ac-filter-group__t" for="filter-campus">{{ __('catalog.filter.campus-style') }}</label>
                                <select id="filter-campus" class="ac-select">
                                    <option value="all">{{ __('catalog.filter.all-styles') }}</option>
                                    @foreach($filters['campus_style'] as $item)
                                        <option value="{{ $item['slug'] }}">{{ $item['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </aside>

                <!-- Results Grid -->
                <div class="ac-catalog-grid" id="catalog-grid2">
                    @foreach($catalog AS $item)
                        @php
                            /** @var \App\Models\Catalog $item */
                        @endphp

                        {!! ($item->is_visible ? '<a ' : '<div ') . ($item->is_visible ? ('href="'.$item->url.'" class="ac-catalog-card">') : 'class="ac-catalog-card is-placeholder">') !!}
                        <div class="ac-catalog-card__img-wrap">
                            @if($item->image?->exists())
                            <img src="{{ $item->image->url() }}" alt="{{ $item->image->alt() ?: $item->title }}" class="ac-catalog-card__img">
                            @endif
                        </div>

                        <div class="ac-catalog-card__body">
                            <span class="ac-catalog-card__type">{{ $item->type?->title() }}</span>
                            <h3 class="ac-catalog-card__title">{{ $item->title }}</h3>
                            <div class="ac-catalog-card__loc">{{ $item->city }}, {{ $item->country }}</div>
                            <p class="ac-catalog-card__desc">{{ $item->short_details }}</p>
                            <div class="ac-catalog-card__specs">
                                @if($item->type == \App\Enums\Catalog\ItemType::SCHOOL)
                                    <div class="ac-catalog-card__spec-item"><strong>Age:</strong> {{ $item->age_range }}</div>
                                    <div class="ac-catalog-card__spec-item"><strong>Gender:</strong> {{ $item->gender }}</div>
                                    <div class="ac-catalog-card__spec-item"><strong>Boarding:</strong> {{ $item->boarding }}</div>
                                    <div class="ac-catalog-card__spec-item"><strong>Curriculum:</strong> {{ $item->curriculum }}</div>
                                @else
                                    <div class="ac-catalog-card__spec-item"><strong>Degrees:</strong> {{ $item->degrees }}</div>
                                    <div class="ac-catalog-card__spec-item"><strong>Acceptance:</strong> {{ $item->acceptance }}</div>
                                    <div class="ac-catalog-card__spec-item"><strong>Campus:</strong> {{ $item->campus_style }}</div>
                                    <div class="ac-catalog-card__spec-item"><strong>Programs:</strong> {{ $item->programs }}</div>
                                @endif
                            </div>

                            @if($item->is_visible )
                                <span class="ac-catalog-card__link">{{ __('catalog.profile-visible') }} <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></span>
                            @else
                                <span class="ac-catalog-card__link is-placeholder">{{ __('catalog.profile-invisible') }}</span>
                            @endif
                        </div>

                        {!! ($item->is_visible ? '</a>' : '</div>') !!}
                    @endforeach
                </div>
            </div>
        </div>
    </x-amber::section>


    <x-amber::section
        style="padding-top: 0"
        color="paper">
        <div class="ac-container">
            <x-amber::cta :content="[
                'pre_heading' => new \App\Services\Api\MultilingualService(['en' => __('catalog.cta.pre-heading')]),
                'heading' => [
                    'text' => new \App\Services\Api\MultilingualService(['en' => __('catalog.cta.heading')]),
                    'level' => 'h2'
                ],
                'text' => new \App\Services\Api\MultilingualService(['en' => __('catalog.cta.text')]),
                'button' => new \App\Services\Api\MultilingualService(['en' => __('catalog.cta.button')]),
                'url' => locale_url('contacts'),
            ]" />
        </div>
    </x-amber::section>
</x-amber::layout>
