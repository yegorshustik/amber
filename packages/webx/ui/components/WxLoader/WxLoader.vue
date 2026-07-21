<script setup lang="ts">
</script>

<template>

    <div class="loader">
        <div class="loader__animation"><div class="loader__first"></div><div class="loader__second"></div></div>
    </div>
</template>

<style scoped lang="scss">

.loader {
    --wx-loader-background : rgba(var(--wx-white-rgb), .5);
    --wx-loader-color : var(--wx-primary);

    z-index: 1000000;
    position: fixed;
    top:0;
    right:0;
    bottom: 0;
    left: 0;
    background-color: var(--wx-loader-background);

    &__animation {
        width: 40px;
        height: 40px;

        position: absolute;
        top:50%;
        left:50%;
        transform: translate(-50%, -50%);
    }

    &__first,
    &__second {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color:var(--wx-loader-color);
        opacity: 0.6;
        position: absolute;
        top: 0;
        left: 0;
        animation: sk-bounce 2.0s infinite ease-in-out;
    }
    &__second {
        animation-delay: -1.0s;
    }
}

@keyframes sk-bounce {
    0%, 100% {
        transform: scale(0.0);
    }
    50% {
        transform: scale(1.0);
    }
}
</style>
