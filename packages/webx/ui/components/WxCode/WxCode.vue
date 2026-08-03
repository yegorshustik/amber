<script setup lang="ts">
import hljs from 'highlight.js/lib/core';
import css from 'highlight.js/lib/languages/css';
import javascript from 'highlight.js/lib/languages/javascript';
import typescript from 'highlight.js/lib/languages/typescript';
import xml from 'highlight.js/lib/languages/xml';
import { ref, onMounted, nextTick } from 'vue';

import 'highlight.js/styles/github.css';

hljs.registerLanguage('js', javascript);
hljs.registerLanguage('ts', typescript);
hljs.registerLanguage('xml', xml);
hljs.registerLanguage('html', xml);
hljs.registerLanguage('vue', xml);
hljs.registerLanguage('css', css);

const props = defineProps<{
    lang?: string;
    title?: string;
}>();

const isCopied = ref(false);
const codeRoot = ref<HTMLElement | null>(null);
const rawCode = ref('');

const extractAndHighlight = () => {
    if (codeRoot.value) {
        rawCode.value = codeRoot.value.innerText.trim();
        hljs.highlightElement(codeRoot.value);
    }
};

onMounted(async () => {
    await nextTick();
    extractAndHighlight();
});

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(rawCode.value);
        isCopied.value = true;
        setTimeout(() => (isCopied.value = false), 2000);
    } catch (err) {
        console.error('Copy failed', err);
    }
};

</script>

<template>
    <div class="wx-code shadow bg-white rounded">
        <div class="wx-code__header">
            <span class="wx-code__title">{{ props.title || props.lang || 'code' }}</span>
            <button
                @click="copyToClipboard"
                class="wx-code__copy-btn"
                :class="{ 'is-success': isCopied }"
            >
                {{ isCopied ? 'Copied!' : 'Copy' }}
            </button>
        </div>

        <pre class="wx-code__pre"><code
            ref="codeRoot"
            class="wx-code__content"
            :class="`language-${props.lang || 'plaintext'}`"
        ><slot /></code></pre>
    </div>
</template>

<style scoped lang="scss">
.wx-code {
    margin: 1rem 0;
    overflow: hidden;

    &__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 16px;
        background: var(--wx-white);
        border-bottom: 1px solid var(--wx-border-color);
    }

    &__title {
        font-size: 14px;
        color: var(--wx-dark);
        text-transform: uppercase;
        font-weight: 600;
    }

    &__copy-btn {
        background: var(--wx-light-gray);
        border: 1px solid var(--wx-border-color);
        color: var(--wx-dark);
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
        transition: 0.2s;

        &:hover { background: var(--wx-light-gray); }

        &.is-success { }
    }

    &__pre {
        margin: 0;
        padding: 16px;
        overflow-x: auto;
    }

    &__content {
        font-size: 14px;
        line-height: 1.6;
        background: transparent !important;
        padding: 0 !important;
    }
}
</style>
