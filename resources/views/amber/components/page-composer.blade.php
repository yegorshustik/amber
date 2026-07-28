@if(($components = $content->components($children ?? null))->count() > 0)
    @foreach($components as $block)
        @if($block['name'] == 'Hero')
            <x-amber::hero :content="$block['content']" />
        @elseif($block['name'] == 'Quote')
            <x-amber::quote :style="$level > 0 ? 'margin-top:var(--space-16);' : ''" :content="$block['content']['quote']" />
        @elseif($block['name'] == 'Section')
            <x-amber::section
                :id="$block['content']['id'] ?? null"
                :style="($block['content']['id'] ?? null) == 'pricing' ? 'padding-top: 0;' : ''"
                :color="$block['content']['color'] ?? 'default'">
                @unless($block['content']['text']?->empty())
                    @if(!$block['content']['pre_heading']?->empty() || !$block['content']['heading']['text']?->empty() || !$block['content']['text']?->empty())
                        <x-slot name="head">
                            <div class="ac-container">
                                <div class="ac-cols">
                                    <div class="ac-cols__label">
                                        @unless($block['content']['pre_heading']?->empty())
                                            <p class="ac-eyebrow">{{ $block['content']['pre_heading'] }}</p>
                                        @endunless

                                        @unless($block['content']['heading']['text']?->empty())
                                            <x-amber::heading :max-characters="$block['content']['heading_max_characters'] ?? 1000" :level="$block['content']['heading']['level'] ?? 'h2'" :style="$block['content']['heading']['style'] ?? null">
                                                {!! $block['content']['heading']['text'] !!}
                                            </x-amber::heading>
                                        @endunless
                                    </div>
                                    <div class="ac-cols__body">
                                        {!! str_replace('<p', '<p class="ac-body"', $block['content']['text']) !!}
                                    </div>
                                </div>
                            </div>
                        </x-slot>
                    @endif
                    <div class="ac-container">
                        <x-amber::page-composer :level="$level + 1" :content="$content" :children="$block['children']" />
                    </div>
                @else
                    <div class="ac-container">
                        @unless($block['content']['pre_heading']?->empty())
                            <p class="ac-eyebrow">{{ $block['content']['pre_heading'] }}</p>
                        @endunless

                        @unless($block['content']['heading']['text']?->empty())
                            <x-amber::heading :max-characters="$block['content']['heading_max_characters'] ?? 1000" :level="$block['content']['heading']['level'] ?? 'h2'" :style="$block['content']['heading']['style'] ?? null">
                                {!! $block['content']['heading']['text'] !!}
                            </x-amber::heading>
                        @endunless

                        <x-amber::page-composer :level="$level + 1" :content="$content" :children="$block['children']" />
                    </div>
                @endunless
            </x-amber::section>
        @elseif($block['name'] == 'Reviews')
            <x-amber::reviews />
        @elseif($block['name'] == 'Cta')
            <x-amber::cta :content="$block['content']" />
        @elseif($block['name'] == 'Headline')
            <x-amber::headline :content="$block['content']" />
        @elseif($block['name'] == 'TextBlock')
            <x-amber::text-block :content="$block['content']" />
        @elseif($block['name'] == 'Cards')
            <x-amber::cards :type="$block['content']['type']" :columns="$block['content']['columns'] ?? 'default'" :image="$block['content']['image']" :cards="$block['content']['items']" :style="$level > 0 ? 'margin-top:var(--space-12);' : ''" />

            @unless($block['content']['button']?->empty())
                <div style="margin-top:var(--space-12);">
                    <a class="ac-btn ac-btn--ghost"
                       @if(isExternalUrl($block['content']['url'])) target="_blank" rel="noopener noreferrer" @endif
                       href="{{ isExternalUrl($block['content']['url']) ? $block['content']['url'] : locale_url($block['content']['url']) }}">
                        {{ $block['content']['button'] }}
                    </a>
                </div>
            @endunless
        @elseif($block['name'] == 'Text')
            {!! $block['content']['text'] !!}
        @elseif($block['name'] == 'Image')
            <x-amber::image :content="$block['content']" />
        @elseif($block['name'] == 'Article')
            <x-amber::toc>
                <x-amber::page-composer :level="$level + 1" :content="$content" :children="$block['children']" />
            </x-amber::toc>
        @elseif($block['name'] == 'Faq')
            @if($block['content']['category'])
                @if($faq = $block['content']['faq']->where('slug', $block['content']['category'])->first())
                    <div class="ac-faq" style="margin-top: var(--space-8);">
                        <div class="ac-faq__list">
                            @foreach($faq['items'] as $item)
                                <details @class(['ac-faq__q', 'ac-faq__q--last' => $loop->last])>
                                    <summary>{{ $item['question'] }}</summary>
                                    <div class="ac-faq__a">
                                        {!! $item['answer'] !!}
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </div>
                    <div style="margin-top:var(--space-8);">
                        <a class="ac-btn ac-btn--secondary" href="{{ locale_url('faq') }}#{{ $faq['slug'] }}">{{ __('see-all-faq') }}</a>
                    </div>
                @endif
            @else
                @if($block['content']['faq']->count() > 0)
                    <div class="ac-faq" data-faq>
                        <div class="ac-faq__tabs" role="tablist" aria-label="FAQ categories">
                            @foreach($block['content']['faq'] as $category)
                                <button class="ac-faq__tab" type="button" data-cat="{{ $category['slug'] }}">{{ $category['title'] }}</button>
                            @endforeach
                        </div>
                    </div>
                    @foreach($category['items'] as $faq)
                        <details class="ac-faq__q" data-cat="{{ $category['slug'] }}" open>
                            <summary>{{ $faq['question'] }}</summary>
                            <div class="ac-faq__a">
                                {!! $faq['answer'] !!}
                            </div>
                        </details>
                    @endforeach
                @endif
            @endif
        @elseif($block['name'] == 'Person')
            <div class="ac-founder">
                <figure class="ac-founder__photo">
                    @if($block['content']['image']?->exists())
                        <img class="ac-img-ph ac-img-ph--portrait"
                             style="object-fit: cover"
                             src="{{ $block['content']['image']->url() }}"
                             alt="{{ $block['content']['image']->alt() }}" >
                    @else
                        <div class="ac-img-ph ac-img-ph--portrait"></div>
                    @endif
                </figure>
                <div>
                    @unless($block['content']['job']?->empty())
                        <p class="ac-eyebrow">{{ $block['content']['job'] }}</p>
                    @endunless
                    @unless($block['content']['name']?->empty())
                        <h2 class="ac-h2" style="max-width:18ch;">{{ $block['content']['name'] }}</h2>
                    @endunless
                    @unless($block['content']['about']?->empty())
                            {!! str_replace('<p', '<p style="margin-top:var(--space-4);max-width:52ch;" class="ac-body"', $block['content']['about']) !!}
                    @endunless

                    @if($block['content']['linkedin'])
                    <div style="margin-top:var(--space-6);">
                        <a class="ac-btn ac-btn--secondary" href="{{ $block['content']['linkedin'] }}" target="_blank" rel="noopener">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                                <rect width="4" height="12" x="2" y="9"/>
                                <circle cx="4" cy="4" r="2"/>
                            </svg>
                            LinkedIn
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @elseif($block['name'] == 'Contacts')
            <div class="ac-contacts">

                <!-- LEFT: details -->
                <div class="ac-contacts__col">
                    <div class="ac-contacts__block">
                        <p class="ac-eyebrow">{{ __('contacts.company') }}</p>

                        <address>
                            @if($block['content']['company-name'])
                                {{ $block['content']['company-name'] }}<br>
                            @endif
                            @if($block['content']['address'])
                                <a href="https://www.google.com/maps/search/?api=1&amp;query={{ $block['content']['address'] }}" target="_blank" rel="noopener">{{ $block['content']['address'] }}</a>
                            @endif
                        </address>

                        @if($block['content']['registration-numbers'])
                        <p class="ac-small" style="margin-top:var(--space-2);">{{ $block['content']['registration-numbers'] }}</p>
                        @endif
                    </div>

                    <div class="ac-contacts__block">
                        <p class="ac-eyebrow">{{ __('contacts.direct') }}</p>
                        @if($block['content']['phone'])
                            <p><a href="tel:{{ clearPhone($block['content']['phone']) }}">{{ $block['content']['phone'] }}</a></p>
                        @endif
                        @if($block['content']['email'])
                            <p><a href="mailto:{{ $block['content']['email'] }}">{{ $block['content']['email'] }}</a></p>
                        @endif
                    </div>

                    @if($block['content']['opening-hours']->count() > 0)
                        <div class="ac-contacts__block">
                            <p class="ac-eyebrow">{{ __('contacts.hours') }}</p>
                            @foreach($block['content']['opening-hours'] as $row)
                                <p>{{ $row }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- RIGHT: actions -->
                <div class="ac-contacts__col">
                    <div class="ac-contacts__block">
                        <p class="ac-eyebrow">{{ __('contacts.message-us') }}</p>
                        <div class="ac-contacts__row">
                            <!-- WhatsApp uses the real phone number -->
                            @if($block['content']['whatsapp'])
                                <a class="ac-btn ac-btn--secondary" href="{{ $block['content']['whatsapp'] }}" target="_blank" rel="noopener">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                    {{ __('contacts.whatsapp') }}
                                </a>
                            @endif
                            <!-- PLACEHOLDER Telegram handle — replace ambercouncil with the real one -->
                            @if($block['content']['telegram'])
                                <a class="ac-btn ac-btn--secondary" href="{{ $block['content']['telegram'] }}" target="_blank" rel="noopener">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                    {{ __('contacts.telegram') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="ac-contacts__block">
                        <p class="ac-eyebrow">{{ __('contacts.follow') }}</p>
                        <div class="ac-contacts__row">
                            <!-- PLACEHOLDER social links — replace with real profiles -->
                            @if($block['content']['linkedin'])
                            <a class="ac-btn ac-btn--secondary" href="{{ $block['content']['linkedin'] }}" target="_blank" rel="noopener">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                                {{ __('contacts.linkedin') }}
                            </a>
                            @endif
                            @if($block['content']['instagram'])
                                <a class="ac-btn ac-btn--secondary" href="{{ $block['content']['instagram'] }}" target="_blank" rel="noopener">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                                    {{ __('contacts.instagram') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($block['content']['vcf']?->exists())
                    <div class="ac-contacts__block">
                        <p class="ac-eyebrow">{{ __('contacts.vcf-title') }}</p>
                        <div class="ac-contacts__row">
                            <a class="ac-btn ac-btn--secondary" href="{{ $block['content']['vcf']->url() }}" download>{{ __('contacts.vcf-download') }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @elseif($block['name'] == 'ContactCenter')
            @if($block['content']['form'])
                <div class="ac-lead">
                    @if(!$block['content']['pre_heading']?->empty() || !$block['content']['heading']['text']?->empty() || !$block['content']['text']?->empty())
                        <div class="ac-lead__intro">
                            @unless($block['content']['pre_heading']?->empty())
                                <p class="ac-eyebrow">{{ $block['content']['pre_heading'] }}</p>
                            @endunless

                            @unless($block['content']['heading']['text']?->empty())
                                <x-amber::heading :max-characters="$block['content']['heading_max_characters'] ?? 1000" :level="$block['content']['heading']['level'] ?? 'h2'" :style="$block['content']['heading']['style'] ?? null">
                                    {!! $block['content']['heading']['text'] !!}
                                </x-amber::heading>
                            @endunless

                            @unless($block['content']['text']?->empty())
                                {!! str_replace('<p', '<p style="max-width:42ch;" class="ac-body"', $block['content']['text']) !!}
                            @endunless
                        </div>
                    @endif


                    <div class="ac-card" style="position:relative; overflow:hidden;">
                        <div class="ac-form-success" id="form-success" aria-live="polite">
                            <div class="ac-form-success__inner">
                                <div class="ac-form-success__icon">
                                    <svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="26" cy="26" r="25" stroke="currentColor" stroke-width="2"/>
                                        <polyline points="14,27 22,35 38,18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <h3 class="ac-form-success__title">{{ select_multilingual_field_value($block['content']['form']->options['thank-you.heading']) }}</h3>
                                <div class="ac-form-success__body">{!! select_multilingual_field_value($block['content']['form']->options['thank-you.text']) !!}</div>
                            </div>
                        </div>


                        <form class="ac-form" id="lead-form" onsubmit="return false;" novalidate>
                            <div class="ac-form__row">
                                @foreach($block['content']['form']->fields as $field)
                                    <div class="ac-field">
                                        <label class="ac-label" for="field_{{ $field->id }}">{{ $field->title }}</label>

                                        @if($field->type == \App\Enums\Inbox\FieldType::TEXT)
                                            <input class="ac-input" id="field_{{ $field->id }}" type="text" placeholder="{{ $field->placeholder }}" autocomplete="name">
                                        @elseif($field->type == \App\Enums\Inbox\FieldType::DATE)
                                            <input class="ac-input" id="field_{{ $field->id }}" type="text" inputmode="numeric" maxlength="10" placeholder="{{ $field->placeholder }}" autocomplete="bday">
                                        @elseif($field->type == \App\Enums\Inbox\FieldType::EMAIL)
                                            <input class="ac-input" id="field_{{ $field->id }}" type="email" placeholder="{{ $field->placeholder }}" autocomplete="email">
                                        @elseif($field->type == \App\Enums\Inbox\FieldType::TEL)
                                            <input class="ac-input" id="field_{{ $field->id }}" type="tel" placeholder="{{ $field->placeholder }}" autocomplete="tel">
                                        @elseif($field->type == \App\Enums\Inbox\FieldType::SELECT)
                                            <select class="ac-select" id="field_{{ $field->id }}">
                                                <option value="">{{ $field->placeholder }}</option>
                                                @foreach($field->options AS $item)
                                                    <option>{{ select_multilingual_field_value($item['option']) }}</option>
                                                @endforeach

                                            </select>
                                        @endif
                                        <p class="ac-field-error" id="err-name" aria-live="polite"></p>
                                    </div>
                                @endforeach
                            </div>
                            <p class="ac-small" style="font-size:13px; margin-top:var(--space-3); margin-bottom:var(--space-2); color:var(--muted); text-align:center;">
                                {!! __('form.agreement', [
                                    'terms' => '<a href="' . locale_url('terms') . '" style="color:inherit; text-decoration:underline;">' . __('form.agreement.terms') . '</a>',
                                    'privacy' => '<a href="' . locale_url('privacy') . '" style="color:inherit; text-decoration:underline;">' . __('form.agreement.privacy') . '</a>'
                                ]) !!}
                            </p>
                            <button class="ac-btn ac-btn--primary" id="submit-btn" type="submit" style="width:100%;">
                                {{ select_multilingual_field_value($block['content']['form']->options['design.submit-button-text'], __('form.submit')) }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    @endforeach
@endif
