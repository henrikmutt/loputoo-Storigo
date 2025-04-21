<script setup lang="ts">
import { ref } from 'vue'
defineProps<{ images: string[]; size: number }>()

const emit = defineEmits(['open-carousel'])

function handleClick(index: number) {
  emit('open-carousel', index)
}
</script>

<template>
  <div class="relative">
    <div class="grid grid-cols-4 grid-rows-2 gap-2 rounded-xl overflow-hidden">
      <div v-for="(image, index) in images.slice(0, 5)" :key="index" class="cursor-pointer overflow-hidden" :class="{
          'col-span-2 row-span-2': index === 0
        }"
        @click="handleClick(index)">
        <img :src="`/storage/${image}`" class="object-cover w-full h-full" />
      </div>
    </div>
  </div>
</template>
