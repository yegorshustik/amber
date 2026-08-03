<x-amber::layout>
    @php
        /** @var \App\Models\Catalog $item */
    @endphp
    <x-amber::section color="cream" class=" ac-section--hero-detail">
        <div class="ac-container">

            <div class="ac-hero">
                <div class="ac-hero__text">
                    <p class="ac-eyebrow"><a href="{{ locale_url('catalog') }}" style="color:inherit;text-decoration:none;">{{ __('catalog.directory') }}</a> &middot; {{ $item->type->title() }}</p>
                    <h1 class="ac-h1" style="max-width:18ch;margin-bottom:var(--space-4);">{{ seo()->h1 }}</h1>
                    <p class="ac-editorial" style="margin:0;max-width:65ch;">
                        {{ $item->details }}
                    </p>
                </div>
                <div class="ac-hero__media">
                    @if($item->image?->exists())
                    <img class="ac-photo ac-photo--portrait" src="{{ $item->image->url() }}" alt="{{ $item->image->alt() ?: $item->title }}" width="941" height="941">
                    @endif
                </div>
            </div>
        </div>
    </x-amber::section>

    <x-amber::section color="paper">
        <div class="ac-container">
            <div class="ac-cols">
                <div class="ac-cols__label">
                    <p class="ac-eyebrow">{{ __('catalog.specifications') }}</p>
                    <h2 class="ac-h2" style="margin-bottom:var(--space-6);">{{ __('catalog.profile-details') }}</h2>

                    <table class="ac-spec-table">
                        <tr>
                            <td>{{ __('catalog.spec.type') }}</td>
                            <td><strong>{{ $item->type->title() }}</strong></td>
                        </tr>
                        <tr>
                            <td>{{ __('catalog.spec.country') }}</td>
                            <td><strong>{{ $item->country }}</strong></td>
                        </tr>
                        <tr>
                            <td>{{ __('catalog.spec.location') }}</td>
                            <td><strong>{{ $item->city }}</strong></td>
                        </tr>

                        @if($item->type == \App\Enums\Catalog\ItemType::SCHOOL)
                            <tr>
                                <td>{{ __('catalog.spec.age-range') }}</td>
                                <td><strong>{{ $item->age_range }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ __('catalog.spec.gender') }}</td>
                                <td><strong>{{ $item->gender }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ __('catalog.spec.boarding') }}</td>
                                <td><strong>{{ $item->boarding }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ __('catalog.spec.curriculum') }}</td>
                                <td><strong>{{ $item->curriculum }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ __('catalog.spec.size') }}</td>
                                <td><strong>{{ $item->size }}</strong></td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ __('catalog.spec.degrees') }}</td>
                                <td><strong>{{ $item->degrees }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ __('catalog.spec.acceptance') }}</td>
                                <td><strong>{{ $item->acceptance }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ __('catalog.spec.campus') }}</td>
                                <td><strong>{{ $item->campus_style }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ __('catalog.spec.programs') }}</td>
                                <td><strong>{{ $item->programs }}</strong></td>
                            </tr>
                            <tr>
                                <td>{{ __('catalog.spec.established') }}</td>
                                <td><strong>{{ $item->established }}</strong></td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="ac-cols__body ac-cols__body--detail">
                    @unless($item->pre_heading->empty())
                        <p class="ac-eyebrow">{{ $item->pre_heading }}</p>
                    @endunless

                    @unless($item->heading->empty())
                        <h2 class="ac-h2" style="margin-bottom:var(--space-6);">{!! $item->heading !!}</h2>
                    @endunless

                    @unless($item->content->empty())
                        <div class="ac-prose">
                            {!! $item->content !!}
                        </div>
                    @endunless
                </div>
            </div>
        </div>
    </x-amber::section>


    @if(($faq = $item->getFaq())->count() > 0)
        <x-amber::section
            color="cream">
            <div class="ac-container">
                <p class="ac-eyebrow">{{ __('catalog.faq.pre-heading') }}</p>
                <h2 class="ac-h2" style="max-width:20ch;margin-bottom:var(--space-8);">{{ __('catalog.faq.heading') }}</h2>

                <div class="ac-faq">
                    <div class="ac-faq__list">
                        @foreach($faq as $faqItem)
                            <details @class(['ac-faq__q', 'ac-faq__q--last' => $loop->last])>
                                <summary>{{ $faqItem['question'] }}</summary>
                                <div class="ac-faq__a">
                                    {!! $faqItem['answer'] !!}
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-amber::section>
    @endif


    <x-amber::section
        :style="$item->getFaq()->count() > 0 ? 'padding-top: 0' : ''"
        color="cream">
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
