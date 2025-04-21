<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useForm } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader } from './ui/card'
import { Button } from './ui/button'
import { Textarea } from './ui/textarea'
import { Input } from './ui/input'
import { Label } from './ui/label'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from './ui/carousel'
import CardDescription from './ui/card/CardDescription.vue'

const emit = defineEmits(['refresh'])

const props = defineProps<{
  roomId: number
  reviews: {
    id: number
    rating: number
    comment: string
    created_at: string
    from_user: { name: string }
  }[]
}>()

const showForm = ref(false)
const localReviews = ref([...props.reviews])

const form = useForm({
  to_room_id: props.roomId,
  rating: 5,
  comment: ''
})

function submit() {
  form.post('/reviews', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      showForm.value = false

      emit('refresh')
    }
  })
}
</script>

<template>
  <div class="mt-8">
    <div class="flex justify-between items-center mb-4">
      <CardHeader class="text-xl font-semibold">Reviews</CardHeader>
      <Button @click="showForm = !showForm" variant="outline">
        {{ showForm ? 'Cancel' : 'Rate this room' }}
      </Button>
    </div>

    <div v-if="showForm" class="mb-6 space-y-4">
      <div>
        <Label for="rating">Rating</Label>
        <Input type="number" id="rating" v-model.number="form.rating" min="1" max="5" />
      </div>

      <div>
        <Label for="comment">Comment</Label>
        <Textarea id="comment" v-model="form.comment" rows="3" placeholder="Write your review..." />
      </div>

      <Button @click="submit" :disabled="form.processing">Submit</Button>
    </div>

    <div v-if="localReviews.length">
      <Carousel>
        <CarouselContent class="-ml-2 md:-ml-4 gap-4">
          <CarouselItem
            v-for="review in localReviews"
            :key="review.id"
            class="py-4 md:pl-4 basis-1/2 md:basis-1/3 border rounded-xl shadow-sm"
          >
            <Card>
              <CardHeader class="p-1">{{ review.from_user?.name ?? 'Unknown' }}</CardHeader>
              <CardContent class="flex p-1">
                <p class="text-yellow-600 font-semibold">{{ review.rating }}/5</p>
                <CardDescription>{{ review.comment }}</CardDescription>
                <CardDescription class="text-xs">{{ new Date(review.created_at).toLocaleDateString() }}</CardDescription>
              </CardContent>
            </Card>
          </CarouselItem>
        </CarouselContent>
        <CarouselPrevious />
        <CarouselNext />
      </Carousel>
    </div>

    <div v-else class="text-gray-500 text-sm">
      No reviews yet.
    </div>
  </div>
</template>
