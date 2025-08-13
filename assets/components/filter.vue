<template>
  <div class="filter-container">
    <div class="filter-wrapper">
      <div class="filter">
        <multiselect
            v-if="currentFilter === 'team'"
            v-model="selectedTeams"
            :options="teams"
            :multiple="true"
            :searchable="true"
            placeholder="Filter by Team"
            @update:modelValue="onTeamChange"
        >
        </multiselect>
        <multiselect
            v-if="currentFilter === 'league'"
            v-model="selectedLeagues"
            :options="leagues"
            :multiple="true"
            :searchable="true"
            placeholder="Filter by League"
            @update:modelValue="onLeagueChange"
        >
        </multiselect>
      </div>
      <div class="button-container">
        <button v-if="page === 'Archive'" class="icon-button" title="Change Season" @click="showModal = true">
          <i class='bx bxs-calendar'></i>
        </button>
        <div v-if="showModal" class="modal">
          <div class="modal-content">
            <button class="close-button" @click="showModal = false">×</button>
            <h3>Select Season</h3>
            <select
                v-model="selectedSeason"
                class="select-new"
                @change="onSeasonChange()"
            >
              <option :value="'2023-2024'">
                2023-2024
              </option>
              <option :value="'2024-2025'">
                2024-2025
              </option>
              <option :value="'2025-2026'">
                2025-2026
              </option>
            </select>
          </div>
        </div>
        <button
            class="icon-button"
            @click="toggleFilter"
            :title="(currentFilter === 'league' ? 'Team' : 'League') + ' Filter'"
        >
          <i v-if="currentFilter === 'team'" class="bx bxs-trophy"></i>
          <i v-else class="bx bxs-ball"></i>
        </button>
        <button class="icon-button" @click="resetFilter" title="Reset Filters">
          <i class='bx bx-refresh'></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';

export default {
  name: 'Filter',
  components: {
    Multiselect,
  },
  data() {
    return {
      currentFilter: 'team',
      selectedTeams: [],
      selectedLeagues: [],
      selectedSeason: '2025-2026',
      showModal: false
    };
  },
  props: ['teams', 'leagues', 'page', 'season'],
  methods: {
    onTeamChange() {
      if(this.page === 'Fixtures') {
        this.$emit('get-fixtures', this.selectedTeams, this.currentFilter);
      } else {
        this.$emit('get-fixtures', this.selectedSeason, this.selectedTeams, this.currentFilter);
      }
    },
    onLeagueChange() {
      if(this.page === 'Fixtures') {
        this.$emit('get-fixtures', this.selectedLeagues, this.currentFilter);
      } else {
        this.$emit('get-fixtures', this.selectedSeason, this.selectedLeagues, this.currentFilter);
      }
    },
    toggleFilter() {
      this.currentFilter = this.currentFilter === 'league' ? 'team' : 'league';
      this.$emit('toggle-filter', this.currentFilter);
    },
    resetFilter() {
      this.selectedTeams = [];
      this.selectedLeagues = [];
      this.$emit('get-fixtures');
    },
    onSeasonChange() {
      this.showModal = false;
      this.selectedTeams = [];
      this.selectedLeagues = [];
      this.$emit('get-fixtures', this.selectedSeason);
    }
  },
}
</script>