import Inputmask from 'inputmask';
import { ref } from 'vue';

export function usePhoneMask(handleInput: (val: string, key: string) => void) {
    const maskInstances = ref<Record<string, Inputmask.Instance>>({});

    const initMask = (inputRefs: Record<string, HTMLInputElement>, type: string) => {
        if (type !== 'tel') return;

        // Итерируемся по объекту рефов (ключ — локаль, значение — элемент)
        Object.entries(inputRefs).forEach(([key, el]) => {
            if (el) {
                if (maskInstances.value[key]) maskInstances.value[key].remove();

                const im = new Inputmask('+48 999 999 999', {
                    placeholder: '+48 ___ ___ ___',
                    showMaskOnHover: true,
                    showMaskOnFocus: true,
                    // Используем стрелочную функцию, чтобы сохранить контекст или передать key
                    oncomplete: function (this: HTMLInputElement) {
                        handleInput(this.value, key);
                    },
                    onKeyValidation: function (this: HTMLInputElement) {
                        handleInput(this.value, key);
                    },
                });

                maskInstances.value[key] = im.mask(el);
            }
        });
    };

    const destroyMask = () => {
        Object.values(maskInstances.value).forEach((mask) => mask.remove());
        maskInstances.value = {};
    };

    return { maskInstances, initMask, destroyMask };
}
