import { h, ref } from 'vue';
import WxAlertDialog from '../ui/components/WxAlertDialog';
import { mountTemporary } from './mount';

export type WxAlertOptions = {
    title?: string;
    okText?: string;
    size?: number;
    persistent?: boolean;
    closeOnOverlay?: boolean;
    closeOnEscape?: boolean;
};
export function wxAlert(message: string, options: WxAlertOptions = {}): Promise<void> {
    return new Promise<void>((resolve, reject) => {
        const open = ref(true);

        const { unmount } = mountTemporary(() =>
            h(WxAlertDialog as any, {
                modelValue: open.value,
                title: options.title,
                message,
                okText: options.okText,
                size: options.size,
                persistent: options.persistent,
                closeOnOverlay: options.closeOnOverlay,
                closeOnEscape: options.closeOnEscape,
                'onUpdate:modelValue': (v: boolean) => {
                    open.value = v;
                },
                onOk: () => {
                    open.value = false;
                    unmount();
                    resolve();
                },
                onCancel: () => {
                    open.value = false;
                    unmount();
                    //reject();
                },
            }),
        );
    });
}
