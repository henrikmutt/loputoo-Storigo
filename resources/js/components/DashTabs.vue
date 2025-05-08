<script setup lang="ts">
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../components/ui/tabs'
import OwnerTable from '../components/OwnerTable.vue'
import RenterTable from '../components/RenterTable.vue'
import { computed } from 'vue';
import { BellRing } from 'lucide-vue-next';
import TooltipProvider from './ui/tooltip/TooltipProvider.vue';
import Tooltip from './ui/tooltip/Tooltip.vue';
import TooltipTrigger from './ui/tooltip/TooltipTrigger.vue';
import TooltipContent from './ui/tooltip/TooltipContent.vue';

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
  <!-- MOBILE TABLE -->
  <div class="md:hidden relative overflow-scroll rounded-xl border dark:bg-gray-900 shadow-md" orientation="vertical">
    <Tabs default-value="owner" class="w-full">
      <TabsList class="grid w-full grid-cols-1">
        <TooltipProvider>
            <Tooltip>
              <TooltipTrigger class="w-full">
                <TabsTrigger value="owner" class="flex justify-center w-full relative">
                  <div class="flex gap-4">
                    Your room bookings
                    <div
                      v-if="hasWaitingActions.ownerHasActions"
                    ><BellRing class="text-red-500"/></div>
                  </div>
                </TabsTrigger>
              </TooltipTrigger>
              <TooltipContent>
                <p>This table shows all bookings made by clients for your listed storage spaces.</p>
              </TooltipContent>
            </Tooltip>
          </TooltipProvider>

        <TooltipProvider>
            <Tooltip>
              <TooltipTrigger class="w-full">
                <TabsTrigger value="renter" class="flex justify-center w-full relative">
                  <div class="flex gap-4">
                    Your booking requests
                    <div
                      v-if="hasWaitingActions.renterHasActions"
                      ><BellRing class="text-red-500"/>
                    </div>
                  </div>
                </TabsTrigger>
              </TooltipTrigger>
              <TooltipContent>
                <p>This table displays your booking requests for storage spaces offered by others.</p>
              </TooltipContent>
            </Tooltip>
          </TooltipProvider>
      </TabsList>
      <TabsContent value="owner">
        <OwnerTable :bookings="ownerBookings" @open-chat="emit('open-chat', $event)" />
      </TabsContent>
      <TabsContent value="renter">
        <RenterTable :bookings="renterBookings" @open-chat="emit('open-chat', $event)" />
      </TabsContent>
    </Tabs>
  </div>

  <!-- DESKTOP TABLE -->
  <div class="hidden md:block relative overflow-scroll rounded-xl border dark:bg-gray-900 shadow-md">
    <Tabs default-value="owner" class="w-full">
      <TabsList class="w-full flex justify-center">
        <TooltipProvider>
            <Tooltip>
              <TooltipTrigger class="w-full">
                <TabsTrigger value="owner" class="flex justify-center w-full relative">
                  <div class="flex gap-4">
                    Your room bookings
                    <div
                      v-if="hasWaitingActions.ownerHasActions"
                    ><BellRing class="text-red-500"/></div>
                  </div>
                </TabsTrigger>
              </TooltipTrigger>
              <TooltipContent>
                <p>This table shows all bookings made by clients for your listed storage spaces.</p>
              </TooltipContent>
            </Tooltip>
          </TooltipProvider>

        <TooltipProvider>
            <Tooltip>
              <TooltipTrigger class="w-full">
                <TabsTrigger value="renter" class="flex justify-center w-full relative">
                  <div class="flex gap-4">
                    Your booking requests
                    <div
                      v-if="hasWaitingActions.renterHasActions"
                      ><BellRing class="text-red-500"/>
                    </div>
                  </div>
                </TabsTrigger>
              </TooltipTrigger>
              <TooltipContent>
                <p>This table displays your booking requests for storage spaces offered by others.</p>
              </TooltipContent>
            </Tooltip>
          </TooltipProvider>
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