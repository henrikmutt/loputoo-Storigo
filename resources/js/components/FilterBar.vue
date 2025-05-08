<script setup>
import {ToggleGroup,ToggleGroupItem,} from '@/components/ui/toggle-group'
import { Armchair, Bike, Car, Sailboat } from 'lucide-vue-next';

const emits = defineEmits(['filter']);

const sizes = [
  { label: 'Bike', value: 'bike', icon: Bike },
  { label: 'Car', value: 'car', icon: Car },
  { label: 'Boat', value: 'boat', icon: Sailboat },
  { label: 'Furniture', value: 'boxes', icon: Armchair },
];

function onSelect(value) {
  emits('filter', value);
}
</script>

<template>
  
  <ToggleGroup type="single" @update:modelValue="onSelect" class="flex gap-2 md:gap-8 py-4">
    <ToggleGroupItem
      v-for="option in sizes"
      :key="option.value"
      :value="option.value"
      variant="outline"
      class="flex items-center gap-2"
    >
      <component :is="option.icon" class="w-6 h-6" />
      {{ option.label }}
    </ToggleGroupItem>
  </ToggleGroup>
</template>
