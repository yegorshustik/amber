import { defineStore } from 'pinia';
import { computed, ref } from 'vue';
import { locales } from '@/config/locales';
import { WxLocalizedValue } from '@/types/locale';

export const useLocalesStore = defineStore('locales', () => {
    const list = ref(locales.list);

    const activeCode = ref(locales.default);

    const activeLocale = computed(() => {
        return list.value.find((l) => l.code === activeCode.value) || list.value[0];
    });

    function setLocale(code: string) {
        const localeExists = list.value.some((l) => l.code === code);

        if (localeExists) {
            activeCode.value = code;
        } else {
            console.error(`Локаль ${code} не найдена в конфигурации.`);
        }
    }

    function selectLocalizedValue(value : WxLocalizedValue|string|null, defaultValue ?: string) {
        if (!value) {
            return defaultValue
        }

        if (typeof value === 'string') {
            return value;
        }

        if (value.hasOwnProperty(locales.default) && value[locales.default] !== null && value[locales.default] !== undefined && value[locales.default] !== '') {
            return value[locales.default];
        }

        for (const locale of locales.list) {
            if (value.hasOwnProperty(locale.code) && value[locale.code] !== null && value[locale.code] !== '') {
                return value[locale.code];
            }
        }

        return defaultValue
    }

    return {
        list,
        activeCode,
        activeLocale,
        setLocale,
        selectLocalizedValue,
    };
});
