<style lang="sass" scoped>
.column-default-width
    min-width: 220px
.column-first-width
    min-width: 280px
</style>

<template>
    
    <div class="container">

        <!-- 1. Row / Header -->
        <div class="row no-wrap flex">

            <!-- TH - Table Handling (x & y) -->
            <div class="column-first-width">
                <CardSimple>
                    <q-card-section class="text-right">
                        <q-btn 
                            label="Add X"
                            icon="add" 
                            size="sm"  
                            color="primary"
                            @click="addNewColumn(newColumn)" 
                        />
                    </q-card-section>
                    <span class="text-caption flex w-100 justify-center">Find the 🐞!</span>
                    <q-card-section class="text-left">
                        <q-btn 
                            label="Add Y"
                            icon="add" 
                            size="sm"  
                            color="primary"
                            @click="addNewRow(newRow, columns)" 
                        />
                    </q-card-section>
                </CardSimple>
            </div>
            
            <!-- TH - columns as Columns -->
            <div class="column-default-width" v-for="(role, index) in columns" :key="index">
                <CardSimple >
                    <q-list class="q-pb-none">
                        <q-item>
                            <q-item-section>
                                <q-item-label caption>X #{{ index + 1 }}</q-item-label>
                            </q-item-section>
                            <q-item-section class="text-caption" side top>
                                <q-btn icon="delete" size="6px" @click="columns.splice(index, 1)" dense color="red"/>
                            </q-item-section>
                        </q-item>
                    </q-list>
                    <q-separator />
                    <q-card-section class="q-pa-sm">
                        <q-input dense placeholder="Name x" v-model="role.title" />
                    </q-card-section>
                </CardSimple>
            </div>
        </div>

        <!-- Rows N -->
        <q-separator class="q-my-sm w-100" />
        <div class="row no-wrap" v-for="(workflow, indexWorkflow) in rows" :key="indexWorkflow">
            
            <!-- Default Column / rows -->
            <div class="column-first-width">
                <CardSimple>
                    <q-list>
                        <q-item>
                            <q-item-section>
                                <q-item-label>Y #{{ indexWorkflow + 1 }}</q-item-label>
                            </q-item-section>
                            <q-item-section class="text-caption" side top>
                                <q-btn icon="delete" size="6px" @click="rows.splice(indexWorkflow, 1)" dense color="red"/>
                            </q-item-section>
                        </q-item>
                    </q-list>
                    <q-separator />
                    <q-card-section>
                        <q-input 
                            autogrow 
                            dense 
                            maxlength="199" 
                            type="textarea" 
                            placeholder="Define y" 
                            v-model="workflow.definition" 
                        />

                        <!-- q-item-section class="text-caption" side top>
                            <span>Total time: 12h</span>
                            <span>Quality Points: 3</span>
                            <span>Complexity: 5</span>
                            <span>columns: 3</span>
                        </q-item-section> -->
                    </q-card-section>
                </CardSimple>
            </div>

            <div class="column-default-width q-pa-md" v-for="(role, indexRole) in workflow.columns" :key="indexRole">
                <div class="text-center w-100">
                    <q-btn 
                        icon="add" 
                        round outline 
                        color="primary" 
                        @click="rows[indexWorkflow].columns[indexRole].linked = !rows[indexWorkflow].columns[indexRole].linked"
                    /><br>
                    <span class="text-caption">X: {{ indexRole }}</span><br>
                    <span class="text-caption">Y: {{ indexWorkflow }} </span><br>
                    <span class="text-caption">Linked: {{ role.linked }} </span>
                </div> 
            </div>                        
        </div>
    </div>

</template>

<script>
import { ref } from 'vue';

export default {
    name: 'MatrixMapper',
    components: {
        // Components
    },

    setup() {
        return {
            rows: ref([]),
            columns: ref([]),

            newColumn: ref({
                title: '',
                linked: false
            }),
            
            newRow: ref({
                definition: '',
                columns: []
            }),
            
            
        }
    },

    data() {
        return {
            // Code
        }
    },

    mounted() {
        // Code
    },

    methods: {
        addNewRow(workflow, columns) {
            workflow.columns = columns
            this.rows.push(workflow)
            this.newRow = {
                definition: '',
                columns: []
            };

            console.log('new row', this.rows)
        },

        addNewColumn(role) {
            this.columns.push(role)
            this.newColumn = {
                title: '',
                linked: false
            };
        }
    }
};
</script>