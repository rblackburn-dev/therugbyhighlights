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
      index: 0
    };
  },
  components: {
    FixtureComponent,
    DateComponent
  },
  computed: {
    todayFixtures() {
      const today = new Date();

      const timezoneOffset = today.getTimezoneOffset() * 60000;
      const localDate = new Date(today - timezoneOffset).toISOString().slice(0, 10);

      return this.fixtures.filter(fixture => {
        const fixtureDate = new Date(fixture.kickOff.date);
        const fixtureOffset = fixtureDate.getTimezoneOffset() * 60000;
        const fixtureLocalDate = new Date(fixtureDate - fixtureOffset).toISOString().slice(0, 10);

        return fixtureLocalDate === localDate;
      });
    },
    yesterdayFixtures() {
      const yesterday = new Date();
      yesterday.setDate(yesterday.getDate() - 1);
      const yesterdayDate = yesterday.toISOString().slice(0, 10);
      return this.fixtures.filter(fixture => fixture.kickOff.date.slice(0, 10) === yesterdayDate);
    },
    twoDaysAgoFixtures() {
      const twoDaysAgo = new Date();
      twoDaysAgo.setDate(twoDaysAgo.getDate() - 2);
      const twoDaysAgoDate = twoDaysAgo.toISOString().slice(0, 10);
      return this.fixtures.filter(fixture => fixture.kickOff.date.slice(0, 10) === twoDaysAgoDate);
    },
    threeDaysAgoFixtures() {
      const threeDaysAgo = new Date();
      threeDaysAgo.setDate(threeDaysAgo.getDate() - 3);
      const threeDaysAgoDate = threeDaysAgo.toISOString().slice(0, 10);
      return this.fixtures.filter(fixture => fixture.kickOff.date.slice(0, 10) === threeDaysAgoDate);
    },
    fourDaysAgoFixtures() {
      const fourDaysAgo = new Date();
      fourDaysAgo.setDate(fourDaysAgo.getDate() - 4);
      const fourDaysAgoDate = fourDaysAgo.toISOString().slice(0, 10);
      return this.fixtures.filter(fixture => fixture.kickOff.date.slice(0, 10) === fourDaysAgoDate);
    }
  },
  methods: {
    getDates() {
      const today = new Date();
      const yesterday = new Date(today);
      const twoDaysAgo = new Date(today);
      const threeDaysAgo = new Date(today);
      const fourDaysAgo = new Date(today);

      yesterday.setDate(today.getDate() - 1);
      twoDaysAgo.setDate(today.getDate() - 2);
      threeDaysAgo.setDate(today.getDate() - 3);
      fourDaysAgo.setDate(today.getDate() - 4);

      this.dateToday = today.toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
      this.dateYesterday = yesterday.toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
      this.dateTwoDaysAgo = twoDaysAgo.toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
      this.dateThreeDaysAgo = threeDaysAgo.toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
      this.dateFourDaysAgo = fourDaysAgo.toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
    },
    getFixtures: function() {
      $.ajax({
        type: "GET",
        url: "/rugby/getRecentFixtures/",
        success: (data) => {
          this.fixtures = data;
        }
      })
    }
  },
  created() {
    this.getDates();
    this.getFixtures();
  }
}
</script>