<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '../components/ui/table'
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '../components/ui/dropdown-menu'
import Button from '../components/ui/button/Button.vue'
import { Ellipsis } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  rooms: {
    id: number
    location: string
    price_per_day?: number
    price_per_month: number
    is_available: boolean
    is_deleted: boolean
  }[]
}>()

function makeUnavailable(id: number) {
  router.patch(`/rooms/${id}/unavailable`, {}, {
    preserveScroll: true
  })
}

function makeAvailable(id: number) {
  router.patch(`/rooms/${id}/available`, {}, {
    preserveScroll: true
  })
}

function editRoom(id: number) {
  console.log('Edit room', id)
}

function deleteRoom(id: number) {
  if (confirm('Are you sure you want to delete this room?')) {
    router.delete(`/rooms/${id}`, {
      preserveScroll: true,
    })
  }
}


</script>

<template>
  <Table>
    <TableCaption>Your Listings</TableCaption>
    <TableHeader>
      <TableRow>
        <TableHead>Location</TableHead>
        <TableHead>Price</TableHead>
        <TableHead>Status</TableHead>
        <TableHead class="text-right">Actions</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableRow
        v-for="room in rooms"
        :key="room.id"
        >
  <TableCell>{{ room.location }}</TableCell>
  <TableCell>
    {{ room.price_per_day ? `${room.price_per_day} €/day` : '' }}
    <br v-if="room.price_per_day && room.price_per_month" />
    {{ room.price_per_month }} €/month
  </TableCell>
  <TableCell>
  <span :class="room.is_available ? 'text-green-600' : 'text-red-600'">
    {{ room.is_available ? 'Available' : 'Unavailable' }}
  </span>
</TableCell>

  <TableCell class="text-right">
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button variant="outline" size="sm">⋯</Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent>
        <DropdownMenuItem @click="editRoom(room.id)">
          Edit
        </DropdownMenuItem>
        <DropdownMenuItem
          v-if="room.is_available"
          @click="makeUnavailable(room.id)"
        >
          Make Unavailable
        </DropdownMenuItem>
        <DropdownMenuItem
          v-else
          @click="makeAvailable(room.id)"
        >
          Make Available
        </DropdownMenuItem>
        <DropdownMenuItem class="text-red-600" @click="deleteRoom(room.id)">
          Delete
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  </TableCell>
</TableRow>

    </TableBody>
  </Table>
</template>
