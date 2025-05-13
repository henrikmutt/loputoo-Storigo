<script setup lang="ts">
import { ref } from 'vue'
import RoomCard from '../../js/components/RoomCard.vue'
import AppHeader from '@/components/AppHeader.vue'
import FilterBar from '@/components/FilterBar.vue'
import { Link } from '@inertiajs/vue3';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';

const props = defineProps<{ rooms: Array<any> }>();
const filteredRooms = ref([...props.rooms]);
type FilterType = 'bike' | 'car' | 'boat' | 'boxes'

function handleFilter(type: FilterType | null) {
  const filters = {
    bike: (r: any) => r.size <= 2,
    car: (r: any) => r.size > 2 && r.size <= 8,
    boat: (r: any) => r.size > 8 && r.size <= 20,
    boxes: (r: any) => r.size <= 10,
  }

  filteredRooms.value = type
    ? props.rooms.filter(filters[type])
    : [...props.rooms]
}
</script>

<template>
  <AppHeader />
  <div class="flex justify-center">
    <TooltipProvider>
    <Tooltip>
      <TooltipTrigger>
        <FilterBar @filter="handleFilter" />
      </TooltipTrigger>
      <TooltipContent>
        <p>What would you like to store?</p>
      </TooltipContent>
    </Tooltip>
  </TooltipProvider>
  </div>
  <div class="p-4 lg:p-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <Link v-for="room in filteredRooms" :key="room.id" :href="`/rooms/${room.id}`" class="block">
      <RoomCard :room="room" />
    </Link>
    </div>
  </div>
</template>
