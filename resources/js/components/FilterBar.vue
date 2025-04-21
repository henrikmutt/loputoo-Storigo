<script setup>
import {ToggleGroup,ToggleGroupItem,} from '@/components/ui/toggle-group'

const emits = defineEmits(['filter']);

const sizes = [
  { label: 'Bike', value: 'bike' },
  { label: 'Car', value: 'car' },
  { label: 'Boat', value: 'boat' },
  { label: 'Boxes', value: 'boxes' },
];

function onSelect(value) {
  emits('filter', value);
}
</script>

<template>
  <ToggleGroup type="single" @update:modelValue="onSelect" class="flex gap-8 py-4">
    <ToggleGroupItem
      v-for="option in sizes"
      :key="option.value"
      :value="option.value"
      variant="outline"
    >
      {{ option.label }}
    </ToggleGroupItem>
  </ToggleGroup>
</template>
