<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from './ui/card'
import Carousel from './ui/carousel/Carousel.vue'
import CarouselContent from './ui/carousel/CarouselContent.vue'
import CarouselItem from './ui/carousel/CarouselItem.vue'
import CarouselPrevious from './ui/carousel/CarouselPrevious.vue'
import CarouselNext from './ui/carousel/CarouselNext.vue'
import CardDescription from './ui/card/CardDescription.vue'

defineProps<{ room: any }>()
</script>

<template>

  <Card>
    <CardHeader class="p-0">
      <Carousel class="relative w-full max-w-full overflow-hidden">
        <CarouselContent>
          <CarouselItem v-for="(imageUrl, index) in room.images" :key="index" class="aspect-square">
            <img
              :src="imageUrl"
              :alt="`Room image ${index + 1}`"
              class="w-full h-full object-cover rounded-xl"
            />
          </CarouselItem>
        </CarouselContent>

        <CarouselPrevious @click.prevent class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/80 backdrop-blur-sm rounded-full hover:bg-white shadow opacity-0 group-hover:opacity-100 transition-opacity duration-200 disabled:opacity-0" />
        <CarouselNext @click.prevent class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/80 backdrop-blur-sm rounded-full hover:bg-white shadow opacity-0 group-hover:opacity-100 transition-opacity duration-200 disabled:opacity-0" />
      </Carousel>
    </CardHeader>

    <CardContent class="p-4">
      <div class="flex justify-between items-center">
        <CardTitle>{{ room.location }}</CardTitle>
      </div>
      <div v-if="room.width && room.length && room.height" class="flex gap-2">
        <CardDescription><span class="text-black font-bold">W:</span> {{ room.width }} m</CardDescription>
        /
        <CardDescription><span class="text-black font-bold">L:</span> {{ room.length }} m</CardDescription>
        /
        <CardDescription><span class="text-black font-bold">H:</span> {{ room.height }} m</CardDescription>
      </div>
      <p><strong>{{ room.price_per_month }} €</strong> / Month</p>
    </CardContent>
  </Card>
</template>
