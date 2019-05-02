<template>
  <div id="content-main">
    <v-app dark>
        <div class="bg-joyson">
            <v-navigation-drawer fixed :clipped="$vuetify.breakpoint.mdAndUp" app v-model="drawer" v-if="this.$store.state.logged" width="250">
                <v-list dense>
                    <v-list-tile to="/home">
                        <v-list-tile-action>
                            <v-icon>fa fa-home</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title>Main</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile v-for="route in routesDepartments()" :key="route.id" :to="route.route">
                        <v-list-tile-action>
                            <v-icon>fa fa-folder</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title>{{ route.title}}</v-list-tile-title>
                    </v-list-tile>
                    <v-divider></v-divider>
                    <v-list-tile v-if="isAdmin()" to="/users">
                        <v-list-tile-action>
                            <v-icon>fa fa-users</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title>Users</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile v-if="isAdmin()" to="/configuration">
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
                    <span class="hidden-sm-and-down">JOYSONQUIN</span>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items v-if="!this.$store.state.logged" >
                    <v-btn to="/login" flat>Login</v-btn>
                    <v-btn to="/register" flat>Register</v-btn>
                </v-toolbar-items>
                <v-toolbar-items v-else>
                    <h4 class="mt-2">
                        {{ nameUser }}
                    </h4>
                    <v-btn to="/logout" flat>
                        <v-icon>fas fa-sign-out-alt</v-icon>
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-content>
                <!--<v-progress-linear :indeterminate="true"></v-progress-linear>-->
                <router-view :key="$route.fullPath"></router-view>
            </v-content>
        </div>
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
  private nameUser: string = '';
  private routesDepartments(){
      let loggedUser = this.$store.state.loggedUser;
      let routesDepartments = [];
      if (loggedUser !== null) {
          if (loggedUser.departments !== null) {
              if (loggedUser.departments.length > 0) {
                  for (let i = 0; i < loggedUser.departments.length; i++) {
                      let dp = loggedUser.departments[i];
                      routesDepartments.push({
                          title: `${dp.name}`,
                          route: `/tasks/department/${dp.id}`,
                      });
                  }
              }
          }
      }
      return routesDepartments;
  }
  private isAdmin() {
      let loggedUser = this.$store.state.loggedUser;
      if (loggedUser !== null) {
          if (loggedUser.userTypes !== null) {
              if (loggedUser.userTypes.length > 0) {
                  for (let i = 0; i < loggedUser.userTypes.length; i++) {
                      let ut = loggedUser.userTypes[i];
                      if (Number(ut.id)===1) {
                          return true;
                      }
                  }
              }
          }
      }
      return false;
  }
  private async mounted(): Promise<void> {
    await this.$store.dispatch('retrieveConfigurations');
    if (this.$store.state.loggedUser !== null && this.$store.state.loggedUser) {
        this.nameUser = this.$store.state.loggedUser.name + ' ' + ((this.$store.state.loggedUser.lastName)?this.$store.state.loggedUser.lastName: '');
    } else {
        this.nameUser = '';
    }
  }
}
</script>

<style lang="scss">
  @import "~vuetify/dist/vuetify.min.css";
  .theme--light.application {
      background: transparent;
  }
    .bg-joyson{
        background: #000000;
        height: 100%;
        opacity: 0.9;
    }
</style>