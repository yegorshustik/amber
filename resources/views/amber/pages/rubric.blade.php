<x-amber::layout>
    <x-amber::headline :content="[
        'color' => 'paper',
        'pre_heading' => $rubric->pre_heading,
        'heading' => [
            'text' => new \App\Services\Api\MultilingualService(['en' => seo()->h1]),
            'level' => 'h1'
        ],
        'text' => $rubric->details,
        'button_1' => new \App\Services\Api\MultilingualService(['en' => null]),
        'button_2' => new \App\Services\Api\MultilingualService(['en' => null]),
    ]" />

    <x-amber::section color="cream">
        <div class="ac-container">
            <div class="ac-blog-filters" id="blog-filters">
                @foreach(\App\Models\Articles\Rubric::items() as $item)
                    <button @class(['ac-blog-filter', 'is-active' => $loop->first]) data-filter="{{ $item->slug == 'blog' ? 'all' : $item->slug }}">{{ $item->slug == 'blog' ? __('all-posts') : $item->title }}</button>
                @endforeach
            </div>

            <div class="ac-blog-grid" id="blog-grid">
                @foreach($articles AS $article)
                    <x-amber::article :article="$article" />
                @endforeach
            </div>
        </div>
    </x-amber::section>

    <x-amber::subscribe style="padding-top:0;" />

    @push('layout-head')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // Category Filtering Logic
                var filterContainer = document.getElementById('blog-filters');
                var cards = document.querySelectorAll('.ac-blog-card');
                if (filterContainer && cards.length) {
                    filterContainer.addEventListener('click', function (e) {
                        var btn = e.target.closest('.ac-blog-filter');
                        if (!btn) return;

                        // Update active state
                        filterContainer.querySelectorAll('.ac-blog-filter').forEach(function (button) {
                            button.classList.remove('is-active');
                        });
                        btn.classList.add('is-active');

                        // Filter cards
                        var category = btn.dataset.filter;
                        cards.forEach(function (card) {
                            if (category === 'all' || card.dataset.category === category) {
                                card.style.display = 'flex';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    });
                }

                // Newsletter Form Validation & Handling
                var form = document.getElementById('newsletter-form');
                var emailInput = document.getElementById('f-email');
                var errorEl = document.getElementById('err-email');
                var successEl = document.getElementById('form-success');

                if (form && emailInput) {
                    // Real-time ASCII restriction (no Cyrillic/non-ASCII characters)
                    emailInput.addEventListener('input', function () {
                        emailInput.value = emailInput.value.replace(/[^\x20-\x7E]/g, '');
                        if (emailInput.classList.contains('is-error')) {
                            validate();
                        }
                    });

                    emailInput.addEventListener('paste', function () {
                        setTimeout(function () {
                            emailInput.value = emailInput.value.replace(/[^\x20-\x7E]/g, '');
                            if (emailInput.classList.contains('is-error')) {
                                validate();
                            }
                        }, 0);
                    });

                    // Blur validation
                    emailInput.addEventListener('blur', function () {
                        validate();
                    });

                    function validate() {
                        var val = emailInput.value.trim();
                        var msg = '';
                        if (!val) {
                            msg = 'Please enter your email';
                        } else if (!/^[^\s@]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{2,}$/.test(val)) {
                            msg = 'Enter a valid email address';
                        }

                        if (errorEl) errorEl.textContent = msg;
                        if (msg) {
                            emailInput.classList.add('is-error');
                            emailInput.classList.remove('is-valid');
                            return false;
                        } else {
                            emailInput.classList.remove('is-error');
                            emailInput.classList.add('is-valid');
                            return true;
                        }
                    }

                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        var isValid = validate();
                        if (!isValid) return;

                        // Show success state
                        if (successEl) {
                            successEl.classList.add('is-visible');
                        }
                    });
                }
            });
        </script>
    @endpush

</x-amber::layout>
