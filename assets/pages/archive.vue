<template>
  <section>
    <season-filter-component
        @filter-season="filterSeason"
        :page="page"
    />

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
import SeasonFilterComponent from "../components/seasonFilter";
import LeagueFilterComponent from "../components/leagueFilter";
import TeamFilterComponent from '../components/teamFilter';

export default {
  name: 'Archive',
  data() {
    return {
      fixtures: [],
      page: 'Archive',
      leagues: [],
      teams: [],
      currentSeason: '2024-2025'
    };
  },
  components: {
    FixtureComponent,
    SeasonFilterComponent,
    LeagueFilterComponent,
    TeamFilterComponent
  },
  methods: {
    getFixtures: function() {
      $.ajax({
        type: "GET",
        url: "/rugby/getPastFixtures/" + this.currentSeason,
        success: (data) => {
          this.fixtures = data;
        }
      })
    },
    filterSeason(season) {
      this.currentSeason = season;
      this.getFixtures();
    },
    filterLeague(league) {
      $.ajax({
        type: "GET",
        url: "/rugby/getPastFixtures/" + this.currentSeason + "/" + league,
        success: (data) => {
          this.fixtures = data;
        }
      })
    },
    filterTeam(team) {
      $.ajax({
        type: "GET",
        url: "/rugby/getPastFixturesByTeam/" + this.currentSeason + "/" + team,
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