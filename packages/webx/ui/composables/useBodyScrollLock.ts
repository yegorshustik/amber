import { onUnmounted, watch } from 'vue';

let lockCount = 0;
let previousOverflow: string | null = null;

function lockBody() {
    if (lockCount === 0) {
        previousOverflow = document.body.style.overflow ?? '';
        document.body.style.overflow = 'hidden';
    }
    lockCount += 1;
}

function unlockBody() {
    lockCount = Math.max(0, lockCount - 1);
    if (lockCount === 0) {
        document.body.style.overflow = previousOverflow ?? '';
        previousOverflow = null;
    }
}

export function useBodyScrollLock(locked: () => boolean) {
    let isCurrentlyLocked = false;

    watch(
        () => locked(),
        (shouldLock) => {
            if (shouldLock && !isCurrentlyLocked) {
                lockBody();
                isCurrentlyLocked = true;
                return;
            }

            if (!shouldLock && isCurrentlyLocked) {
                unlockBody();
                isCurrentlyLocked = false;
            }
        },
        { immediate: true },
    );

    onUnmounted(() => {
        if (isCurrentlyLocked) {
            unlockBody();
            isCurrentlyLocked = false;
        }
    });
}
