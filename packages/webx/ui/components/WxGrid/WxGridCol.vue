<script setup lang="ts">
import type { WxGridColProps } from './types';

const props = withDefaults(defineProps<WxGridColProps>(), {

});


const cssClasses = () => {
    const classes = ['grid-col'];

    if (props.xs) classes.push(`grid-col-xs-${props.xs}`);
    if (props.sm) classes.push(`grid-col-sm-${props.sm}`);
    if (props.md) classes.push(`grid-col-md-${props.md}`);
    if (props.lg) classes.push(`grid-col-lg-${props.lg}`);
    if (props.xl) classes.push(`grid-col-xl-${props.xl}`);
    if (props.xxl) classes.push(`grid-col-xxl-${props.xxl}`);

    return classes.join(' ');
}

</script>

<template>
    <div :class="cssClasses()">
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">
.grid-col {
    grid-column: span 12;
}

@each $name, $width in $grid-breakpoints {
    @container (min-width: #{$width}) {
        @for $i from 1 through 12 {
            .grid-col-#{$name}-#{$i} {
                grid-column: span #{$i};
            }
        }
    }
}
</style>
