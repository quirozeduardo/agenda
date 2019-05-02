<template>
    <v-container>
        <v-card>
            <v-card-title>
                Departments
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="fa-search" label="Search" single-line hide-details></v-text-field>
                <v-btn color="primary" dark class="mb-2" v-on:click="openDialog()">New Department</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="this.$store.state.departmentsAdmin" :search="search">
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
                    <span class="headline">Department</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-form v-model="valid">
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field label="Name*" v-model="nameInsert" :rules="[textFieldRequierd]" required></v-text-field>
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
    import  Category from "../../objects/types/Category";
    import {UserType} from "../../objects/types/admin/UserType";
    import {Department} from "../../objects/types/admin/Department";
    @Component
    export default class UserTypes extends Vue {
        private valid: boolean =  false;
        private dialog: boolean = false;
        private dialogDelete: boolean = false;
        private search: any = '';
        private update = false;
        private item: Category|any = null;

        private nameInsert: string = '';
        private descriptionInsert: string = '';
        private headers = [
            { text: 'Name', value: 'name', align: 'center' },
            { text: 'Description', value: 'description', align: 'center' },
            { text: 'Actions', value: 'description', align: 'center'},
        ];

        private saveItem(): void {
            if (this.nameInsert.trim().length > 0) {
                if(this.update === true) {
                    this.updateItem(this.item);
                }else{
                    this.storeItem();
                }
            }

            this.dialog = false;

        }
        private openDialog(item: Department|null = null): void {
            this.dialog = true;
            if (item === null) {
                this.nameInsert = '';
                this.descriptionInsert = '';
                this.update = false;
            }else {
                this.nameInsert = item.name;
                this.descriptionInsert = item.description;
                this.update = true;
            }
        }
        private editItem(item: Department): void {
            this.item = item;
            this.openDialog(item);
        }
        private storeItem(): void {
            let category = new Department();
            category.name = this.nameInsert;
            category.description = this.descriptionInsert;
            this.$store.dispatch('storeDepartmentAdmin',category);
        }
        private updateItem(item: Department): void {
            let itemUpdate = new Department();
            itemUpdate.id = this.item.id;
            itemUpdate.name = this.nameInsert;
            itemUpdate.description = this.descriptionInsert;
            this.$store.dispatch('updateDepartmentAdmin',itemUpdate);
        }
        private deleteItem(item: Department): void {
            this.dialogDelete = false;
            this.$store.dispatch('deleteDepartmentAdmin',item);
        }
        private mounted():void {
            this.$store.dispatch('retrieveDepartmentsAdmin');
        }
        openDialogDeleteItem(item: Department) {
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
