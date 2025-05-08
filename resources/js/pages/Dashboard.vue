<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import DashTabs from '../components/DashTabs.vue'
import ChatBox from '../components/ChatBox.vue'
import UserRooms from '../components/UserRooms.vue'
import { ref, computed } from 'vue'
import axios from 'axios'
import Card from '@/components/ui/card/Card.vue';
import CardTitle from '@/components/ui/card/CardHeader.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import TotalEarnings from '@/components/TotalEarnings.vue';
import TotalSpendings from '@/components/TotalSpendings.vue';
import EditRoom from '@/components/EditRoom.vue'
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';

const props = defineProps<{
  ownerBookings: any[],
  renterBookings: any[],
  totalEarnings: number,
  totalSpendings: number,
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

const selectedRoom = ref(null)
const isEditDialogOpen = ref(false)

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

function openEditRoom(room: any) {
  selectedRoom.value = room
  isEditDialogOpen.value = true
}

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-10 p-4">
          <DashTabs :owner-bookings="ownerBookings" :renter-bookings="renterBookings" @open-chat="openChat" />
            <div class="flex flex-col-reverse lg:flex-row gap-10 lg:gap-6">
                <Card class="w-full lg:w-2/3 dark:border-sidebar-border border dark:bg-gray-900 shadow-md">
                  <CardTitle class="font-medium bg-muted rounded-t-xl">Your listings</CardTitle>
                  <CardContent>
                    <UserRooms :rooms="rooms" @edit-room="openEditRoom"/>
                  </CardContent>
                </Card>
                <div class="flex lg:w-1/3 flex-row lg:flex-col gap-4 h-full">
                    <div class="flex flex-1">
                      <TotalEarnings :total="totalEarnings" class="w-full" />
                    </div>
                    <div class="flex flex-1">
                      <TotalSpendings :total="props.totalSpendings" class="w-full" />
                    </div>
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

    <EditRoom
        v-if="selectedRoom"
        :open="isEditDialogOpen"
        :room="selectedRoom"
        @close="isEditDialogOpen = false"
      />
</template>
