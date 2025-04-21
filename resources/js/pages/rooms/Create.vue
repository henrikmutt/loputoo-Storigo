<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppHeader from '@/components/AppHeader.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Button } from '@/components/ui/button'

// Form setup
const form = useForm({
  location: '',
  description: '',
  length: '',
  width: '',
  height: '',
  size: 0,
  price_per_day: '',
  price_per_month: '',
  images: [] as File[],
})

// Volume calculation (m³)
const calculatedSize = computed(() => {
  const l = parseFloat(form.length)
  const w = parseFloat(form.width)
  const h = parseFloat(form.height)
  return isNaN(l) || isNaN(w) || isNaN(h) ? 0 : +(l * w * h).toFixed(2)
})

// Watch for size updates and sync it to the form
watch(calculatedSize, (newVal) => {
  form.size = newVal
})
</script>

<template>
  <AppHeader />

  <div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6">Add a New Room</h1>

    <form @submit.prevent="form.post('/rooms')" class="space-y-6">
      <!-- Location -->
      <div class="space-y-2">
        <Label for="location">Location</Label>
        <Input id="location" v-model="form.location" placeholder="e.g. Tallinn, Tartu..." />
      </div>

      <!-- Description -->
      <div class="space-y-2">
        <Label for="description">Description</Label>
        <Textarea
          id="description"
          v-model="form.description"
          placeholder="Describe your space, access, rules, etc."
        />
      </div>

      <!-- Dimensions -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="space-y-2">
          <Label for="length">Length (m)</Label>
          <Input id="length" type="number" step="0.01" v-model="form.length" />
        </div>

        <div class="space-y-2">
          <Label for="width">Width (m)</Label>
          <Input id="width" type="number" step="0.01" v-model="form.width" />
        </div>

        <div class="space-y-2">
          <Label for="height">Height (m)</Label>
          <Input id="height" type="number" step="0.01" v-model="form.height" />
        </div>
      </div>

      <!-- Calculated size preview -->
      <p class="text-sm text-muted-foreground mt-2">
        Volume: {{ calculatedSize }} m³
      </p>

      <!-- Pricing -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-2">
          <Label for="price_per_day">Price per Day (€)</Label>
          <Input
            id="price_per_day"
            type="number"
            step="0.01"
            v-model="form.price_per_day"
            placeholder="e.g. 5.00"
          />
        </div>

        <div class="space-y-2">
          <Label for="price_per_month">Price per Month (Optional)</Label>
          <Input
            id="price_per_month"
            type="number"
            step="0.01"
            v-model="form.price_per_month"
            placeholder="e.g. 80.00"
          />
        </div>
      </div>

      <!-- Images -->
      <div class="space-y-2">
        <Label for="images">Upload Images (max 5)</Label>
        <Input
          id="images"
          type="file"
          accept="image/*"
          multiple
          @change="(e) => form.images = Array.from(e.target.files).slice(0, 5)"
        />
        <p class="text-sm text-muted-foreground">You can upload up to 5 images.</p>
      </div>

      <!-- Submit -->
      <div>
        <Button type="submit" :disabled="form.processing">
          Submit Room
        </Button>
      </div>
    </form>
  </div>
</template>
