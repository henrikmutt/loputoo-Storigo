<script setup lang="ts">
import Card from './ui/card/Card.vue';
import CardContent from './ui/card/CardContent.vue';
import CardTitle from './ui/card/CardTitle.vue';

defineProps<{
  total: number,
}>()
</script>

<template>
  <div class="w-full">
    <Card class="h-full flex flex-col gap-4 rounded-xl border dark:bg-gray-900 p-4 md:p-6 shadow-md">
        <CardTitle class="text-xl font-normal p-0">Total spendings</CardTitle>
        <CardContent class="text-2xl md:text-4xl font-bold text-red-600">
            € {{ typeof total === 'number' ? total.toFixed(2) : '0.00' }}
        </CardContent>
    </Card>
  </div>
</template>
