<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '../components/ui/table';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from '../components/ui/dropdown-menu';
import Button from '../components/ui/button/Button.vue';
import { Badge } from '../components/ui/badge';
import { Check, Ellipsis, Pencil, Trash2, X } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
  AlertDialog,
  AlertDialogContent,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogCancel,
} from '../components/ui/alert-dialog';
import TooltipProvider from './ui/tooltip/TooltipProvider.vue';
import Tooltip from './ui/tooltip/Tooltip.vue';
import TooltipTrigger from './ui/tooltip/TooltipTrigger.vue';
import TabsTrigger from './ui/tabs/TabsTrigger.vue';
import TooltipContent from './ui/tooltip/TooltipContent.vue';

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

const emit = defineEmits(['edit-room'])
const roomToDelete = ref<number | null>(null)

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


function confirmDeleteRoom(id: number) {
  roomToDelete.value = id
}

function handleConfirmDelete() {
  console.log('Delete triggered')

  if (roomToDelete.value !== null) {
    router.patch(route('rooms.delete', roomToDelete.value), {}, {
      preserveScroll: true,
      onSuccess: () => {
        console.log('Successfully deleted')
        roomToDelete.value = null
      },
      onError: (errors) => {
        console.error('Failed to delete room:', errors)
      },
      onFinish: () => {
        console.log('Request finished')
      }
    })
  }
}


</script>

<template>
      <TooltipProvider>
    <Tooltip>
      <TooltipTrigger>

      
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
      <TableCell class="text-left">
        <Link :href="route('rooms.show', room.id)" :data="{ hideRentButton: true }" class="underline">
          {{ room.location }}
        </Link>
      </TableCell>
      <TableCell class="text-left">
        {{ room.price_per_day ? `${room.price_per_day} €/day` : '' }}
        <br v-if="room.price_per_day && room.price_per_month" />
        {{ room.price_per_month }} €/month
      </TableCell>
      <TableCell class="text-left">
      <Badge :class="room.is_available ? 'bg-green-600' : 'bg-red-600'">
        {{ room.is_available ? 'Available' : 'Unavailable' }}
      </Badge>
    </TableCell>
    
      <TableCell class="text-right">
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="outline" size="sm"><Ellipsis /></Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            <DropdownMenuItem @click="$emit('edit-room', room)">
              <Pencil /> Edit
            </DropdownMenuItem>
            <DropdownMenuItem
              v-if="room.is_available"
              @click="makeUnavailable(room.id)"
            >
              <X /> Unavailable
            </DropdownMenuItem>
            <DropdownMenuItem
              v-else
              @click="makeAvailable(room.id)"
            >
              <Check /> Available
            </DropdownMenuItem>
            <DropdownMenuItem class="text-red-600" @click="confirmDeleteRoom(room.id)">
              <Trash2 /> Delete
            </DropdownMenuItem>
    
          </DropdownMenuContent>
        </DropdownMenu>
      </TableCell>
    </TableRow>
    
        </TableBody>
      </Table>
      </TooltipTrigger>
      <TooltipContent>
        <p>These are all your storage room listings.</p>
      </TooltipContent>
    </Tooltip>
  </TooltipProvider>
  <AlertDialog :open="roomToDelete !== null" @update:open="(val: boolean) => { if (!val) roomToDelete = null }">
  <AlertDialogContent>
    <AlertDialogHeader>
      <AlertDialogTitle>Confirm Deletion</AlertDialogTitle>
      <AlertDialogDescription>
        {{ roomToDelete }}
        Are you sure you want to delete this room? This action cannot be undone.
      </AlertDialogDescription>
    </AlertDialogHeader>
    <AlertDialogFooter>
      <AlertDialogCancel @click="roomToDelete = null">Cancel</AlertDialogCancel>
      <Button
          as="button"
          type="button"
          @click.prevent="handleConfirmDelete"
        >
          Delete
        </Button>
    </AlertDialogFooter>
  </AlertDialogContent>
</AlertDialog>

</template>
