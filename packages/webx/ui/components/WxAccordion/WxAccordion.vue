<script setup lang="ts">
import { ref, provide } from 'vue';

const props = defineProps<{
    multiple?: boolean;
}>();

// Храним ссылки на ref-ы активности дочерних элементов
const activeItemsRefs = ref<Set<any>>(new Set());

// Логика переключения
const toggleItem = (itemActiveRef: any) => {
    if (props.multiple) {
        // Логика для multiple не требует закрытия других
        itemActiveRef.value = !itemActiveRef.value;
        if (itemActiveRef.value) {
            activeItemsRefs.value.add(itemActiveRef);
        } else {
            activeItemsRefs.value.delete(itemActiveRef);
        }
    } else {
        // Если активен этот же элемент, просто переключаем его
        if (activeItemsRefs.value.has(itemActiveRef)) {
            itemActiveRef.value = !itemActiveRef.value;
            if (!itemActiveRef.value) {
                activeItemsRefs.value.delete(itemActiveRef);
            }
        } else {
            // Закрываем все остальные
            activeItemsRefs.value.forEach((itemRef: any) => {
                itemRef.value = false;
            });
            activeItemsRefs.value.clear();

            // Открываем текущий
            itemActiveRef.value = true;
            activeItemsRefs.value.add(itemActiveRef);
        }
    }
};

// Предоставляем функцию переключения
provide('toggleAccordionItem', toggleItem);
</script>

<template>
    <div class="wx-accordion bg-white shadow rounded overflow-hidden">
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">
.wx-accordion {
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}
</style>
