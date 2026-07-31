@if($form && $form->exists)
    <section {{ $attributes->merge(['class' => 'ac-section ac-section--cream', 'id' => 'cta' ]) }}>
        <style>
            .ac-newsletter-form {
                width: 100%;
                max-width: 420px;
            }
            .ac-newsletter-group {
                display: flex;
                gap: var(--space-3);
                width: 100%;
            }
            .ac-newsletter-form .ac-input {
                background: rgba(255, 255, 255, 0.07);
                border: 1px solid rgba(255, 255, 255, 0.18);
                color: var(--cream);
                flex: 1;
                min-width: 200px;
            }
            .ac-newsletter-form .ac-input::placeholder {
                color: rgba(237, 228, 210, 0.55);
            }
            .ac-newsletter-form .ac-input:focus {
                border-color: var(--amber);
                background: rgba(255, 255, 255, 0.12);
                box-shadow: 0 0 0 3px rgba(214, 163, 85, 0.25);
            }
            .ac-newsletter-form .ac-input.is-error {
                border-color: var(--error);
                background: rgba(248, 99, 99, 0.05);
            }
            .ac-newsletter-form .ac-input.is-valid {
                border-color: var(--amber);
            }
        </style>
        <div class="ac-container">
            <div class="ac-action" style="position: relative; overflow: hidden;">
                <div class="ac-action__text">
                    <p class="ac-eyebrow">Stay informed</p>
                    <h2 class="ac-h2">Subscribe to our journal.</h2>
                    <p>Get our latest academic insights, admissions timelines, and counselor guides delivered straight to your inbox.</p>
                </div>
                @if($field())
                <form id="newsletter-form" class="ac-newsletter-form" novalidate action="{{ route('amber.inbox', ['slug' => $form->slug]) }}" method="POST">
                    <div class="ac-newsletter-group">
                        <input class="ac-input" type="email" id="f-email" name="field_{{ $field()->id }}" placeholder="Your email address" required>
                        <button type="submit" id="submit-btn" class="ac-btn ac-btn--primary">Subscribe</button>
                    </div>
                    <div class="ac-field-error" id="err-email"></div>
                </form>
                @endif

                <!-- Form Success State overlay inside .ac-action -->
                <div class="ac-form-success" id="form-success" style="background: var(--navy); color: var(--cream);">
                    <div class="ac-form-success__inner" style="max-width: 440px;">
                        <div class="ac-form-success__icon" style="color: var(--amber);">
                            <svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="26" cy="26" r="25" stroke="currentColor" stroke-width="2"/>
                                <polyline points="14,27 22,35 38,18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h3 class="ac-h3" style="color: var(--cream); margin: 0;">You're subscribed!</h3>
                        <p style="color: var(--cream); opacity: 0.85; margin: 0; font-size: var(--fs-small);">We will send our latest journal updates to your inbox.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
