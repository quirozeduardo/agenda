<template>
    <v-container>
        <v-flex>
            <v-select :items="quickResolveOptions" :value="quickResolveOptionSelected" item-text="name" label="Quick Resolve" v-on:change="changedQuickResolved" item-value="id">
            </v-select>
        </v-flex>
        <v-layout flex justify-center>
            <v-btn to="/configuration/status">Status</v-btn>
            <v-btn to="/configuration/priority">Priority</v-btn>
            <v-btn to="/configuration/impact">Impact</v-btn>
            <v-btn to="/configuration/userType">User Type</v-btn>
            <v-btn to="/configuration/department">Department</v-btn>
            <v-btn to="/configuration/category">Category</v-btn>
        </v-layout>
        <router-view/>
    </v-container>

</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';
    import Status from "../objects/types/Status";
    import {Configuration} from "../objects/types/Configuration";

    @Component
    export default class ConfigurationView extends Vue {
        private quickResolveOptions: Status[] = [];
        private quickResolveOptionSelected = 0;
        private async mounted(): Promise<void> {
            await this.$store.dispatch('retrieveStatuses');
            this.quickResolveOptions = this.$store.getters.getStatuses;
            let statusNull = new Status();
            statusNull.id = 0;
            statusNull.name = 'Null';
            this.quickResolveOptions.unshift(statusNull);

            let configurations = await this.$store.getters.getConfigurationsByKey('quick_resolved');
            if (configurations.length < 1){
                this.quickResolveOptionSelected = 0;
            }else {
                let configuration =  configurations[configurations.length-1];
                this.quickResolveOptionSelected = Number(configuration.value);
            }
        }
        private async changedQuickResolved(id: number): Promise<void> {
            let configurations = await this.$store.getters.getConfigurationsByKey('quick_resolved');
            if (configurations.length < 1){
                let configuration =  new Configuration();
                configuration.key = 'quick_resolved';
                configuration.value = String(id);
                this.$store.dispatch('storeConfiguration',configuration);
            }else {
                let configuration =  configurations[configurations.length-1];
                let updateConfiguration = new Configuration();
                updateConfiguration.id = configuration.id;
                updateConfiguration.key = 'quick_resolved';
                updateConfiguration.value = String(id);
                this.$store.dispatch('updateConfiguration',updateConfiguration);
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
