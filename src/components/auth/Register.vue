<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-alert :value="userNameInUse" type="error" v-on:click="userNameInUse=false">This User Name is in use.</v-alert>
                <v-alert :value="emailInUse" type="error" v-on:click="emailInUse=false">This Email is in use.</v-alert>
                <v-card class="elevation-12">
                    <v-toolbar dark color="primary">
                        <v-toolbar-title>Register</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form v-model="valid">
                            <v-text-field prepend-icon="fa-user" label="Name" type="text" :rules="[textFieldRequierd]" v-model="name" required></v-text-field>
                            <v-text-field prepend-icon="fa-user" label="Last Name" type="text" :rules="[textFieldRequierd]" v-model="lastName" required></v-text-field>
                            <v-text-field prepend-icon="fa-user" label="User Name" type="text" :rules="[textFieldRequierd]" v-model="userName" required></v-text-field>
                            <v-text-field prepend-icon="fa-envelope" label="Email" type="text" :rules="[textFieldRequierd, validEmail]" v-model="email" required></v-text-field>
                            <v-text-field prepend-icon="fa-key" label="Password" type="password" :rules="[textFieldRequierd,passwordValidation]" v-model="password"></v-text-field>
                            <v-text-field prepend-icon="fa-key" label="Confirm Password" type="password" :rules="[textFieldRequierd,passwordValidation,passwordConfirmtionValidation]" v-model="confirmPassword"></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="success" to="/login">Login</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" v-on:click="register()">Register</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';
    import UserRegister from "../../objects/types/UserRegister";
    import UserLogin from "../../objects/types/UserLogin";

    @Component
    export default class Register extends Vue {
        private name: string = '';
        private lastName: string = '';
        private userName: string = '';
        private email: string = '';
        private password: string = '';
        private confirmPassword: string = '';
        private valid: boolean =  false;

        private userNameInUse: boolean = false;
        private emailInUse: boolean = false;

        private async register(): Promise<void> {
            if (this.password.trim() !== this.confirmPassword.trim()) {
                return ;
            }
            console.log(this.valid);
            if (this.valid !== true) {
                return;
            }
            let userRegister = new UserRegister();
            userRegister.name = this.name;
            userRegister.lastName = this.lastName;
            userRegister.userName = this.userName;
            userRegister.email = this.email;
            userRegister.password = this.password;

            let response:number = await this.$store.dispatch('registerUser', userRegister);
            if (response === 0) {
                console.log('error');
            } else if (response === 1) {
                let userLogin: UserLogin = new UserLogin();
                userLogin.email = userRegister.email;
                userLogin.password = userRegister.password;
                userLogin.userName = userRegister.userName;
                await this.$store.dispatch('login', userLogin);
                this.$router.push('home');
            }  else if (response === 2) {
                this.userNameInUse = true;
                setTimeout(()=> {
                    this.userNameInUse = false;
                },5000);
            }  else if (response === 3) {
                this.emailInUse = true;
                setTimeout(()=> {
                    this.emailInUse = false;
                },5000);
            }
        }
        public textFieldRequierd(v: any) {
            return !!v || 'This Field is required'
        }
        public validEmail(v: any) {
            return /.+@.+/.test(v) || 'E-mail must be valid'
        }
        public passwordValidation(v: string) {
            return (v.length >5) || 'Password must contain more than 5 characters'
        }
        public passwordConfirmtionValidation(v: string) {
            return String(v) === this.password || 'Password Not Match'
        }
    }
</script>

<style lang="scss">

</style>
