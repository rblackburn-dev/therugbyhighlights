<template>
  <section>
    <league-filter-component
        @filter-league="filterLeague"
        :leagues="leagues"
        :page="page"
    />

    <team-filter-component
        @filter-team="filterTeam"
        :teams="teams"
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
import LeagueFilterComponent from "../components/leagueFilter.vue";
import TeamFilterComponent from "../components/teamFilter.vue";

export default {
  name: 'Fixtures',
  data() {
    return {
      fixtures: [],
      page: 'Fixtures',
      leagues: [],
      teams: []
    };
  },
  components: {
    FixtureComponent,
    LeagueFilterComponent,
    TeamFilterComponent
  },
  methods: {
    getFixtures: function() {
      $.ajax({
        type: "GET",
        url: "/rugby/getFixtures/",
        success: (data) => {
          this.fixtures = data;
        }
      })
    },
    filterLeague(league) {
      $.ajax({
        type: "GET",
        url: "/rugby/getFixtures/" + league,
        success: (data) => {
          this.fixtures = data;
        }
      })
    },
    filterTeam(team) {
      $.ajax({
        type: "GET",
        url: "/rugby/getFixturesByTeam/" + team,
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
    }
  },
  created() {
    this.getFixtures();
    this.getLeagues();
    this.getTeams();
  }
}
</script>