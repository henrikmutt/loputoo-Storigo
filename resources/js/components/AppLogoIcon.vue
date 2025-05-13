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
    <img src="/images/Storigo_noBG.png" alt="Storigo logo" class="size-8 aspect-square">
</template>
