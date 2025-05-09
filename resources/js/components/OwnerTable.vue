<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import {
  Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow,
} from '../components/ui/table'
import {
  DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem
} from '../components/ui/dropdown-menu'
import Button from './ui/button/Button.vue'
import { Check, CircleX, Ellipsis, MessageSquareText, OctagonPause } from 'lucide-vue-next'
import { Badge } from '../components/ui/badge'
import { ref } from 'vue'

const props = defineProps<{
  bookings: any[]
}>()


function acceptBooking(id: number) {
  router.patch(`/bookings/${id}/confirm`)
}

function cancelBooking(id: number) {
  router.patch(`/bookings/${id}/cancel`)
}

function confirmOwnerDelivery(id: number) {
  router.patch(`/bookings/${id}/owner-delivered`)
}

function requestOwnerStop(id: number) {
  router.patch(`/bookings/${id}/owner-stop`)
}

function cancelOwnerStop(id: number) {
  router.patch(`/bookings/${id}/owner-stop-cancel`)
}

function confirmOwnerStop(id: number) {
  router.patch(`/bookings/${id}/owner-stop-confirm`)
}

</script>

<template>
  <Table>
    <TableCaption>Your room bookings</TableCaption>
    <TableHeader>
      <TableRow>
        <TableHead>Client</TableHead>
        <TableHead class="hidden md:block">Date</TableHead>
        <TableHead>Amount</TableHead>
        <TableHead>Status</TableHead>
        <TableHead>Actions</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableRow v-for="booking in bookings" :key="booking.id">
        <TableCell>{{ booking.renter?.name }}</TableCell>
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
              <Badge class="bg-black">Stopped</Badge>
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
                        <DropdownMenuItem v-if="booking.status === 'pending'" @click="acceptBooking(booking.id)">
                        <Check /> Confirm
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="booking.status === 'pending'" @click="cancelBooking(booking.id)">
                        <CircleX /> Cancel
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="booking.status === 'paid' && !booking.owner_confirmed_delivery" @click="confirmOwnerDelivery(booking.id)">
                        <Check /> Confirm Delivery
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="['paid', 'completed'].includes(booking.status)" @click="$emit('open-chat', booking.id)">
                        <MessageSquareText /> Chat
                        </DropdownMenuItem>
                        <template v-if="booking.status === 'completed'">
                        <DropdownMenuItem v-if="!booking.owner_requested_stop" @click="requestOwnerStop(booking.id)">
                            <OctagonPause /> Stop
                        </DropdownMenuItem>
                        <template v-else>
                            <DropdownMenuItem @click="cancelOwnerStop(booking.id)">
                            <CircleX /> Cancel Stop
                            </DropdownMenuItem>
                            <DropdownMenuItem @click="confirmOwnerStop(booking.id)">
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
