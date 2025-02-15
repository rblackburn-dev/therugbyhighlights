<template>
  <select
      class="select-new"
      v-model="team"
      @change="onTeamChange()"
  >
    <option value="" disabled selected>
      Filter by Team
    </option>
    <option :value="team" v-for="(team) in teams">
      {{team}}
    </option>
  </select>
</template>

<script>
export default {
  name: 'Team Filter',
  data() {
    return {
      team: '',
    };
  },
  props: ['page', 'teams'],
  methods: {
    onTeamChange() {
      this.$emit('filter-team', this.team);
    }
  },
}
</script>