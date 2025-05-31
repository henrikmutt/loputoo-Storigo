<script setup lang="ts">
import { ref } from 'vue'
import Carousel from './ui/carousel/Carousel.vue';
import CarouselContent from './ui/carousel/CarouselContent.vue';
import CarouselItem from './ui/carousel/CarouselItem.vue';
import CarouselPrevious from './ui/carousel/CarouselPrevious.vue';
import CarouselNext from './ui/carousel/CarouselNext.vue';
defineProps<{ images: string[]; size: number; room: any }>()

const emit = defineEmits(['open-carousel'])

function handleClick(index: number) {
  emit('open-carousel', index)
}
</script>

<template>
  <!-- MOBILE -->
  <div class="block md:hidden relative">
    <Carousel class="relative w-full max-w-full overflow-hidden">
        <CarouselContent>
          <CarouselItem v-for="(imageUrl, index) in images" :key="index" class="aspect-square">
            <img
              :src="`/storage/${imageUrl}`"
              :alt="`Room image ${index + 1}`"
              class="w-full h-full object-cover rounded-xl"
              @click="handleClick(index)"
            />
          </CarouselItem>
        </CarouselContent>

        <CarouselPrevious @click.prevent class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/80 backdrop-blur-sm rounded-full hover:bg-white shadow opacity-0 group-hover:opacity-100 transition-opacity duration-200 disabled:opacity-0" />
        <CarouselNext @click.prevent class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/80 backdrop-blur-sm rounded-full hover:bg-white shadow opacity-0 group-hover:opacity-100 transition-opacity duration-200 disabled:opacity-0" />
    </Carousel>
  </div>

  <!-- DESKTOP -->
  <div class="hidden md:block relative">
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
