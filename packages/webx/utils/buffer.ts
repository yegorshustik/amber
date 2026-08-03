import { wxSnackbar } from '@/utils/snackbar';
import { $t } from '@/locales';

const bufferEvent = new CustomEvent('wxBufferUpdated', {
    detail: {},
    bubbles: true,
    cancelable: true
});


export function wxBuffer()
{
    const key = 'wx-buffer';

    const push = (data) => {
        localStorage.setItem(key, JSON.stringify(data));
        window.dispatchEvent(bufferEvent);
    }

    const pull = () => {
        const storage = localStorage.getItem(key);

        if (!storage) {
            return null
        }

        return JSON.parse(storage);
    }

    const clear = () => {
        localStorage.removeItem(key)
        window.dispatchEvent(bufferEvent);
        return ;
    }

    return { push, pull, clear }
}
