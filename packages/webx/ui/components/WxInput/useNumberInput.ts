export function useNumberInput(handleInput: (val: string, key: string) => void) {
    const clampValue = (val: string, min?: number, max?: number): string => {
        if (val === '') return '';
        let num = parseFloat(val);
        if (isNaN(num)) return '';

        if (min !== undefined && num < min) num = min;
        if (max !== undefined && num > max) num = max;

        return num.toString();
    };

    const onWheel = (e: WheelEvent, key: string, min?: number, max?: number) => {
        const el = e.target as HTMLInputElement;

        if (document.activeElement === el) {
            e.preventDefault();

            if (e.deltaY < 0) {
                el.stepUp();
            } else {
                el.stepDown();
            }

            // После шага проверяем границы на всякий случай (хотя stepUp/Down их учитывают)
            const clamped = clampValue(el.value, min, max);
            if (el.value !== clamped) {
                el.value = clamped;
            }

            handleInput(el.value, key);
        }
    };

    const onBlur = (e: FocusEvent, key: string, min?: number, max?: number) => {
        const el = e.target as HTMLInputElement;
        const clamped = clampValue(el.value, min, max);

        if (el.value !== clamped) {
            el.value = clamped;
            handleInput(clamped, key);
        }
    };

    const onKeyPress = (e: KeyboardEvent) => {
        const charCode = e.which ? e.which : e.keyCode;
        // Цифры, точка, запятая, минус
        if (charCode > 31 && (charCode < 48 || charCode > 57) && ![44, 45, 46].includes(charCode)) {
            e.preventDefault();
        }
    };

    return { onKeyPress, onWheel, onBlur, clampValue };
}
