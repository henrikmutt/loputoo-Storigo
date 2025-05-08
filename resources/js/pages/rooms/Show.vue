<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue'
import ShowImages from '@/components/ShowImages.vue'
import ShowDetails from '@/components/ShowDetails.vue'
import FullscreenCarousel from '@/components/FullscreenCarousel.vue'
import Reviews from '@/components/Reviews.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import Button from '@/components/ui/button/Button.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{ room: any, reviews: any[], hideRentButton?: boolean }>()
const room = props.room

const showCarousel = ref(false)
const startIndex = ref(0)

function openCarousel(index: number) {
  startIndex.value = index
  showCarousel.value = true
}

function closeCarousel() {
  showCarousel.value = false
}

function refreshRoom() {
  router.reload({
    only: ['room', 'reviews'],
    preserveUrl: true
  })
}

const form = useForm({
  room_id: room.id,
  total_price: room.price_per_month ?? 0,
})
</script>

<template>
  <Head :title="`Room in ${room.location}`" />
  <AppHeader />

  <div class="flex flex-col gap-16 max-w-5xl mx-auto px-4 py-10">
    <ShowImages :images="room.images" :size="room.size" @open-carousel="openCarousel" />

    <div class="flex flex-col gap-8">
      <ShowDetails :room="room" />
      <form @submit.prevent="form.post('/bookings')">
        <Button v-if="!props.hideRentButton" type="submit" :disabled="form.processing">
          Rent the room
        </Button>
      </form>
    </div>
    <Reviews :reviews="reviews" :room-id="room.id" @refresh="refreshRoom" />
  </div>

  <FullscreenCarousel
    v-if="showCarousel"
    :key="startIndex"
    :images="room.images"
    :start-index="startIndex"
    @close="closeCarousel"
  />
</template>
