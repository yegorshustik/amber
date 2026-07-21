import { h, ref } from 'vue';
import type { ApiResponse } from '@/types/api';
import { api } from '@/utils/api';
import type { WxFilemanagerFile } from '../ui/widgets/WxFilemanager';
import { WxFilemanager } from '../ui/widgets/WxFilemanager';
import { mountTemporary } from './mount';

export type WxFilemanagerOptions = {
    multiple?: boolean;
    zIndex?: number;
};

export function wxFilemanager(options: WxFilemanagerOptions = {}): Promise<WxFilemanagerFile|WxFilemanagerFile[]> {
    return new Promise<WxFilemanagerFile|WxFilemanagerFile[]>((resolve, reject) => {
        const open = ref(true);

        const { unmount } = mountTemporary(() =>
            h(WxFilemanager as any, {
                modelValue: open.value,
                multiple: options.multiple ?? false,
                zIndex: options.zIndex ?? null,

                'onUpdate:modelValue': (v: boolean) => {
                    open.value = v;
                },

                onCancel: () => {
                    open.value = false;
                    unmount();
                },

                onSelect: (file: WxFilemanagerFile) => {
                    //console.log('onSelect - start');
                    open.value = false;
                    //console.log('onSelect - open - off');
                    unmount();
                    //console.log(file);
                    //console.log('onSelect - unmount');
                    resolve(file as WxFilemanagerFile | WxFilemanagerFile[]);
                    //console.log('onSelect - resolve');
                },
            }),
        );
    });
}

export function wxLoadFile(id : number|number[]) : Promise<WxFilemanagerFile|WxFilemanagerFile[]>
{
    return new Promise<WxFilemanagerFile|WxFilemanagerFile[]>(async (resolve, reject) => {
        const response = await api.getCached<ApiResponse<WxFilemanagerFile>>('filemanager/files/file', { id: typeof id === 'number' ? id : id.join(',') });

        resolve(response.data);
    })
}
