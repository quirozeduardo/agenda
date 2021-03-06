<template>
    <v-container>
        <v-card>
            <v-card-title>
                Impacts
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="fa-search" label="Search" single-line hide-details></v-text-field>
                <v-btn color="primary" dark class="mb-2" v-on:click="openDialog()">New Impact</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="this.$store.state.impacts" :search="search">
                <template v-slot:items="props">
                    <td class="text-xs-center">{{ props.item.name }}</td>
                    <td class="text-xs-center">{{ props.item.description }}</td>
                    <td class="justify-center layout px-0">
                        <v-icon small class="mr-2" @click="editItem(props.item)">
                            fa-edit
                        </v-icon>
                        <v-icon small @click="openDialogDeleteItem(props.item)" color="red">
                            fa-trash
                        </v-icon>
                    </td>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="headline">Impact</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-form v-model="valid">
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field label="Name*" v-model="nameInsert" :rules="[textFieldRequierd]" required></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-slider v-model="importanceInsert" :label="String(importanceInsert)" thumb-label="always" :thumb-color="'primary'" :max="100" :min="1"></v-slider>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Color" v-model="colorInsert" required></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Description" v-model="descriptionInsert"></v-text-field>
                            </v-flex>
                        </v-layout>
                        </v-form>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialog = false">Close</v-btn>
                    <v-btn color="blue darken-1" flat @click="saveItem()">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="290">
            <v-card>
                <v-card-title class="headline">Delete</v-card-title>

                <v-card-text>
                    Are you sure you want to delete it?
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="blue darken-1" flat="flat" @click="dialogDelete = false">
                        Close
                    </v-btn>

                    <v-btn color="red darken-1" flat="flat" @click="deleteItem(item);">
                        Delete
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';
    import  Impact from "../../objects/types/Impact";
    @Component
    export default class ImpactView extends Vue {
        private valid: boolean =  false;
        private dialog: boolean = false;
        private dialogDelete: boolean = false;
        private search: any = '';
        private update = false;
        private item: Impact|any = null;

        private nameInsert: string = '';
        private descriptionInsert: string = '';
        private importanceInsert: string = '1';
        private colorInsert: string = '';

        private headers = [
            { text: 'Name', value: 'name', align: 'center' },
            { text: 'Description', value: 'description', align: 'center' },
            { text: 'Actions', value: 'description', align: 'center'},
        ];

        private saveItem(): void {
            if (this.nameInsert.trim().length > 0 && Number(this.importanceInsert)>0 && Number(this.importanceInsert)<=100) {
                if(this.update === true) {
                    this.updateItem(this.item);
                }else{
                    this.storeItem();
                }
            }

            this.dialog = false;

        }
        private openDialog(item: Impact|null = null): void {
            this.dialog = true;
            if (item === null) {
                this.nameInsert = '';
                this.descriptionInsert = '';
                this.colorInsert = '';
                this.importanceInsert = '1';
                this.update = false;
            }else {
                this.nameInsert = item.name;
                this.descriptionInsert = item.description;
                this.colorInsert = item.color;
                this.importanceInsert = String(item.importance);
                this.update = true;
            }
        }
        private editItem(item: Impact): void {
            this.item = item;
            this.openDialog(item);
        }
        private storeItem(): void {
            let item = new Impact();
            item.name = this.nameInsert;
            item.description = this.descriptionInsert;
            item.color = this.colorInsert;
            item.importance = Number(this.importanceInsert);
            this.$store.dispatch('storeImpact',item);
        }
        private updateItem(item: Impact): void {
            let itemUpdate = new Impact();
            itemUpdate.id = this.item.id;
            itemUpdate.name = this.nameInsert;
            itemUpdate.description = this.descriptionInsert;
            itemUpdate.color = this.colorInsert;
            itemUpdate.importance = Number(this.importanceInsert);
            this.$store.dispatch('updateImpact',itemUpdate);
        }
        private deleteItem(item: Impact): void {
            this.dialogDelete = false;
            this.$store.dispatch('deleteImpact',item);
        }
        private mounted():void {
            this.$store.dispatch('retrieveImpacts');
        }
        openDialogDeleteItem(item: Impact) {
            this.item = item;
            this.dialogDelete = true;
        }
        public textFieldRequierd(v: any) {
            return !!v || 'This Field is required'
        }
    }
</script>

<style scoped lang="scss">

</style>
