<template>
  <section>
    <!-- Today's Fixtures -->
    <date-component
        :date="dateToday"
    />
    <fixture-component
        :fixtures="todayFixtures"
    />
    <!-- End Today's Fixtures -->

    <!-- Yesterday's Fixtures -->
    <date-component
        :date="dateYesterday"
    />
    <fixture-component
        :fixtures="yesterdayFixtures"
    />
    <!-- End Yesterday's Fixtures -->

    <!-- Two Days Ago Fixtures -->
    <date-component
        :date="dateTwoDaysAgo"
    />
    <fixture-component
        :fixtures="twoDaysAgoFixtures"
    />
    <!-- End Two Days Ago Fixtures -->

    <!-- Three Days Ago Fixtures -->
    <date-component
        :date="dateThreeDaysAgo"
    />
    <fixture-component
        :fixtures="threeDaysAgoFixtures"
    />
    <!-- End Three Days Ago Fixtures -->

    <!-- Four Days Ago Fixtures -->
    <date-component
        :date="dateFourDaysAgo"
    />
    <fixture-component
        :fixtures="fourDaysAgoFixtures"
    />
    <!-- End Four Days Ago Fixtures -->
  </section>
</template>

<script>
import FixtureComponent from '../components/fixture';
import DateComponent from '../components/date';

export default {
  name: 'Rugby',
  data() {
    return {
      dateToday: '',
      dateYesterday: '',
      dateTwoDaysAgo: '',
      dateThreeDaysAgo: '',
      dateFourDaysAgo: '',
      fixtures: [],
    };
  },
  components: {
    FixtureComponent,
    DateComponent,
  },
  computed: {
    getFixturesForDaysAgo() {
      return (daysAgo) => {
        const date = new Date();
        date.setDate(date.getDate() - daysAgo);
        const localDate = this.formatLocalDate(date);

        return this.fixtures.filter(fixture => {
          const fixtureDate = new Date(fixture.kickOff.date);
          return this.formatLocalDate(fixtureDate) === localDate;
        });
      };
    },

    todayFixtures() {
      return this.getFixturesForDaysAgo(0);
    },
    yesterdayFixtures() {
      return this.getFixturesForDaysAgo(1);
    },
    twoDaysAgoFixtures() {
      return this.getFixturesForDaysAgo(2);
    },
    threeDaysAgoFixtures() {
      return this.getFixturesForDaysAgo(3);
    },
    fourDaysAgoFixtures() {
      return this.getFixturesForDaysAgo(4);
    },
  },
  methods: {
    formatLocalDate(date) {
      return new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
    },
    getDates() {
      const today = new Date();

      const days = [0, 1, 2, 3, 4];
      const dateKeys = ['dateToday', 'dateYesterday', 'dateTwoDaysAgo', 'dateThreeDaysAgo', 'dateFourDaysAgo'];

      days.forEach((daysAgo, index) => {
        const date = new Date(today);
        date.setDate(date.getDate() - daysAgo);
        this[dateKeys[index]] = this.formatLocalDate(date);
      });
    },
    getFixtures() {
      $.ajax({
        type: "GET",
        url: "/rugby/getRecentFixtures/",
        success: (data) => {
          this.fixtures = data;
        }
      });
    },
  },
  created() {
    this.getDates();
    this.getFixtures();
  }
};
</script>