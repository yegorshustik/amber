export interface WxTreeProps {
    modelValue?: any | any[];
    checked?: any | any[];
    endpoint?: string;
    treeData?: WxTreeItem[];
    draggable?: boolean;
    checkable?: boolean;
    selectable?: boolean;
    searchable?: boolean;
    stateId?: string;
    parentId?: number|null;
    type ?: 'default' | 'data-tree';
}

export interface WxTreeItem {
    id: number;
    parent_id: number;
    position: number;
    title: string;
    open?: boolean;
    children: WxTreeItem[];
}
