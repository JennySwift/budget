<template>
  <div id="app">
    <!--<navbar></navbar>-->

    <feedback></feedback>
    <loading></loading>

    <div class="main">
        <router-view/>
    </div>

    <footer>
      <li><a href="http://jennyswiftcreations.com/privacy-policy">Privacy Policy</a></li>

      <li><a href="http://jennyswiftcreations.com/credits">Credits</a></li>
    </footer>
  </div>
</template>

<script>
    // import NavbarComponent from './components/shared/NavbarComponent.vue'
    import LoadingComponent from './components/shared/LoadingComponent.vue'
    import FeedbackComponent from './components/shared/FeedbackComponent.vue'

    export default {
    name: 'app',
    components: {
        // 'navbar': NavbarComponent,
        'feedback': FeedbackComponent,
        'loading': LoadingComponent,
    }
}
</script>