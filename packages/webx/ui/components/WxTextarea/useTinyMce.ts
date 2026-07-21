import { computed } from 'vue';
import type { WxFilemanagerFile } from '@/ui/widgets/WxFilemanager';
import { wxFilemanager } from '@/utils/filemanager';
import contentStyles from './editor-content.scss?inline';

export function useTinyMce(props: any) {
    const commonConfig = {
        base_url: '/libs/tinymce',
        suffix: '.min',
        language: 'en',
        apiKey : '',
        license_key: 'gpl',
        branding: false,
        promotion: false,
        skin: 'oxide',
        content_css: false,
        content_style: contentStyles,
        min_height: 350,
        autoresize_bottom_margin: 20,

        style_formats: [
            { title: 'Заголовок 1', block: 'h1' },
            { title: 'Заголовок 2', block: 'h2' },
            { title: 'Обычный текст', block: 'p' },
            { title: 'Акцентный текст', inline: 'span', classes: 'text-accent' }
        ],

        file_picker_callback: (callback: any, value: any, meta: any) => {
            if (meta.filetype === 'image') {
                wxFilemanager({multiple : false, zIndex : 1500}).then((image: WxFilemanagerFile) => {
                    callback(image.url, { });
                })
            }
        }
    };

    const presets = {
        minimal: {
            menubar: false,
            plugins: 'lists link image code wordcount table media',
            toolbar:
                'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright | bullist numlist | removeformat | image link table media | code',
        },
        maximal: {
            menubar: true,
            plugins:
                'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar:
                'undo redo | accordion | blocks fontfamily fontsize | bold italic underline strikethrough forecolor backcolor | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat | image link table media | code fullscreen preview',
        },
    };

    const editorConfig = computed(() => ({
        ...commonConfig,
        ...presets[props.preset || 'minimal'],
        placeholder: props.placeholder,
        readonly: props.disabled
    }));

    const focusEditor = (uid: string, localeCode: string) => {
        const editorId = `editor-${uid}-${localeCode}`;

        const editor = (window as any).tinymce?.get(editorId);

        if (editor) {
            editor.focus();
            // Опционально: ставим курсор в конец
            editor.selection.select(editor.getBody(), true);
            editor.selection.collapse(false);
        }
    };

    return { editorConfig, focusEditor };
}
