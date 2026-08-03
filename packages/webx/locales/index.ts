// eslint-disable-next-line @typescript-eslint/ban-ts-comment
import translations from './translations.json';

export const $t = (key: string, params?: Record<string, string | number>): string => {
    const result = key.split('.').reduce((obj, k) => (obj && obj[k] !== undefined ? obj[k] : null), translations);
    if (result === null) {
        console.warn(`Translation key not found: ${key}`);
        return key;
    }

    if (params) {
        return Object.keys(params).reduce((str, p) => {
            return str.replace(new RegExp(`:${p}`, 'g'), String(params[p]));
        }, result);
    }

    return result;
};
