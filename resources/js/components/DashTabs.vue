<script setup lang="ts">
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../components/ui/tabs'
import OwnerTable from '../components/OwnerTable.vue'
import RenterTable from '../components/RenterTable.vue'
import { computed } from 'vue';
import { BellRing } from 'lucide-vue-next';

const props = defineProps<{
  ownerBookings: any[],
  renterBookings: any[]
}>()

const emit = defineEmits(['open-chat'])

const hasWaitingActions = computed(() => {
  const owner = props.ownerBookings.some(booking =>
    booking.status === 'pending' ||
    booking.status === 'confirmed' ||
    (booking.status === 'paid' && !booking.owner_confirmed_delivery) || 
    (booking.status === 'completed' && booking.owner_requested_stop) ||
    (booking.status === 'completed' && booking.renter_requested_stop)
  )

  const renter = props.renterBookings.some(booking =>
    booking.status === 'pending' ||
    booking.status === 'confirmed' ||
    (booking.status === 'paid' && !booking.renter_confirmed_delivery) ||
    (booking.status === 'completed' && booking.renter_requested_stop) ||
    (booking.status === 'completed' && booking.owner_requested_stop)
  )

  return {
    ownerHasActions: owner,
    renterHasActions: renter,
  }
})

</script>

<template>
  <div class="relative overflow-scroll rounded-xl border dark:bg-gray-900 shadow-md">
    <Tabs default-value="owner" class="w-full">
      <TabsList class="w-full">
        <TabsTrigger value="owner" class="flex justify-center w-full relative">
          <div class="flex gap-4">
            Your room bookings
            <div
              v-if="hasWaitingActions.ownerHasActions"
            ><BellRing class="text-red-500"/></div>
          </div>
        </TabsTrigger>

        <TabsTrigger value="renter" class="flex justify-center w-full relative">
          <div class="flex gap-4">
            Your booking requests
            <div
              v-if="hasWaitingActions.renterHasActions"
              ><BellRing class="text-red-500"/>
            </div>
          </div>
        </TabsTrigger>
      </TabsList>
      <TabsContent value="owner">
        <OwnerTable :bookings="ownerBookings" @open-chat="emit('open-chat', $event)" />
      </TabsContent>
      <TabsContent value="renter">
        <RenterTable :bookings="renterBookings" @open-chat="emit('open-chat', $event)" />
      </TabsContent>
    </Tabs>
  </div>
</template>