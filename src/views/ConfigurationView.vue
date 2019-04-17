<template>
    <v-container>
        <v-layout flex justify-center>
            <v-radio-group :value="'category'" row left>
                <v-radio :label="'Category'" :value="'category'"></v-radio>
                <v-radio :label="'Impact'" :value="'impact'"></v-radio>
            </v-radio-group>
        </v-layout>
        <v-layout flex justify-center>
            <v-btn to="/configuration/category">Category</v-btn>
            <v-btn to="/configuration/priority">Priority</v-btn>
            <v-btn to="/configuration/impact">Impact</v-btn>
            <v-btn to="/configuration/status">Status</v-btn>
        </v-layout>
        <router-view/>
    </v-container>

</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';

    @Component
    export default class ConfigurationView extends Vue {
    }
</script>

<style scoped lang="scss">

</style>
