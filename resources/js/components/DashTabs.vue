<script setup lang="ts">
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../components/ui/tabs'
import OwnerTable from '../components/OwnerTable.vue'
import RenterTable from '../components/RenterTable.vue'

const props = defineProps<{
  ownerBookings: any[],
  renterBookings: any[]
}>()

const emit = defineEmits(['open-chat'])
</script>

<template>
  <Tabs default-value="owner" class="w-full">
    <TabsList class="w-full">
      <TabsTrigger value="owner" class="flex justify-center w-full">
        Your room bookings
      </TabsTrigger>
      <TabsTrigger value="renter" class="flex justify-center w-full">
        Your booking requests
      </TabsTrigger>
    </TabsList>
    <TabsContent value="owner">
      <OwnerTable :bookings="ownerBookings" @open-chat="emit('open-chat', $event)" />
    </TabsContent>
    <TabsContent value="renter">
      <RenterTable :bookings="renterBookings" @open-chat="emit('open-chat', $event)" />
    </TabsContent>
  </Tabs>
</template>