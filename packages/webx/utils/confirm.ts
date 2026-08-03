import { h, ref } from 'vue';
import { api } from '@/utils/api';
import WxConfirmDialog from '../ui/components/WxConfirmDialog';
import { mountTemporary } from './mount';

export type WxConfirmOptions = {
    title?: string;
    confirmText?: string;
    cancelText?: string;
    size?: number;
    persistent?: boolean;
    closeOnOverlay?: boolean;
    closeOnEscape?: boolean;
};

export function wxConfirm(message: string = 'Are you sure?', options: WxConfirmOptions = {}): Promise<typeof api> {
    return new Promise<typeof api>((resolve, reject) => {
        const open = ref(true);

        const { unmount } = mountTemporary(() =>
            h(WxConfirmDialog as any, {
                modelValue: open.value,
                title: options.title,
                message,
                confirmText: options.confirmText,
                cancelText: options.cancelText,
                size: options.size,
                persistent: options.persistent,
                closeOnOverlay: options.closeOnOverlay,
                closeOnEscape: options.closeOnEscape,
                'onUpdate:modelValue': (v: boolean) => {
                    open.value = v;
                },
                onConfirm: () => {
                    open.value = false;
                    unmount();
                    resolve(api);
                },
                onCancel: () => {
                    open.value = false;
                    unmount();
                    //reject(false);
                },
            }),
        );
    });
}
