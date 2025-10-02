<template>
  <div class="max-w-5xl mx-auto p-6">
    
<header class="mb-6 flex items-center gap-4">
  <h1 class="text-2xl font-bold text-gray-900">📝 Notes</h1>
  <button class="btn btn-new" @click="openCreate">Create Note</button>
</header>


    <div v-if="toast" class="toast">{{ toast }}</div>

    <section class="grid md:grid-cols-2 gap-4">
      <article v-for="n in notes" :key="n.id" class="card">
        <h2 class="text-lg font-semibold">{{ n.title }}</h2>
        <p class="text-gray-700 mt-2 line-clamp-3">{{ n.content }}</p>
        <p class="text-xs text-gray-500 mt-2">Created: {{ new Date(n.created_at).toLocaleString() }}</p>

<div class="mt-4 flex gap-2">
  <button class="btn btn-view" @click="view(n.id)">View</button>
  <button class="btn btn-edit" @click="edit(n)">Edit</button>
  <button class="btn btn-delete" @click="remove(n.id)">Delete</button>
</div>

      </article>
    </section>

    <!-- View Modal -->
    <div v-if="viewing" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
      <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-xl">
        <h3 class="text-xl font-bold">{{ current?.title }}</h3>
        <p class="mt-3 whitespace-pre-wrap">{{ current?.content }}</p>
        <p class="text-xs text-gray-500 mt-3">Created: {{ current ? new Date(current.created_at).toLocaleString() : '' }}</p>

        <div class="mt-6 flex justify-end gap-2">
          <button class="btn-secondary" @click="closeView">Close</button>
        </div>
      </div>
    </div>

    <!-- Create/Update Modal -->
    <div v-if="editing" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
      <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-xl">
        <h3 class="text-xl font-bold">{{ editMode === 'create' ? 'Create Note' : 'Update Note' }}</h3>
        <form class="mt-4" @submit.prevent="submit">
          <label class="label">Title</label>
          <input class="input mt-1" v-model="form.title" required maxlength="255" />

          <label class="label mt-4">Content</label>
          <textarea class="input mt-1 min-h-[160px]" v-model="form.content" required></textarea>

          <p v-if="errors" class="error mt-2">{{ errors }}</p>

          <div class="mt-6 flex justify-end gap-2">
            <button class="btn-secondary" type="button" @click="closeEdit">Cancel</button>
            <button class="btn" type="submit">{{ editMode === 'create' ? 'Create' : 'Save' }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'

const API = import.meta.env.VITE_API_BASE || 'http://localhost:8000/api'

const notes = ref([])
const toast = ref('')
const viewing = ref(false)
const editing = ref(false)
const current = ref(null)
const form = ref({ title: '', content: '' })
const errors = ref('')
const editMode = ref('create') // 'create' | 'update'

function showToast(msg) {
  toast.value = msg
  setTimeout(() => (toast.value = ''), 3000)
}

async function load() {
  try {
    const { data } = await axios.get(`${API}/notes`)
    notes.value = data
  } catch (e) {
    showToast('Failed to load notes')
  }
}

async function view(id) {
  try {
    const { data } = await axios.get(`${API}/notes/${id}`)
    current.value = data
    viewing.value = true
  } catch (e) {
    showToast('Failed to fetch note')
  }
}
function closeView() { viewing.value = false; current.value = null }

function openCreate() {
  form.value = { title: '', content: '' }
  editMode.value = 'create'
  editing.value = true
}
function edit(n) {
  form.value = { id: n.id, title: n.title, content: n.content }
  editMode.value = 'update'
  editing.value = true
}
function closeEdit() { editing.value = false; errors.value = '' }

async function submit() {
  try {
    if (editMode.value === 'create') {
      const { data } = await axios.post(`${API}/notes`, { ...form.value })
      notes.value.unshift(data)
      showToast('Note created')
    } else {
      const { data } = await axios.put(`${API}/notes/${form.value.id}`, { title: form.value.title, content: form.value.content })
      const idx = notes.value.findIndex(n => n.id === form.value.id)
      if (idx !== -1) notes.value[idx] = data
      showToast('Note updated')
    }
    closeEdit()
  } catch (e) {
    errors.value = e.response?.data?.message || 'Validation error'
  }
}

async function remove(id) {
  if (!confirm('Delete this note?')) return
  try {
    await axios.delete(`${API}/notes/${id}`)
    notes.value = notes.value.filter(n => n.id !== id)
    showToast('Note deleted')
  } catch (e) {
    showToast('Failed to delete note')
  }
}

onMounted(load)
</script>
