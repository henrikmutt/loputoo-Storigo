<script setup lang="ts">
import { CardTitle } from '../components/ui/card'

const props = defineProps<{
  room: any
}>()
</script>

<template>
  <div class="space-y-4">
    <CardTitle class="text-xl">{{ room.location }}</CardTitle>
    <p class="text-muted-foreground">{{ room.description }}</p>

    <div class="flex gap-4 text-sm">
      <p><strong>Daily Price:</strong> €{{ room.price_per_day }}</p>
      <p v-if="room.price_per_month"><strong>Monthly Price:</strong> €{{ room.price_per_month }}</p>
    </div>

  </div>
</template>

