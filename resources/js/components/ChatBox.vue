<script setup lang="ts">
import { ref, watch, nextTick, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { X } from 'lucide-vue-next'
import axios from 'axios'

const props = defineProps<{
  messages: any[],
  conversationId: number,
  currentUserId: number
}>()

const emit = defineEmits(['refresh', 'close'])

const form = useForm({
  message: ''
})

const scrollContainer = ref<HTMLElement | null>(null)

function sendMessage() {
  if (!form.message.trim()) return

  axios.post(`/chat/${props.conversationId}/message`, {
    message: form.message
  }).then(() => {
    form.reset()
    emit('refresh')
  })
}

watch(
  () => props.messages.length,
  async () => {
    await nextTick()
    if (scrollContainer.value) {
      scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight
    }
  }
)

const chatPartnerName = computed(() => {
  const otherUser = props.messages.find(m => m.sender_id !== props.currentUserId)
  return otherUser?.sender?.name || 'partner'
})

</script>


<template>
  <div class="fixed bottom-0 w-full lg:right-8 lg:w-80 z-50">
    <Card class="shadow-2xl border border-black">
      <CardHeader class="bg-gray-100 justify-between rounded-t-md px-4 py-2">
        Chat with {{ chatPartnerName }}
        <button @click="$emit('close')"><X /></button>
      </CardHeader>
      <CardContent ref="scrollContainer" class="max-h-64 overflow-y-auto space-y-2 p-4 bg-white border-t">

        <div
          v-for="(msg, index) in props.messages"
          :key="index"
          :class="msg.sender_id === currentUserId ? 'text-right' : 'text-left'"
        >
          <div class="inline-block px-3 py-2 rounded-lg text-sm"
            :class="msg.sender_id === currentUserId ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'">
            {{ msg.message }}
          </div>
        </div>
      </CardContent>
      <div class="flex border-t bg-white p-2 gap-2">
        <Input
          v-model="form.message"
          placeholder="Type a message..."
          class="flex-1"
          @keyup.enter="sendMessage"
        />
        <Button @click="sendMessage" size="sm">Send</Button>
      </div>
    </Card>
  </div>
</template>
