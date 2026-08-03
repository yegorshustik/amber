export const codeExamples: Record<string, string> = {
    'layout-routes': `
{
    path: '/',
    component: WxLayout,
    children: [
    ]
}
  `.trim(),

    'layout-page': `
<wx-page heading="Page heading">
    ...Page content
</wx-page>
  `.trim(),

    'layout-page-actions': `
<wx-page heading="Page heading">
    <template #actions>...Actions</template>

    ...Page content
</wx-page>
  `.trim(),

    'layout-page-full': `
<wx-page heading="Page heading" size="full">
    ...Page content
</wx-page>
  `.trim(),

    'layout-page-sidebar': `
<wx-page heading="Page heading">
    <template #sidebar>...Sidebar</template>

    ...Page content
</wx-page>
  `.trim(),

    'layout-page-full-example': `
<wx-page heading="Page heading" size="full|default">
    <template #actions>...Actions</template>
    <template #sidebar>...Sidebar</template>

    ...Page content
</wx-page>
  `.trim(),

    'layout-page-full-example-2': `
<wx-page heading="Page full example">
    <template #actions>...Actions</template>

    <template #sidebar>
        <wx-card title="Card heading" class="mb-16">
            ...Card content
        </wx-card>
        <wx-card title="Another card heading" class="mb-16">
            ...Card content
        </wx-card>
    </template>

    ...Page content
</wx-page>
  `.trim(),

    // Cards

    'card-minimal': `
<wx-card class="mb-16">
    ...Card content
</wx-card>
  `.trim(),

    'card-with-title': `
<wx-card title="Card title" class="mb-16">
    ...Card content
</wx-card>
  `.trim(),

    'card-with-title-actions': `
<wx-card title="Card title" class="mb-16">
    <template #actions>
        ...Actions
    </template>

    ...Card content
</wx-card>
  `.trim(),

    'card-with-footer': `
<wx-card class="mb-16">
    <template #footer>
        ...Footer
    </template>

    ...Card content
</wx-card>
  `.trim(),

    'card-with-sidebar': `
<wx-card class="mb-16">
    <template #sidebar>
        ...Sidebar
    </template>

    ...Card content
</wx-card>
  `.trim(),

    'card-with-all': `
<wx-card title="Card title" class="mb-16">
    <template #actions>
        ...Actions
    </template>
    <template #sidebar>
        ...Sidebar
    </template>
    <template #footer>
        ...Footer
    </template>

    ...Card content
</wx-card>
  `.trim(),

    // Dropdown

    dropdown: `
<wx-dropdown>
    <template #trigger>
        <strong>Click here</strong>
    </template>
    <template #body>
        <div class="fw-semibold">
            ...Dropdown body
        </div>
    </template>
</wx-dropdown>
  `.trim(),

    'dropdown-links': `
<wx-dropdown>
    <template #trigger>
        <strong>Click here</strong>
    </template>
    <template #body>
        <wx-dropdown-link>
            Link example
        </wx-dropdown-link>
        <wx-dropdown-link>
            Link example
        </wx-dropdown-link>
    </template>
</wx-dropdown>
  `.trim(),

    'dropdown-link-icon': `
<wx-dropdown>
    <template #trigger>
        <strong>Click here</strong>
    </template>
    <template #body>
        <wx-dropdown-link>
            <template #icon>
                <wx-icon name="person" />
            </template>
            Link example
        </wx-dropdown-link>
        <wx-dropdown-link>
            <template #icon>
                <wx-icon name="box-arrow-right" />
            </template>
            Link example
        </wx-dropdown-link>
    </template>
</wx-dropdown>
  `.trim(),

    // Buttons
    'button-default': `
<wx-buttons>
    <wx-button>Default button</wx-button>
    <wx-button theme="default">Default button</wx-button>
    <wx-button theme="default" class="hover">Hover button</wx-button>
    <wx-button theme="default" class="active">Active button</wx-button>
    <wx-button theme="default" class="disabled">Disabled button</wx-button>
</wx-buttons>
  `.trim(),
    'button-primary': `
<wx-buttons>
    <wx-button theme="primary">Primary button</wx-button>
    <wx-button theme="primary" class="hover">Primary button</wx-button>
    <wx-button theme="primary" class="active">Primary button</wx-button>
    <wx-button theme="primary" class="disabled">Primary button</wx-button>
    <wx-button theme="outline-primary">Primary button</wx-button>
    <wx-button theme="outline-primary" class="hover">Primary button</wx-button>
    <wx-button theme="outline-primary" class="active">Primary button</wx-button>
    <wx-button theme="outline-primary" class="disabled">Primary button</wx-button>
</wx-buttons>
  `.trim(),
    'button-success': `
<wx-buttons>
    <wx-button theme="success">Success button</wx-button>
    <wx-button theme="success" class="hover">Success button</wx-button>
    <wx-button theme="success" class="active">Success button</wx-button>
    <wx-button theme="success" class="disabled">Success button</wx-button>
    <wx-button theme="outline-success">Success button</wx-button>
    <wx-button theme="outline-success" class="hover">Success button</wx-button>
    <wx-button theme="outline-success" class="active">Success button</wx-button>
    <wx-button theme="outline-success" class="disabled">Success button</wx-button>
</wx-buttons>
  `.trim(),
    'button-danger': `
<wx-buttons>
    <wx-button theme="danger">Danger button</wx-button>
    <wx-button theme="danger" class="hover">Danger button</wx-button>
    <wx-button theme="danger" class="active">Danger button</wx-button>
    <wx-button theme="danger" class="disabled">Danger button</wx-button>
    <wx-button theme="outline-danger">Danger button</wx-button>
    <wx-button theme="outline-danger" class="hover">Danger button</wx-button>
    <wx-button theme="outline-danger" class="active">Danger button</wx-button>
    <wx-button theme="outline-danger" class="disabled">Danger button</wx-button>
</wx-buttons>
  `.trim(),
    'button-warning': `
<wx-buttons>
    <wx-button theme="warning">Warning button</wx-button>
    <wx-button theme="warning" class="hover">Warning button</wx-button>
    <wx-button theme="warning" class="active">Warning button</wx-button>
    <wx-button theme="warning" class="disabled">Warning button</wx-button>
    <wx-button theme="outline-warning">Warning button</wx-button>
    <wx-button theme="outline-warning" class="hover">Warning button</wx-button>
    <wx-button theme="outline-warning" class="active">Warning button</wx-button>
    <wx-button theme="outline-warning" class="disabled">Warning button</wx-button>
</wx-buttons>
  `.trim(),
    'button-secondary': `
<wx-buttons>
    <wx-button theme="secondary">Secondary button</wx-button>
    <wx-button theme="secondary" class="hover">Secondary button</wx-button>
    <wx-button theme="secondary" class="active">Secondary button</wx-button>
    <wx-button theme="secondary" class="disabled">Secondary button</wx-button>
    <wx-button theme="outline-secondary">Secondary button</wx-button>
    <wx-button theme="outline-secondary" class="hover">Secondary button</wx-button>
    <wx-button theme="outline-secondary" class="active">Secondary button</wx-button>
    <wx-button theme="outline-secondary" class="disabled">Secondary button</wx-button>
</wx-buttons>
  `.trim(),
    'button-light': `
<wx-buttons>
    <wx-button theme="light">Light button</wx-button>
    <wx-button theme="light" class="hover">Light button</wx-button>
    <wx-button theme="light" class="active">Light button</wx-button>
    <wx-button theme="light" class="disabled">Light button</wx-button>
    <wx-button theme="outline-light">Light button</wx-button>
    <wx-button theme="outline-light" class="hover">Light button</wx-button>
    <wx-button theme="outline-light" class="active">Light button</wx-button>
    <wx-button theme="outline-light" class="disabled">Light button</wx-button>
</wx-buttons>
  `.trim(),
    'button-info': `
<wx-buttons>
    <wx-button theme="info">Light button</wx-button>
    <wx-button theme="info" class="hover">Info button</wx-button>
    <wx-button theme="info" class="active">Info button</wx-button>
    <wx-button theme="info" class="disabled">Info button</wx-button>
    <wx-button theme="outline-info">Info button</wx-button>
    <wx-button theme="outline-info" class="hover">Info button</wx-button>
    <wx-button theme="outline-info" class="active">Info button</wx-button>
    <wx-button theme="outline-info" class="disabled">Info button</wx-button>
</wx-buttons>
  `.trim(),
    'button-size': `
<wx-buttons>
    <wx-button theme="primary" size="sm">SM</wx-button>
    <wx-button theme="primary" size="md">MD</wx-button>
    <wx-button theme="primary" size="lg">LG (default)</wx-button>
    <wx-button theme="primary" size="xl">XL</wx-button>
</wx-buttons>
  `.trim(),
    'actions-list': `
<wx-actions>
    <wx-action type="add" @click="() => null" />
    <wx-action type="edit" @click="() => null" />
    <wx-action type="remove" @click="() => null" />
    <wx-action type="sort" @click="() => null" />
    <wx-action type="link" @click="() => null" />
    <wx-action type="details" @click="() => null" />
</wx-actions>
  `.trim(),
    'actions-adaptive': `
<wx-actions type="adaptive">
    <template #desktop>
        <wx-buttons>
            <wx-button theme="primary">Create</wx-button>
            <wx-button theme="outline-primary">Edit</wx-button>
            <wx-button theme="outline-success">Update some information</wx-button>
            <wx-button theme="danger">Remove</wx-button>
        </wx-buttons>
    </template>
    <template #mobile>
        <wx-dropdown-link>Create</wx-dropdown-link>
        <wx-dropdown-link>Edit</wx-dropdown-link>
        <wx-dropdown-link>Update some information</wx-dropdown-link>
        <wx-dropdown-link>Remove</wx-dropdown-link>
    </template>
</wx-actions>
  `.trim(),
    'localization-switcher': `
<wx-locales @change="(locale) => someAction(locale)">
    <template #item="{ locale }">
        {{ locale.code }}
    </template>
</wx-locales>
  `.trim(),
    'localization-tabs': `
<wx-locales type="tabs" @change="(locale) => someAction(locale)">
    <template #item="{ locale }">
        {{ locale.code }}
    </template>
</wx-locales>
  `.trim(),
    tabs: `
<wx-tabs>
    <wx-tab name="General">
        <wx-card>...General</wx-card>
    </wx-tab>
    <wx-tab name="Secondary">
        <wx-card>...Secondary</wx-card>
    </wx-tab>
    <wx-tab name="Secondary some">
        <wx-card>...Secondary some</wx-card>
    </wx-tab>
    <wx-tab name="Secondary another">
        <wx-card>...Secondary another</wx-card>
    </wx-tab>
</wx-tabs>
  `.trim(),
    'tabs-vertical': `
<wx-tabs type="vertical">
    <wx-tab name="General">
        <wx-card>...General</wx-card>
    </wx-tab>
    <wx-tab name="Secondary">
        <wx-card>...Secondary</wx-card>
    </wx-tab>
    <wx-tab name="Secondary some">
        <wx-card>...Secondary some</wx-card>
    </wx-tab>
    <wx-tab name="Secondary another">
        <wx-card>...Secondary another</wx-card>
    </wx-tab>
</wx-tabs>
  `.trim(),
    'alerts-inline': `
<wx-alert type="info | success | warning | danger">
    Inline alert message
</wx-alert>
  `.trim(),
    dialog: `
<wx-dialog size="1000" v-model="showModal" title="Dialog title">
    <template #sidebar>
        This is optional sidebar
    </template>
    <template #footer>
        This is optional footer
    </template>
    <template #actions>
        ...Actions
    </template>

    This is dialog content
</wx-dialog>
  `.trim(),
    'side-panel': `
<wx-side-panel size="800" v-model="showSidePanel" title="Side panel title">
    <template #sidebar>
        This is optional sidebar
    </template>
    <template #footer>
        This is optional footer
    </template>
    <template #actions>
        ...Actions
    </template>

    <p v-for="i in 100">This is side panel content</p>
</wx-side-panel>
  `.trim(),

    'confirm-alert-dialog': `
import { wxConfirm, wxAlert } from '@/utils';

await wxAlert('Saved');

try {
    await wxConfirm('Are you sure?');
    // confirmed
} catch {
    // cancelled
}

wxConfirm('Are you sure?').then(() => ...))
wxAlert('Something was happened').then(() => ...)

  `.trim(),
    snackbar: `
import { wxSnackbar } from '@/utils';

wxSnackbar('Saved', { type : 'info' }) // wxSnackbar('Saved')

wxSnackbar('Saved', { type : 'success' })

wxSnackbar('Saved', { type : 'warning' })

wxSnackbar('Saved', { type : 'danger' })
  `.trim(),
    'form-inputs-text': `
<wx-input name="test" placeholder="Placeholder" value="Test value" />

<wx-input name="test" placeholder="Placeholder" v-model="testModel" />

<wx-input name="test" placeholder="Placeholder" localized />

<wx-input name="test" placeholder="Placeholder" localized v-model="testModelLocalized" />

<wx-input name="test" placeholder="Placeholder" localized :value="testModelLocalizedDefault" />
  `.trim(),

    'form-inputs-tel': `
<wx-input name="test" type="tel"  value="" />
<wx-input name="test" type="tel" localized value="" />
  `.trim(),

    accordion: `
<wx-accordion>
    <wx-accordion-item title="Section 1" active>
        <div style="padding: 1rem">Section 1 content</div>
    </wx-accordion-item>
    <wx-accordion-item title="Section 2">
        <div style="padding: 1rem">Section 2 content</div>
    </wx-accordion-item>
</wx-accordion>
  `.trim(),

    'accordion-multiple': `
<wx-accordion multiple>
    <wx-accordion-item title="Section 1" active>
        <div style="padding: 1rem">Section 1 content</div>
    </wx-accordion-item>
    <wx-accordion-item title="Section 2">
        <div style="padding: 1rem">Section 2 content</div>
    </wx-accordion-item>
</wx-accordion>
  `.trim(),

    'form-inputs-datetime': `
<wx-input type="datetime" name="..." value="" />
<wx-input type="date" name="..." value="" />
<wx-input type="time" name="..." value="" />

<wx-input type="datetime" v-model="..." />
<wx-input type="date" v-model="..." />
<wx-input type="time" v-model="..." />
  `.trim(),

    'form-inputs-color': `
<wx-input type="color" name="..." value="" />
<wx-input type="color" name="..." :value="..." />
<wx-input type="color" v-model="..." />
  `.trim(),

    'form-inputs-number': `
<wx-input type="number" min="..." max="..." step="..." name="..." value="" />
<wx-input type="number" min="..." max="..." step="..." name="..." :value="..." />
<wx-input type="number" min="..." max="..." step="..." v-model="..." />
  `.trim(),

    'form-inputs-textarea': `
<wx-textarea name="..." placeholder="..." value="..." />
<wx-textarea placeholder="..." v-model="..." />
<wx-textarea localized name="..." placeholder="..." value="..." />
<wx-textarea localized placeholder="..." v-model="..." />
<wx-textarea wysiwyg name="..." placeholder="..." value="..." />
<wx-textarea wysiwyg placeholder="..." v-model="..." />
<wx-textarea wysiwyg localized name="..." placeholder="..." value="..." />
<wx-textarea wysiwyg localized placeholder="..." v-model="..." />
  `.trim(),

    'form-select-ts': `
const testSelectOptions = [
    { value: 1, label: 'Электроника' },
    { value: 2, label: 'Одежда' },
    { value: 3, label: 'Дом и сад' },
    { value: 4, label: 'Автотовары' }
];
  `.trim(),
    'form-select-vue': `
<wx-select name="test-select" :options="testSelectOptions" />
<wx-select :options="testSelectOptions" :value="..." />
<wx-select :options="testSelectOptions" v-model="..." />
<wx-select multiple :options="testSelectOptions" />
<wx-select multiple :options="testSelectOptions" :value="..." />
<wx-select multiple :options="testSelectOptions" v-model="..." />
<wx-select searchable :options="testSelectOptions" />
  `.trim(),
    'form-checkboxes': `
<wx-check-group>
    <wx-check name="..." checked label="..." />
    <wx-check name="..." label="..." />
</wx-check-group>

<wx-check-group>
    <wx-check v-model="..." label="..." />
</wx-check-group>

<wx-check-group>
    <wx-check name="..." value="..." checked label="..." />
</wx-check-group>

<wx-check-group>
    <wx-check v-model="..." value="..." label="..." />
</wx-check-group>

<wx-check-group>
    <wx-check name="..." value="..." type="radio" label="..." />
    <wx-check name="..." value="..." type="radio" label="..." />
</wx-check-group>

<wx-check-group>
    <wx-check name="..." value="..." checked type="radio" label="..." />
    <wx-check name="..." value="..." type="radio" label="..." />
</wx-check-group>

<wx-check-group>
    <wx-check v-model="..." value="..." type="radio" label="..." />
    <wx-check v-model="..." value="..." type="radio" label="..." />
</wx-check-group>

<wx-check-group>
    <wx-check type="switch" name="..." checked label="..." />
</wx-check-group>

<wx-check-group>
    <wx-check type="switch" v-model="..." label="..." />
</wx-check-group>
  `.trim(),
    'api-request': `

const response = ref({ });

const makeRequest = async () => {
    await api.get('test')
        .then(data => {
            response.value = data;
        })
        .catch(err => {
            console.error(err);
        });
}

  `.trim(),
    form: `

<wx-form action="/test-form" id="test-form" class="mb-16" @success="(data: any) => (response = data)">
    <wx-card>
        <wx-input type="text" name="name" value="" placeholder="Example" />
    </wx-card>
</wx-form>

  `.trim(),
    'tree-simple': `
<wx-tree state-id="tree-example" endpoint="/filemanager/directories"></wx-tree>
  `.trim(),
    'tree-checkable': `
<wx-tree state-id="tree-example-2" v-model="treeExample" endpoint="/filemanager/directories" draggable checkable></wx-tree>
  `.trim(),
    'tree-selectable': `
<wx-tree state-id="tree-example-3" v-model="treeExample2" endpoint="/filemanager/directories" draggable selectable></wx-tree>
  `.trim(),
    'tree-data-tree': `
<wx-tree state-id="tree-example-4" type="data-tree" v-model="treeExample2" endpoint="/filemanager/directories" draggable>
    <template #title="{ node, stat }">
        {{ node.title }} (id: {{ node.id }})
    </template>
    <template #actions="{ node, stat }">
        <wx-actions type="adaptive" align="end">
            <template #desktop>
                <wx-action type="add" />
                <wx-action type="edit" />
                <wx-action type="remove" />
            </template>
            <template #mobile>
                <wx-dropdown-link>Create</wx-dropdown-link>
                <wx-dropdown-link>Edit</wx-dropdown-link>
                <wx-dropdown-link>Remove</wx-dropdown-link>
            </template>
        </wx-actions>
    </template>
</wx-tree>
  `.trim(),
    sortable: `
<wx-sortable v-model="items">
    <template #content="{ item }: { item: ...Type }">
        {{ item.title }}
    </template>
    <template #actions="{ item }: { item: ...Type }">
        <wx-actions>
            <wx-action type="sort" class="handle" />
        </wx-actions>
    </template>
</wx-sortable>
  `.trim(),

    'form-select-tree': `
<wx-select-tree state-id="..." endpoint="..."/>

<wx-select-tree state-id="..." multiple endpoint="..."/>

<wx-select-tree state-id="..." v-model="..." endpoint="..."/>

<wx-select-tree state-id="..." v-model="..." multiple endpoint="..."/>

<wx-select-tree state-id="..." v-model="..." endpoint="..."/>

<wx-select-tree state-id="..." v-model="..." multiple endpoint="..."/>

<wx-select-tree state-id="..." name="..." :value="..." endpoint="..."/>

<wx-select-tree state-id="..." name="..." :value="..." multiple endpoint="..."/>
  `.trim(),

    'form-single-image': `
<wx-input-image name="..." />

<wx-input-image name="..." :value="..." />

<wx-input-image name="..." localized />

<wx-input-image name="..." localized :value="..." />

<wx-input-image v-model="..." />

<wx-input-image localized v-model="..." />
  `.trim(),

    filemanager: `
wxFilemanager().then((file) => {
    // ...
})


wxFilemanager({
    multiple: true
}).then((files) => {
    // ....
})


  `.trim(),
    'form-multiple-image': `
<wx-input-image name="..." multiple />

<wx-input-image name="..." multiple value="..." />

<wx-input-image multiple v-model="..." />
  `.trim(),

    'form-multiple-image-localized': `
<wx-input-image name="..." multiple localized />

<wx-input-image name="..." multiple localized value="..." />

<wx-input-image multiple localized v-model="..." />
  `.trim(),

    'fieldset': `
<wx-fieldset legend="...">
    ...
</wx-fieldset>

<wx-fieldset>
    ...
</wx-fieldset>
  `.trim(),

    'form-single-file': `
<wx-input-file name="..." />

<wx-input-file name="..." :value="..." />

<wx-input-file v-model="..." />
  `.trim(),

    'form-single-file-localized': `
<wx-input-file localized name="..." />

<wx-input-file localized name="..." :value="..." />

<wx-input-file localized v-model="..." />
  `.trim(),

    'form-multiple-files': `
<wx-input-file multiple name="..." />

<wx-input-file multiple name="..." :value="..." />

<wx-input-file multiple v-model="..." />
  `.trim(),

    'form-multiple-files-localized': `
<wx-input-file multiple localized name="..." />

<wx-input-file multiple localized name="..." :value="..." />

<wx-input-file multiple localized v-model="..." />
  `.trim(),

    'tags': `
<wx-tags endpoint="..." placeholder="..." />
<wx-tags endpoint="..." name="..." value="..." />
<wx-tags endpoint="..." v-model="..." />
  `.trim(),

    'seo': `
<wx-seo name="..." />
<wx-seo name="..." value="..." />
<wx-seo name="..." v-model="..." />
  `.trim(),

    'datatable': `
<wx-datatable endpoint="test-datatable" searchable heading="Branches" selectable="checkbox" persist="test" sortable @sorted="(data) => log(data)">
    <template #selected="{ item } : { item : TestDatatableItem }">
        {{ item.description }}
    </template>

    <template #row="{ item } : { item : TestDatatableItem }">
        <wx-datatable-column sortable size="max-content" id="id" title="ID" >
            {{ item.id }}
        </wx-datatable-column>
        <wx-datatable-column sortable size="auto" id="description" title="Description">
            {{ item.description }}
        </wx-datatable-column>
        <wx-datatable-column size="max-content" id="actions">
            <wx-action type="sort" class="handle" />
        </wx-datatable-column>
    </template>
</wx-datatable>
  `.trim(),

    'entity-card': `
<wx-entity-card
    title="Entity heading"
    image="https://picsum.photos/200"
    :params="[{ option : 'Option', value : 'Value' }, { option : 'Option 2', value : 'Value 2' }]" />

<wx-entity-card
    title="Entity heading"
    image="https://picsum.photos/48"
    :params="[{ option : 'Option', value : 'Value' }, { option : 'Option 2', value : 'Value 2' }]">
    <template #actions>
        <wx-actions>
            <wx-action type="edit" />
            <wx-action type="remove" />
        </wx-actions>
    </template>
</wx-entity-card>
  `.trim(),

    'page-composer': `
 <wx-page-composer name="..." v-model="..." />
  `.trim(),

    'grid': `
<wx-grid class="mb-16">
    <wx-grid-col md="7">Column 1</wx-grid-col>
    <wx-grid-col md="5">Column 2</wx-grid-col>
</wx-grid>

<wx-grid>
    <wx-grid-col md="6">Column 1</wx-grid-col>
    <wx-grid-col md="6">Column 2</wx-grid-col>
</wx-grid>
  `.trim(),

    'video': `
<wx-video name="..." />
<wx-video name="..." :value="..." />
<wx-video name="..." v-model="..." />
<wx-video name="..." localized />
<wx-video name="..." localized :value="..." />
<wx-video name="..." localized v-model="..." />
<wx-video name="..." preview :value="..." />
<wx-video name="..." preview localized :value="..." />
  `.trim(),

    'heading': `
<wx-heading name="..." />
<wx-heading name="..." :value="..." />
<wx-heading name="..." v-model="..." />
<wx-heading name="..." preview v-model="..." />
  `.trim(),
};
