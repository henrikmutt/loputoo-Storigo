<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow, } from '../components/ui/table'
import Button from './ui/button/Button.vue';
import { MessageSquareText } from 'lucide-vue-next'
import { ref } from 'vue'

const props = defineProps<{
  bookings: any[]
}>()

const openChatBookingId = ref<number | null>(null)

function acceptBooking(id: number) {
  router.patch(`/bookings/${id}/confirm`)
}

function cancelBooking(id: number) {
  router.patch(`/bookings/${id}/cancel`)
}

function confirmOwnerDelivery(id: number) {
  router.patch(`/bookings/${id}/owner-delivered`);
}

function requestOwnerStop(id: number) {
  router.patch(`/bookings/${id}/owner-stop`);
}

function cancelOwnerStop(id: number) {
  router.patch(`/bookings/${id}/owner-stop-cancel`);
}

function confirmOwnerStop(id: number) {
  router.patch(`/bookings/${id}/owner-stop-confirm`);
}

function openChat(bookingId: number) {
  openChatBookingId.value = bookingId
}

</script>

<template>

    <Table>
        <TableCaption>Your room bookings</TableCaption>
        <TableHeader>
            <TableRow>
                <TableHead class="w-1/3 md:w-1/4">Client</TableHead>
                <TableHead class="hidden md:block md:w-1/4">Date</TableHead>
                <TableHead class="w-1/3 md:w-1/4">Amount</TableHead>
                <TableHead class="w-1/3 md:w-1/4">Status</TableHead>
            </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="booking in bookings" :key="booking.id">
                    <TableCell>{{ booking.renter?.name }}</TableCell>
                    <TableCell class="hidden md:block">{{ new Date(booking.created_at).toLocaleDateString() }}</TableCell>
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
                            <Button v-if="booking.status === 'pending'" @click="acceptBooking(booking.id)">
                                Confirm
                            </Button>

                            <Button v-if="booking.status === 'pending'" variant="destructive" @click="cancelBooking(booking.id)">
                                Cancel
                            </Button>

                            <div v-if="booking.status === 'paid' && !booking.owner_confirmed_delivery">
                            <Button @click="confirmOwnerDelivery(booking.id)">
                                Confirm Delivery
                            </Button>
                        </div>

                        <div class="flex gap-2">
                            <Button v-if="['paid', 'completed'].includes(booking.status)" variant="outline" @click="$emit('open-chat', booking.id)">
                                <MessageSquareText class="w-10"/>
                            </Button>
                        </div>

                        <div v-if="booking.status === 'completed'" class="flex gap-2">
                            <template v-if="!booking.owner_requested_stop">
                                <Button variant="secondary" @click="requestOwnerStop(booking.id)">
                                Stop
                                </Button>
                            </template>
                            <template v-else>
                                <Button variant="destructive" @click="cancelOwnerStop(booking.id)">
                                Cancel Stop
                                </Button>
                                <Button @click="confirmOwnerStop(booking.id)">
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
        