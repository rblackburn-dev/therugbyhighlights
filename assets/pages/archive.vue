<template>
  <section>
    <filter-component
        @get-fixtures="getFixtures"
        @toggle-filter="toggleFilter"
        :teams="teams"
        :leagues="leagues"
        :page="page"
        :season="currentSeason"
    />

    <fixture-component
        :fixtures="fixtures"
        :page="page"
    />
  </section>
</template>

<script>
import FixtureComponent from '../components/fixture';
import FilterComponent from "../components/filter.vue";

export default {
  name: 'Archive',
  data() {
    return {
      fixtures: [],
      page: 'Archive',
      leagues: [],
      teams: [],
      currentSeason: '2025-2026',
      currentFilter: 'team',
    };
  },
  components: {
    FilterComponent,
    FixtureComponent,
  },
  methods: {
    getFixtures: function(season, filters, currentFilter) {
      this.toggleFilter(currentFilter);
      this.updateSeason(season)

      $.ajax({
        type: "GET",
        url: "/rugby/getArchiveFixtures/",
        data: {
          'currentSeason': this.currentSeason,
          'filters' : JSON.stringify(filters),
          'currentFilter': this.currentFilter
        },
        success: (data) => {
          this.fixtures = data;
        }
      })
    },
    getLeagues: function () {
      $.ajax({
        type: "GET",
        url: "/rugby/getLeagues",
        success: (data) => {
          this.leagues = data;
        }
      })
    },
    getTeams: function () {
      $.ajax({
        type: "GET",
        url: "/rugby/getTeams",
        success: (data) => {
          this.teams = data;
        }
      })
    },
    toggleFilter: function (currentFilter) {
      if (currentFilter !== undefined) {
        this.currentFilter = currentFilter;
      }
    },
    updateSeason: function (season) {
      if (season !== undefined) {
        this.currentSeason = season;
      }
    }
  },
  created() {
    this.getFixtures();
    this.getLeagues();
    this.getTeams();
  }
}
</script>