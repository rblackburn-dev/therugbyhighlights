<template>
  <div class="container">
    <div class="date-card">
      {{ date }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'Date',
  props: ['date']
}
</script>