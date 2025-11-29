<template>
  <loading-component
      v-show="isLoading"
  />
  <div class="container">
    <div v-if="hasFixtures" class="fixture-card" v-for="fixture in fixtures" :key="fixture.id" :class="'fixture-' + fixture.id">
      <div v-if="fixture.highlights" @click="getHighlightsVideo(fixture.highlights, fixture.homeTeam, fixture.awayTeam, fixture.league)" class="clickable">
        <div class="league-name">
          <h2 class="league-text">{{ fixture.league }}</h2>
        </div>
        <div class="team-logos">
          <div class="team-logo">
            <img :src="'/assets/img/' + fixture.homeTeam + '.png'" :alt="fixture.homeTeam" :title="fixture.homeTeam">
          </div>
          <div class="team-logo">
            <img :src="'/assets/img/' + fixture.awayTeam + '.png'" :alt="fixture.awayTeam" :title="fixture.awayTeam">
          </div>
        </div>
      </div>
      <div v-else>
        <div class="league-name">
          <h2 class="league-text">{{ fixture.league }}</h2>
        </div>
        <div class="team-logos">
          <div class="team-logo">
            <img v-if="page === 'Fixtures' || page === 'Archive'" :src="'/assets/img/' + fixture.homeTeam + '.png'" :alt="fixture.homeTeam" :title="fixture.homeTeam">
            <img v-else :src="'/assets/img/' + fixture.homeTeam + '.png'" :alt="fixture.homeTeam" class="inactive-fixture" :title="fixture.homeTeam">
          </div>
          <div class="team-logo">
            <img v-if="page === 'Fixtures' || page === 'Archive'" :src="'/assets/img/' + fixture.awayTeam + '.png'" :alt="fixture.awayTeam" :title="fixture.awayTeam">
            <img v-else :src="'/assets/img/' + fixture.awayTeam + '.png'" :alt="fixture.awayTeam" class="inactive-fixture" :title="fixture.awayTeam">
          </div>
        </div>
      </div>
      <div v-if="page === 'Fixtures' || page === 'Archive'" class="kickoff-time">
        <p>{{ formatKickoffTime(fixture.kickOff.date) }}</p>
      </div>
    </div>
    <div v-else class="fixture-card-empty">
      <p>No Fixtures Available</p>
    </div>
  </div>
</template>

<script>
import LoadingComponent from '../components/loading';

export default {
  name: 'Fixture',
  props: ['fixtures', 'page'],
  components: {
    LoadingComponent
  },
  data() {
    return {
      isLoading: true
    };
  },
  computed: {
    hasFixtures() {
      return this.fixtures && this.fixtures.length > 0;
    }
  },
  methods: {
    getHighlightsVideo(highlights, homeTeam, awayTeam) {
      const header = document.querySelector("header");

      header.classList.toggle ("hide-header");

      if(highlights === 'NoVideo') {
        $.sweetModal({
          content: '<h1>Highlights unavailable, apologies for the inconvenience.</h1>',
        });
      } else if (highlights.includes("watch?v=")) {
        $.sweetModal({
          content: '<a href="' + highlights + '" target="_blank"><svg class="play-button" xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24">\n' +
              '    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>\n' +
              '    <path d="m9 17 8-5-8-5z"></path>\n' +
              '</svg></a>',
        });
      } else if (highlights.includes("?&start=")) {
        $.sweetModal({
          content: '<iframe width="660" height="415" src="'+highlights+'&autoplay=1" title="YouTube Video Player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="video-player"></iframe>',
        });
      } else {
        $.sweetModal({
          content: '<iframe width="660" height="415" src="'+highlights+'?&autoplay=1" title="YouTube Video Player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen class="video-player"></iframe>',
        });
      }

      const modalCloseButton = document.getElementsByClassName("sweet-modal-close-link");

      for (let i = 0; i < modalCloseButton.length; i++) {
        modalCloseButton[i].addEventListener("click", function() {
          closeModal(header);
        });
      }

      function closeModal(header) {
        header.classList.remove ("hide-header");
      }
    },
    formatKickoffTime(dateTimeString) {
      const options = { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' };
      const date = new Date(dateTimeString);
      return date.toLocaleString('en-US', options);
    }
  },
  mounted() {
    setTimeout(() => {
      this.isLoading = false;
    }, 1000);
  }
}
</script>