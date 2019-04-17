<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-card class="elevation-12">
                    <v-toolbar dark color="primary">
                        <v-toolbar-title>Register form</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field prepend-icon="fa-user" label="Name" type="text" v-model="name"></v-text-field>
                            <v-text-field prepend-icon="fa-user" label="Last Name" type="text" v-model="lastName"></v-text-field>
                            <v-text-field prepend-icon="fa-user" label="User Name" type="text" v-model="userName"></v-text-field>
                            <v-text-field prepend-icon="fa-envelope" label="Email" type="text" v-model="email"></v-text-field>
                            <v-text-field prepend-icon="fa-key" label="Password" type="password" v-model="password"></v-text-field>
                            <v-text-field prepend-icon="fa-key" label="Confirm Password" type="password" v-model="confirmPassword"></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" v-on:click="register()">Register</v-btn>
                        <v-btn color="primary" to="/login">Login</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';
    import UserRegister from "../../objects/UserRegister";

    @Component
    export default class Register extends Vue {
        private name: string = '';
        private lastName: string = '';
        private userName: string = '';
        private email: string = '';
        private password: string = '';
        private confirmPassword: string = '';

        private async register(): Promise<void> {
            if (this.password.trim() !== this.confirmPassword.trim()) {
                console.log('password not match');
                return ;
            }

            let userRegister = new UserRegister();
            userRegister.name = this.name;
            userRegister.lastName = this.lastName;
            userRegister.userName = this.userName;
            userRegister.email = this.email;
            userRegister.password = this.password;

            let response = await this.$store.dispatch('registerUser', userRegister);
            if (response === false) {
                console.log('error');
            } else {
                this.$router.push('home');
            }
        }
    }
</script>

<style lang="scss">

</style>
