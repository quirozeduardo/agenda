<template>
  <div id="app">
    <v-app>
      <v-navigation-drawer fixed :clipped="$vuetify.breakpoint.mdAndUp" app v-model="drawer" v-if="this.$store.state.logged" width="250">
        <v-list dense>
          <v-list-tile to="/home">
            <v-list-tile-action>
              <v-icon>fa fa-home</v-icon>
            </v-list-tile-action>
            <v-list-tile-title>Main</v-list-tile-title>
          </v-list-tile>
          <v-divider></v-divider>
          <v-list-tile to="/configuration">
            <v-list-tile-action>
              <v-icon>fa fa-cog</v-icon>
            </v-list-tile-action>
            <v-list-tile-title>Configuration</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-navigation-drawer>
      <v-toolbar color="blue darken-3" dark app :clipped-left="$vuetify.breakpoint.mdAndUp" fixed dark height="40">
        <v-toolbar-title>
          <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
          <span class="hidden-sm-and-down">Agenda</span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items v-if="!this.$store.state.logged" >
          <v-btn to="/login" flat>Login</v-btn>
          <v-btn to="/register" flat>Register</v-btn>
        </v-toolbar-items>
        <v-toolbar-items v-else>
          <v-btn icon>
            <v-icon>
              fa-bell
            </v-icon>
          </v-btn>
          <v-btn to="/logout" flat>Log Out</v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-content>
        <router-view/>
      </v-content>
    </v-app>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  components: {
  },
})
export default class App extends Vue {
  private drawer: boolean = true;

  private mounted(): void {
    this.$store.dispatch('retrieveConfigurations');
  }
}
</script>

<style lang="scss">
  @import '~vuetify/dist/vuetify.css';

</style>
