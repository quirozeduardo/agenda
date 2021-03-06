<template>
    <v-container>
        <v-card>
            <v-card-title>
                Statuses
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="fa-search" label="Search" single-line hide-details></v-text-field>
                <v-btn color="primary" dark class="mb-2" v-on:click="openDialog()">New Status</v-btn>
            </v-card-title>
            <v-data-table :headers="headers" :items="this.$store.state.statuses" :search="search">
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
                    <span class="headline">Status</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-form v-model="valid">
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field label="Name*" v-model="nameInsert" :rules="[textFieldRequierd]" required></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <p>Override</p>
                                <v-radio-group v-model="overrideInsert">
                                    <v-radio :value="'0'" :label="'No'"></v-radio>
                                    <v-radio :value="'1'" :label="'Yes'"></v-radio>
                                </v-radio-group>
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
    import  Status from "../../objects/types/Status";
    @Component
    export default class StatusView extends Vue {
        private valid: boolean =  false;
        private dialog: boolean = false;
        private dialogDelete: boolean = false;
        private search: any = '';
        private update = false;
        private item: Status|any = null;

        private nameInsert: string = '';
        private descriptionInsert: string = '';
        private overrideInsert: string = '0';
        private colorInsert: string = '';
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
        private openDialog(item: Status|null = null): void {
            this.dialog = true;
            if (item === null) {
                this.nameInsert = '';
                this.descriptionInsert = '';
                this.colorInsert = '';
                this.overrideInsert = '0';
                this.update = false;
            }else {
                this.nameInsert = item.name;
                this.descriptionInsert = item.description;
                this.colorInsert = item.color;
                this.overrideInsert = String(item.override);
                this.update = true;
            }
        }
        private editItem(item: Status): void {
            this.item = item;
            this.openDialog(item);
        }
        private storeItem(): void {
            let item = new Status();
            item.name = this.nameInsert;
            item.description = this.descriptionInsert;
            item.color = this.colorInsert;
            item.override = Number(this.overrideInsert);
            this.$store.dispatch('storeStatus',item);
        }
        private updateItem(item: Status): void {
            let itemUpdate = new Status();
            itemUpdate.id = this.item.id;
            itemUpdate.name = this.nameInsert;
            itemUpdate.description = this.descriptionInsert;
            itemUpdate.color = this.colorInsert;
            itemUpdate.override = Number(this.overrideInsert);
            this.$store.dispatch('updateStatus',itemUpdate);
        }
        private deleteItem(item: Status): void {
            this.dialogDelete = false;
            this.$store.dispatch('deleteStatus',item);
        }
        private mounted():void {
            this.$store.dispatch('retrieveStatuses');
        }
        openDialogDeleteItem(item: Status) {
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
