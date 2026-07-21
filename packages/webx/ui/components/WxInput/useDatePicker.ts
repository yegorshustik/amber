import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';
import type { Instance } from 'flatpickr/dist/types/instance';
import { ref } from 'vue';

export function useDatePicker(handleInput: (val: string, key: string) => void) {
    const fpInstances = ref<Record<string, Instance>>({});

    const getConfig = (type: string) => {
        const baseConfig = {
            allowInput: true,
            monthSelectorType: 'static' as const,
        };

        if (type === 'time') {
            return { ...baseConfig, enableTime: true, noCalendar: true, dateFormat: 'H:i:S', enableSeconds: true, time_24hr: true };
        }
        if (type === 'datetime') {
            return { ...baseConfig, enableTime: true, dateFormat: 'Y-m-d H:i:S', enableSeconds: true, time_24hr: true };
        }
        return { ...baseConfig, enableTime: false, dateFormat: 'Y-m-d' };
    };

    const initDatepicker = (inputRefs: Record<string, HTMLInputElement>, type: string, currentValue: any) => {
        const dateTypes = ['date', 'time', 'datetime'];
        if (!dateTypes.includes(type)) return;

        Object.entries(inputRefs).forEach(([key, el]) => {
            if (el) {
                if (fpInstances.value[key]) fpInstances.value[key].destroy();

                // Извлекаем значение для конкретной локали
                const val = typeof currentValue === 'object' && currentValue !== null ? currentValue[key] : currentValue;

                fpInstances.value[key] = flatpickr(el, {
                    ...getConfig(type),
                    defaultDate: val || '',
                    onChange: (selectedDates, dateStr) => {
                        handleInput(dateStr, key);
                    },
                });
            }
        });
    };

    const destroyDatepicker = () => {
        Object.values(fpInstances.value).forEach((instance) => instance.destroy());
        fpInstances.value = {};
    };

    return { fpInstances, initDatepicker, destroyDatepicker };
}
