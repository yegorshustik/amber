import { ref } from 'vue';
import { api } from '@/utils';

export function useFileUploader() {
    const uploadProgress = ref(0);

    const uploadFiles = (files, url: string, options: Record<string, string | number> = {}): Promise<any> => {
        return new Promise((resolve, reject) => {
            if (!files || files.length === 0) return reject('No files');

            const formData = new FormData();
            Array.from(files).forEach((file : any) => formData.append('files[]', file));

            Object.entries(options).forEach(([key, value]) => {
                formData.append(key, String(value));
            });

            const xhr = new XMLHttpRequest();

            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    uploadProgress.value = Math.round((e.loaded / e.total) * 100);
                }
            };

            xhr.onload = () => {
                uploadProgress.value = 0;
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(JSON.parse(xhr.responseText));
                } else {
                    reject(new Error(xhr.statusText));
                }
            };

            xhr.onerror = () => {
                uploadProgress.value = 0;
                reject(new Error('Network Error'));
            }

            xhr.open('POST', url);
            xhr.setRequestHeader("Authorization", "Bearer " + api.getToken());
            xhr.send(formData as FormData);
        });
    };

    return { uploadFiles, uploadProgress };
}
