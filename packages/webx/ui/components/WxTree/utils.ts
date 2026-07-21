import type { Stat } from '@he-tree/tree-utils';
import { ref } from 'vue';
import type { ApiResponse } from '@/types/api';
import { api } from '@/utils';
import type { WxTreeItem } from './types';

export async function loadTree(endpoint : string, force : boolean = false) {
    try {
        const response = force
            ? await api.get<ApiResponse<WxTreeItem[]>>(endpoint, {
                  action: 'load-tree',
              })
            : await api.getCached<ApiResponse<WxTreeItem[]>>(endpoint, {
                  action: 'load-tree',
              });
        return response.data;
    } catch (e) {}
}

export function findNodeById(nodes : WxTreeItem[], node : number) {
    for (const n of nodes) {
        if (n.id === node) {
            return n;
        }
        if (n.children) {
            const found = findNodeById(n.children, node);
            if (found) {
                return found;
            }
        }
    }
}

export function getExpandedKeys(state_id : string) {
    if (!state_id) {
        return ref([]);
    }

    return ref(JSON.parse(localStorage.getItem(state_id) || '[]'))
}

export function setExpandedKeys(state_id : string, keys : []) {
    if (state_id) {
        localStorage.setItem(state_id, JSON.stringify(keys));
    }
}

export async function restoreExpandedState(state_id : string, tree : any, nodes: WxTreeItem[]) {
    if (!state_id) {
        return;
    }

    for (const node of nodes) {
        if (getExpandedKeys(state_id).value.includes(node.id)) {
            const stat = tree.value.getStat(node);
            stat.open = true;

            if (node.children && node.children.length > 0) {
                await restoreExpandedState(state_id, tree, node.children);
            }
        }
    }
}

export function updateExpandedState(state_id : string, node : WxTreeItem, stat : Stat<WxTreeItem>) {
    if (!state_id) {
        return;
    }
    const expandedKeys = getExpandedKeys(state_id);
    if (stat.open) {
        if (!expandedKeys.value.includes(node.id)) {
            expandedKeys.value.push(node.id);
        }
    }
    else {
        const idsToRemove = [node.id, ...getAllChildIds(node)];
        expandedKeys.value = expandedKeys.value.filter((id : number) => !idsToRemove.includes(id));
    }

    setExpandedKeys(state_id, expandedKeys.value)
}

export function getAllChildIds(node : WxTreeItem, ids : [] = []) {
    if (node.children && node.children.length > 0) {
        ids.push(node.id as never);
        node.children.forEach((child : WxTreeItem) => {
            ids.push(child.id as never);
            getAllChildIds(child, ids);
        });
    }
    return ids;
}


export function eachDraggable(node : Stat<WxTreeItem>, parent : number = 0) {
    return node.data.parent_id > parent;
}

export function eachDroppable(node: Stat<WxTreeItem>, parent : number = 0) {
    return node.data.parent_id > parent;
}

export function  flattenTreeToMap (data, map = {}) {
    data.forEach(node => {
        map[node.id] = { id: node.id, parent_id: node.parent_id, title: node.title };

        if (node.children && node.children.length > 0) {
            flattenTreeToMap(node.children, map);
        }
    });
    return map;
}
