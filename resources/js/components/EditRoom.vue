<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '../components/ui/dialog'
import { Input } from '../components/ui/input'
import { Label } from '../components/ui/label'
import { Button } from '../components/ui/button'
import { Textarea } from '../components/ui/textarea'
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { computed, watch, toRef } from 'vue'
import { CircleX, Trash2 } from 'lucide-vue-next'

const props = defineProps<{
  open: boolean
  room: any
}>()

const emit = defineEmits(['close'])

const form = useForm({
  location: props.room.location,
  description: props.room.description,
  length: props.room.length,
  width: props.room.width,
  height: props.room.height,
  price_per_day: props.room.price_per_day,
  price_per_month: props.room.price_per_month,
  imagesToDelete: [] as string[],
  newImages: [] as File[],  
})

function toggleImageDeletion(image: string) {
  const index = form.imagesToDelete.indexOf(image)
  if (index !== -1) {
    form.imagesToDelete.splice(index, 1)
  } else {
    form.imagesToDelete.push(image)
  }
}


function submit() {
  const data = new FormData()

  data.append('location', form.location)
  data.append('description', form.description)
  data.append('length', form.length.toString())
  data.append('width', form.width.toString())
  data.append('height', form.height.toString())
  data.append('price_per_day', form.price_per_day?.toString() || '')
  data.append('price_per_month', form.price_per_month?.toString() || '')

  form.imagesToDelete.forEach(img => data.append('imagesToDelete[]', img))
  form.newImages.forEach(file => data.append('newImages[]', file))

  router.post(`/rooms/${props.room.id}`, data, {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => emit('close'),
  })
}


</script>

<template>
  <Dialog :open="open" @update:open="emit('close')">
    <DialogContent class="overflow-y-auto max-h-[90vh]">
      <DialogHeader>
        <DialogTitle>Edit Room</DialogTitle>
        <DialogDescription>
          Update the details of your room listing.
        </DialogDescription>
      </DialogHeader>

      <div class="space-y-4 py-4">
        <div class="space-y-2">
          <Label for="location">Location</Label>
          <Input id="location" v-model="form.location" />
        </div>

        <div class="space-y-2">
          <Label for="description">Description</Label>
          <Textarea id="description" v-model="form.description" rows="3" />
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div class="space-y-2">
            <Label for="length">Length (m)</Label>
            <Input id="length" type="number" v-model="form.length" />
          </div>
          <div class="space-y-2">
            <Label for="width">Width (m)</Label>
            <Input id="width" type="number" v-model="form.width" />
          </div>
          <div class="space-y-2">
            <Label for="height">Height (m)</Label>
            <Input id="height" type="number" v-model="form.height" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <Label for="price_per_day">Price/day</Label>
            <Input id="price_per_day" type="number" v-model="form.price_per_day" />
          </div>
          <div class="space-y-2">
            <Label for="price_per_month">Price/month</Label>
            <Input id="price_per_month" type="number" v-model="form.price_per_month" />
          </div>
        </div>
      </div>

    <div class="space-y-2">
        <Label for="newImages">Add New Images (max 5)</Label>
        <Input
            id="newImages"
            type="file"
            accept="image/*"
            multiple
            @change="(e: Event) => {
            const files = Array.from((e.target as HTMLInputElement).files || [])
            form.newImages = files.slice(0, 5)
            }"
        />
    </div>

    <div class="flex flex-col md:flex-row gap-2 w-full">
        <div
            v-for="(img, index) in props.room.images"
            :key="index"
            class="relative group border rounded-md overflow-hidden"
        >
            <img :src="`/storage/${img}`" class="w-full aspect-video object-cover" />

            <button
            type="button"
            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 opacity-90 hover:opacity-100"
            @click="toggleImageDeletion(img)"
            >
            <CircleX />
            </button>

            <div
            v-if="form.imagesToDelete.includes(img)"
            class="absolute inset-0 bg-black/50 text-white flex items-center justify-center font-bold text-sm"
            >
            <button
            type="button"
            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 opacity-90 hover:opacity-100"
            @click="toggleImageDeletion(img)"
            >
            <CircleX />
            </button>
            Marked for deletion
            </div>
        </div>
    </div>

      <DialogFooter class="mt-4">
        <Button @click="submit" :disabled="form.processing">Save</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
