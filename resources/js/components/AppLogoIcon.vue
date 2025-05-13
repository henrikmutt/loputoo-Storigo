<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img src="/images/Storigo_noBG.png" alt="Storigo logo" class="size-8 block dark:hidden aspect-square">
    <img src="/images/Storigo_white_noBG.png" alt="Storigo logo" class="size-8 hidden dark:block aspect-square">
</template>
