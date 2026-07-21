import type { RegistryComposerComponent } from './registry';

export interface WxPageComposerResponse {
    raw?: WxPageComposerComponent[];
}

export interface WxPageComposerComponent {
    id: string;
    name: string;
    content?: Record<string, any>;
    children?: WxPageComposerComponent[];
}

export interface WxPageComposerContext {
    sortingMode: () => void;
    addComponent: (children: WxPageComposerComponent[], after ?: WxPageComposerComponent|null) => void;
    copyComponent: (component : WxPageComposerComponent[]) => void;
    pasteComponent: (action : 'after' | 'append', children: WxPageComposerComponent[], after : WxPageComposerComponent, components : WxPageComposerComponent|WxPageComposerComponent[]) => void;
    findComponent: (name : string) => RegistryComposerComponent;
    startRemoving: (component : WxPageComposerComponent) => void;
    removingId;
    startEditing: (component : WxPageComposerComponent) => void;
    editingId;
}

export interface WxPageComposerProps {
    name: string;
    value?: WxPageComposerComponent[];
    modelValue?: WxPageComposerComponent[];
}

export interface WxPageComposerCanvasProps {
    children : WxPageComposerComponent[];
}

export interface WxPageComposerComponentProps {
    component : WxPageComposerComponent;
    registry : RegistryComposerComponent
}

export interface WxPageComposerContentProps {
    component: WxPageComposerComponent;
    edit: boolean;
}


export interface WxPageComposerSortingProps {
    modelValue: WxPageComposerComponent[];
}
