<script setup lang="ts">
import Card from './ui/card/Card.vue';
import CardContent from './ui/card/CardContent.vue';
import CardTitle from './ui/card/CardTitle.vue';
import Tooltip from './ui/tooltip/Tooltip.vue';
import TooltipContent from './ui/tooltip/TooltipContent.vue';
import TooltipProvider from './ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from './ui/tooltip/TooltipTrigger.vue';

defineProps<{
  total: number,
}>()
</script>

<template>
  <div class="w-full">
    <TooltipProvider>
    <Tooltip>
      <TooltipTrigger class="w-full h-full gap-4 rounded-xl border dark:bg-gray-900 p-4 md:p-6 shadow-md">
        <Card class="flex flex-col gap-4 md:gap-6">
            <CardTitle class="text-xl font-normal p-0">Earnings</CardTitle>
            <CardContent class="text-2xl md:text-4xl font-bold text-green-600">
                € {{ total.toFixed(2) }}
            </CardContent>
        </Card>
      </TooltipTrigger>
      <TooltipContent>
        <p>All your earnings in total.</p>
      </TooltipContent>
    </Tooltip>
  </TooltipProvider>
  </div>
</template>
