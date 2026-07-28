<x-amber::layout>

    <section class="ac-page-hero ac-page-hero--cream">
        <div class="ac-container">
            <p class="ac-eyebrow">{{ __('faq.hero.pre-peading') }}</p>
            <h1 class="ac-h1">{{ __('faq.hero.heading') }}</h1>
            <p class="ac-editorial" style="margin-top:var(--space-4);max-width:680px;">{{ __('faq.hero.text') }}</p>
        </div>
    </section>

    <x-amber::section color="paper">
        <div class="ac-container">
            <div class="ac-faq" data-faq>

                <div class="ac-faq__tabs" role="tablist" aria-label="FAQ categories">
                    <button class="ac-faq__tab" type="button" data-cat="trust">About &amp; trust</button>
                    <button class="ac-faq__tab" type="button" data-cat="services">Services &amp; process</button>
                    <button class="ac-faq__tab" type="button" data-cat="eligibility">Who it's for</button>
                    <button class="ac-faq__tab" type="button" data-cat="pricing">Pricing</button>
                    <button class="ac-faq__tab" type="button" data-cat="privacy">Privacy</button>
                </div>

                <div class="ac-faq__list">

                    <!-- TRUST -->
                    <details class="ac-faq__q" data-cat="trust" open>
                        <summary>Are you independent, or do schools pay you?</summary>
                        <div class="ac-faq__a"><p>We work for the family, not for any institution. We are not paid commissions to place students at particular schools or universities — our only incentive is to find the right fit.</p></div>
                    </details>
                    <details class="ac-faq__q" data-cat="trust" open>
                        <summary>What does "honest targeting" mean?</summary>
                        <div class="ac-faq__a"><p>We tell you what is realistically reachable for your child — ability-weighted, not what sells. If a goal isn't realistic, we say so, and map the strongest path that is.</p></div>
                    </details>
                    <details class="ac-faq__q ac-faq__q--last" data-cat="trust" open>
                        <summary>What exactly are you accountable for?</summary>
                        <div class="ac-faq__a"><p>Every stage and every deadline. We own the process end-to-end and stay reachable 24/7 throughout — nothing falls through.</p></div>
                    </details>

                    <!-- SERVICES -->
                    <details class="ac-faq__q" data-cat="services" open>
                        <summary>What's the difference between the Academic Roadmap and a single service?</summary>
                        <div class="ac-faq__a"><p>The Academic Roadmap is the full, managed path — assessment, placement, preparation, enrollment and post-enrollment support. Each part is also available on its own.</p></div>
                    </details>
                    <details class="ac-faq__q" data-cat="services" open>
                        <summary>Can I take just one service?</summary>
                        <div class="ac-faq__a"><p>Yes. Every service is available standalone, or combined into your Academic Roadmap.</p></div>
                    </details>
                    <details class="ac-faq__q ac-faq__q--last" data-cat="services" open>
                        <summary>How does the process work?</summary>
                        <div class="ac-faq__a"><p>It starts with a short profile quiz and a free consultation. From there we map a realistic plan and manage each stage with you.</p></div>
                    </details>

                    <!-- ELIGIBILITY -->
                    <details class="ac-faq__q" data-cat="eligibility" open>
                        <summary>What age or grade should we start at?</summary>
                        <div class="ac-faq__a"><p>Many families come to us around the end of grade 9, when the next decade is being decided — but earlier or later is fine. <em>[Confirm exact framing.]</em></p></div>
                    </details>
                    <details class="ac-faq__q" data-cat="eligibility" open>
                        <summary>Do you work with families outside Poland?</summary>
                        <div class="ac-faq__a"><p>Yes — we work with families across the Tri-City (Gdańsk, Sopot, Gdynia) and Polish families abroad. <em>[Confirm scope.]</em></p></div>
                    </details>
                    <details class="ac-faq__q ac-faq__q--last" data-cat="eligibility" open>
                        <summary>What if my child isn't a top student?</summary>
                        <div class="ac-faq__a"><p>That's exactly who we help. We map the strongest path your child can realistically reach — honestly, and managed all the way.</p></div>
                    </details>

                    <!-- PRICING -->
                    <details class="ac-faq__q" data-cat="pricing" open>
                        <summary>How is pricing determined?</summary>
                        <div class="ac-faq__a"><p>Modular services start from a set price. The Academic Roadmap is priced by consultation — it depends on the student, the duration and the scope of support. <em>[Confirm.]</em></p></div>
                    </details>
                    <details class="ac-faq__q" data-cat="pricing" open>
                        <summary>What affects the cost?</summary>
                        <div class="ac-faq__a"><p>The services you choose, the length of the engagement, the institutions involved and the level of support. <em>[Confirm.]</em></p></div>
                    </details>
                    <details class="ac-faq__q ac-faq__q--last" data-cat="pricing" open>
                        <summary>Are there payment terms or refunds?</summary>
                        <div class="ac-faq__a"><p><em>[Placeholder — to be confirmed.]</em></p></div>
                    </details>

                    <!-- PRIVACY -->
                    <details class="ac-faq__q" data-cat="privacy" open>
                        <summary>How do you handle our documents and data?</summary>
                        <div class="ac-faq__a"><p>Visa records, financial documents and family details are handled with strict discretion — the way a name, once given, cannot be taken back carelessly. <em>[Confirm GDPR / storage specifics.]</em></p></div>
                    </details>
                    <details class="ac-faq__q ac-faq__q--last" data-cat="privacy" open>
                        <summary>Is everything confidential?</summary>
                        <div class="ac-faq__a"><p>Yes. Discretion is a discipline for us — we treat what's entrusted to us as we would our own name.</p></div>
                    </details>

                </div>
            </div>
        </div>
    </x-amber::section>

    <x-amber::cta :content="[
        'url' => locale_url('contacts'),
        'pre_heading' => new \App\Services\Api\MultilingualService('asd')
    ]" />

</x-amber::layout>
