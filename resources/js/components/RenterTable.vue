<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '../components/ui/table'
import Button from './ui/button/Button.vue'
import { MessageSquareText } from 'lucide-vue-next'
import { ref } from 'vue'

const props = defineProps<{
  bookings: any[]
}>()

const openChatBookingId = ref<number | null>(null)

function cancelBooking(id: number) {
  router.patch(`/bookings/${id}/cancel`)
}

function goToPayment(id: number) {
  window.location.href = `/bookings/${id}/checkout`
}

function confirmDelivery(id: number) {
  router.patch(`/bookings/${id}/renter-delivered`)
}

function requestRenterStop(id: number) {
  router.patch(`/bookings/${id}/renter-stop`);
}

function cancelRenterStop(id: number) {
  router.patch(`/bookings/${id}/renter-stop-cancel`);
}

function confirmRenterStop(id: number) {
  router.patch(`/bookings/${id}/renter-stop-confirm`);
}

function openChat(bookingId: number) {
  openChatBookingId.value = bookingId
}

</script>

<template>
  <Table>
    <TableCaption>Your booking requests</TableCaption>
    <TableHeader>
      <TableRow>
        <TableHead class="w-1/3 md:w-1/4">Room</TableHead>
        <TableHead class="hidden md:block md:w-1/4">Date</TableHead>
        <TableHead class="w-1/3 md:w-1/4">Amount</TableHead>
        <TableHead class="w-1/3 md:w-1/4">Status</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableRow v-for="booking in bookings" :key="booking.id">
        <TableCell>{{ booking.room?.location }}</TableCell>
        <TableCell>{{ new Date(booking.created_at).toLocaleDateString() }}</TableCell>
        <TableCell>{{ booking.total_price }} €</TableCell>
        <TableCell class="flex flex-col gap-2">
          <span class="capitalize">
            <template v-if="booking.status === 'confirmed'">
              Waiting for payment
            </template>
            <template v-else-if="booking.status === 'paid' && (!booking.owner_confirmed_delivery || !booking.renter_confirmed_delivery)">
              Paid - Confirm after delivery
            </template>
            <template v-else-if="booking.status === 'paid' && booking.owner_confirmed_delivery && booking.renter_confirmed_delivery">
              Completed
            </template>
            <template v-else>
              {{ booking.status }}
            </template>
          </span>

          <div class="flex gap-2">
            <Button v-if="booking.status === 'confirmed'" @click="goToPayment(booking.id)">
              Pay
            </Button>

            <Button v-if="booking.status === 'pending' || booking.status === 'confirmed'" variant="destructive" @click="cancelBooking(booking.id)">
              Cancel
            </Button>

            <Button v-if="booking.status === 'paid' && !booking.renter_confirmed_delivery" @click="confirmDelivery(booking.id)">
              Confirm Delivery
            </Button>

            <Button v-if="['paid', 'completed'].includes(booking.status)" variant="outline" @click="$emit('open-chat', booking.id)">
              <MessageSquareText class="w-5" />
            </Button>

            <div v-if="booking.status === 'completed'" class="flex gap-2">
              <Button v-if="!booking.renter_requested_stop" variant="secondary" @click="requestRenterStop(booking.id)">
                Stop
              </Button>
              <template v-else>
                <Button variant="destructive" @click="cancelRenterStop(booking.id)">
                  Cancel Stop
                </Button>
                <Button @click="confirmRenterStop(booking.id)">
                  Confirm Stop
                </Button>
              </template>
            </div>
          </div>
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
</template>
