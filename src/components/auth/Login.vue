<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12">
                    <v-toolbar dark color="primary">
                        <v-toolbar-title>Login form</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field prepend-icon="fa-user" name="login" label="Login" type="text" v-model="email"></v-text-field>
                            <v-text-field prepend-icon="fa-envelope" name="password" label="Password" type="password" v-model="password"></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" to="/register">Register</v-btn>
                        <v-btn color="primary" v-on:click="login()">Login</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';
    import UserLogin from "../../objects/types/UserLogin";

    @Component
    export default class Login extends Vue {
        private email: string = '';
        private password: string = '';

        private async login():Promise<void> {
            let userLogin = new UserLogin();
            userLogin.email = this.email;
            userLogin.userName = this.email;
            userLogin.password = this.password;

            let response = await this.$store.dispatch('login', userLogin);
            if (response === null) {

            } else {
                this.$router.push('home');
            }
        }
    }
</script>

<style lang="scss">

</style>
