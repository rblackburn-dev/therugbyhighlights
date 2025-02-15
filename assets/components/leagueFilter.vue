<template>
  <select
      class="select-new"
      v-model="league"
      @change="onLeagueChange()"
  >
    <option value="" disabled selected>
      Filter by League
    </option>
    <option :value="league.league" v-for="(league) in leagues">
      {{league.league}}
    </option>
  </select>
</template>

<script>
export default {
  name: 'League Filter',
  data() {
    return {
      league: '',
    };
  },
  props: ['page', 'leagues'],
  methods: {
    onLeagueChange() {
      this.$emit('filter-league', this.league);
    }
  },
}
</script>