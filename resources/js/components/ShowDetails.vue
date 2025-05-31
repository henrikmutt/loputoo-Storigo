<script setup lang="ts">
import { CardTitle } from '../components/ui/card'
import { Badge } from '@/components/ui/badge'

const props = defineProps<{
  room: any
}>()
</script>

<template>
  <div class="flex justify-between items-center">
    <div class="flex flex-col gap-4 w-full md:w-2/3">
      <CardTitle>{{ room.location }}</CardTitle>

      <!-- MOBILE DIMENSIONS -->
      <div class="md:hidden flex justify-evenly gap-2 w-full">
        <div class="flex flex-col">
          <p>Length:</p>
          <Badge>{{ room.length }} m</Badge>
        </div>
        <div class="flex flex-col">
          <p>Width:</p>
          <Badge>{{ room.width }} m</Badge>
        </div>
        <div class="flex flex-col">
          <p>Height:</p>
          <Badge>{{ room.height }} m</Badge>
        </div>
      </div>

      <p class="text-muted-foreground">{{ room.description }}</p>
      <div class="flex gap-4 pt-6 text-sm">
        <p v-if="room.price_per_day">{{ room.price_per_day }}€ / Day</p>
        <CardTitle v-if="room.price_per_month">{{ room.price_per_month }} € / Month</CardTitle>
      </div>
    </div>
    
    <!-- DESKTOP DIMENSIONS -->
    <div class="hidden md:block w-1/3">
      <div class="flex flex-col items-end gap-4">
        <div class="flex gap-2 justify-between">
          <p>Length:</p>
          <Badge>{{ room.length }} m</Badge>
        </div>
        <div class="flex gap-2 justify-between">
          <p>Width:</p>
          <Badge>{{ room.width }} m</Badge>
        </div>
        <div class="flex gap-2 justify-between">
          <p>Height:</p>
          <Badge>{{ room.height }} m</Badge>
        </div>
      </div>
    </div>
  </div>
</template>

