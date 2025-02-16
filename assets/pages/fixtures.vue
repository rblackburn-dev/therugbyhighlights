<template>
  <section>
    <filter-component
        @get-fixtures="getFixtures"
        @toggle-filter="toggleFilter"
        :teams="teams"
        :leagues="leagues"
        :page="page"
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
  name: 'Fixtures',
  data() {
    return {
      fixtures: [],
      page: 'Fixtures',
      leagues: [],
      teams: [],
      currentFilter: 'team',
    };
  },
  components: {
    FilterComponent,
    FixtureComponent
  },
  methods: {
    getFixtures: function(filters, currentFilter) {
      this.toggleFilter(currentFilter);
      $.ajax({
        type: "GET",
        url: "/rugby/getFixtures/",
        data: {
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
    }
  },
  created() {
    this.getFixtures();
    this.getTeams();
    this.getLeagues();
  }
}
</script>