<template>
  <select
      class="select-new"
      v-model="season"
      @change="onSeasonChange()"
  >
    <option value="" disabled selected>
      Filter by Season
    </option>
    <option :value="'2023-2024'">
      2023-2024
    </option>
    <option :value="'2024-2025'">
      2024-2025
    </option>
  </select>
</template>

<script>
export default {
  name: 'Season Filter',
  data() {
    return {
      season: '2024-2025'
    };
  },
  props: ['page'],
  methods: {
    onSeasonChange() {
      this.$emit('filter-season', this.season);
    }
  },
}
</script>