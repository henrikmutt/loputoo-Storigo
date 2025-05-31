<script setup lang="ts">
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from './ui/carousel'
import Button from '@/components/ui/button/Button.vue'
import { X } from 'lucide-vue-next'

const props = defineProps<{
  images: string[],
  startIndex: number
}>()

const emit = defineEmits(['close'])
</script>

<template>
  <div class="fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center">
    <Button variant="secondary" class="absolute right-4 top-10 lg:right-10 z-50"
        @click="emit('close')">
      <X />
    </Button>
    <div class="relative w-full max-w-4xl">


      <Carousel class="w-full">
        <CarouselContent>
          <CarouselItem
            v-for="(image, index) in images"
            :key="index"
            class="aspect-video"
          >
            <img :src="`/storage/${image}`" class="w-full h-full object-contain" />
          </CarouselItem>
        </CarouselContent>
        <CarouselPrevious class="absolute left-4 top-1/2 -translate-y-1/2 z-10 bg-white/80 rounded-full" />
        <CarouselNext class="absolute right-4 top-1/2 -translate-y-1/2 z-10 bg-white/80 rounded-full" />
      </Carousel>
    </div>
  </div>
</template>
