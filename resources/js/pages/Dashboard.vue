<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import DashTabs from '../components/DashTabs.vue'
import ChatBox from '../components/ChatBox.vue'
import UserRooms from '../components/UserRooms.vue'
import { ref } from 'vue'
import axios from 'axios'
import Card from '@/components/ui/card/Card.vue';
import CardTitle from '@/components/ui/card/CardHeader.vue';
import CardContent from '@/components/ui/card/CardContent.vue';

const props = defineProps<{
  ownerBookings: any[],
  renterBookings: any[],
  rooms: any[]
}>()


const openChatBookingId = ref<number | null>(null)

const chatData = ref<{
  messages: any[]
  conversationId: number
  currentUserId: number
} | null>(null)

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

function openChat(bookingId: number) {
  openChatBookingId.value = bookingId

  axios.get(`/bookings/${bookingId}/chat`)
    .then((response: { data: { messages: any[], conversationId: number, userId: number } }) => {
      const { messages, conversationId, userId } = response.data
      chatData.value = {
        messages,
        conversationId,
        currentUserId: userId,
      }
    })
    .catch((error: unknown) => {
      console.error('Failed to load chat data:', error)
    })
}


</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-8 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4">
                <div class="relative overflow-scroll rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <DashTabs :owner-bookings="ownerBookings" :renter-bookings="renterBookings" @open-chat="openChat" />
                </div>
            </div>
            <div class="flex flex-col lg:flex-row gap-4 relative">
                <Card class="w-full lg:w-2/3 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                  <CardTitle class="font-medium bg-muted">Your listings</CardTitle>
                  <CardContent>
                    <UserRooms :rooms="rooms" />
                  </CardContent>
                </Card>
                <div class="flex flex-row lg:flex-col gap-4 w-1/3">
                  <div class="h-1/2">Earnings</div>
                  <div class="h-1/2">Spendings</div>
                </div>
            </div>
        </div>
    </AppLayout>
    
    <ChatBox
        v-if="chatData"
        :messages="chatData.messages"
        :conversation-id="chatData.conversationId"
        :current-user-id="chatData.currentUserId"
        @close="chatData = null"
        @refresh="openChat(openChatBookingId!)"/>
</template>
