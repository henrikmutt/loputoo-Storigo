<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import {
  Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow
} from '../components/ui/table'
import {
  DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem
} from '../components/ui/dropdown-menu'
import Button from './ui/button/Button.vue'
import { Check, CircleX, Ellipsis, MessageSquareText, OctagonPause, ShoppingCart } from 'lucide-vue-next'
import { Badge } from '../components/ui/badge'
import { Link } from '@inertiajs/vue3'

const props = defineProps<{
  bookings: any[]
}>()

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
  router.patch(`/bookings/${id}/renter-stop`)
}

function cancelRenterStop(id: number) {
  router.patch(`/bookings/${id}/renter-stop-cancel`)
}

function confirmRenterStop(id: number) {
  router.patch(`/bookings/${id}/renter-stop-confirm`)
}
</script>

<template>
  <Table>
    <TableCaption>Your booking requests</TableCaption>
    <TableHeader>
      <TableRow>
        <TableHead>Room</TableHead>
        <TableHead class="hidden md:block">Date</TableHead>
        <TableHead>Amount</TableHead>
        <TableHead>Status</TableHead>
        <TableHead>Actions</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableRow v-for="booking in bookings" :key="booking.id">
        <TableCell>
          <Link :href="route('rooms.show', booking.room.id)" :data="{ hideRentButton: true }" class="underline">
            {{ booking.room?.location }}
          </Link>
        </TableCell>
        <TableCell class="hidden md:block">{{ new Date(booking.created_at).toLocaleDateString() }}</TableCell>
        <TableCell>{{ booking.total_price }} €</TableCell>

        <TableCell>
          <template v-if="booking.status === 'confirmed'">
            <Badge class="bg-yellow-400 text-black">Waiting for payment</Badge>
          </template>

          <template v-else-if="booking.status === 'paid' && (!booking.owner_confirmed_delivery || !booking.renter_confirmed_delivery)">
            <Badge class="bg-yellow-400 text-black">Waiting for actions</Badge>
          </template>

          <template v-else-if="booking.status === 'completed' && (booking.owner_requested_stop || booking.renter_requested_stop)">
            <Badge class="bg-yellow-400 text-black">Waiting for actions</Badge>
          </template>

          <template v-else-if="booking.status === 'completed' && booking.owner_confirmed_delivery && booking.renter_confirmed_delivery">
            <Badge class="bg-green-600 text-white">Completed</Badge>
          </template>

          <template v-else-if="booking.status === 'cancelled'">
            <Badge variant="destructive">Cancelled</Badge>
          </template>

          <template v-else-if="booking.status === 'stopped'">
            <Badge>Stopped</Badge>
          </template>

          <template v-else>
            <Badge variant="secondary">{{ booking.status }}</Badge>
          </template>
        </TableCell>

        <TableCell>
          <div v-if="!['cancelled', 'stopped'].includes(booking.status)">
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="outline" size="sm"><Ellipsis /></Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent>
                <DropdownMenuItem v-if="booking.status === 'confirmed'" @click="goToPayment(booking.id)">
                  <ShoppingCart /> Pay
                </DropdownMenuItem>

                <DropdownMenuItem v-if="['pending', 'confirmed'].includes(booking.status)" @click="cancelBooking(booking.id)">
                  <CircleX /> Cancel
                </DropdownMenuItem>

                <DropdownMenuItem v-if="booking.status === 'paid' && !booking.renter_confirmed_delivery" @click="confirmDelivery(booking.id)">
                  <Check /> Confirm Delivery
                </DropdownMenuItem>

                <DropdownMenuItem v-if="['paid', 'completed'].includes(booking.status)" @click="$emit('open-chat', booking.id)">
                  <MessageSquareText /> Chat
                </DropdownMenuItem>

                <template v-if="booking.status === 'completed'">
                  <DropdownMenuItem v-if="!booking.renter_requested_stop" @click="requestRenterStop(booking.id)">
                    <OctagonPause /> Stop
                  </DropdownMenuItem>
                  <template v-else>
                    <DropdownMenuItem @click="cancelRenterStop(booking.id)">
                      <CircleX /> Cancel Stop
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="confirmRenterStop(booking.id)">
                      <Check /> Confirm Stop
                    </DropdownMenuItem>
                  </template>
                </template>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>
</template>
