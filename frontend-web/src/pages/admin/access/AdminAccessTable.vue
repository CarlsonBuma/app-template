<template>
    
    <q-table
        flat
        row-key="id"
        :rows="access"
        :columns="columnsTransaction"
        :title="title"
        :pagination="{
            rowsPerPage: 7
        }"
    >
        <template v-slot:top-right>
             <!-- Search user -->
            <q-input 
                class="w-input" 
                label="Search by email"
                v-model="searchString" 
                @keyup.enter="$emit('search', searchString)"
            >
                <template v-slot:append>
                    <q-btn 
                        icon="search" 
                        color="primary" 
                        @click="$emit('search', searchString)"
                    />
                </template>
            </q-input>

            <!-- Add access -->
            <q-btn icon="add" color="primary" :disable="!searchString" outline @click="$emit('add', searchString)"/>
        </template>

        <template v-slot:body="props">
            <q-tr :props="props">
                <q-td key="id" :props="props">
                    {{ props.rowIndex + 1 }}
                </q-td>
                <q-td key="is_active" :props="props">
                    <q-checkbox v-model="props.row.is_active"/>
                </q-td>
                <q-td key="name" :props="props">
                    {{ props.row.price?.name ?? 'Individual Token' }}<br>
                    <span class="text-caption"><em>"{{ props.row.access_token }}"</em></span>
                </q-td>
                <q-td key="quantity" :props="props">
                    {{ props.row.quantity }}<br>
                </q-td>
                <q-td key="expiration_date" :props="props">
                    {{ $tp.date(props.row.expiration_date ?? '-') }}
                </q-td>
                <q-td key="access_type" :props="props">
                    {{ props.row.price?.type ?? 'private' }}<br>
                    <span class="text-caption">Status: {{ props.row.subscription?.status ?? 'Closed' }}</span>
                </q-td>
                <q-td key="status" :props="props">
                    {{ props.row.status }}<br>
                    <span class="text-caption">{{ props.row.message }}</span>
                </q-td>
                <q-td key="actions" :props="props">
                    <q-btn outline icon="update" size="sm" color="primary" @click="$emit('update', props.row)" />
                </q-td>
            </q-tr>
        </template>
    </q-table>

</template>

<script>
import { ref } from 'vue';

export default {
    name: 'AdminAccessTable',

    props: {
        title: String,
        access: Array
    },

    emits: [
        'add',
        'search',
        'update'
    ],

    setup() {
        const searchString = ref('');
        const columnsTransaction = [
            {
                name: 'id',
                label: 'ID',
                field: 'id',
                align: 'left',
            }, {
                name: 'is_active',
                label: 'Active',
                field: 'is_active',
                align: 'left',
            }, {
                name: 'name',
                label: 'Access Token',
                field: 'name',
                align: 'left',
                sortable: true
            }, {
                name: 'quantity',
                label: 'Quantity',
                field: 'quantity',
                align: 'left',
            }, {
                name: 'expiration_date',
                label: 'Expiration',
                field: 'expiration_date',
                align: 'left',
            }, {
                name: 'access_type',
                label: 'Access Type',
                field: 'access_type',
                align: 'left',
            }, {
                name: 'status',
                label: 'Status',
                field: 'status',
                align: 'left',
                sortable: true
            }, {
                name: 'actions',
                label: 'Actions',
                field: 'actions',
            },
        ];

        return {
            searchString,
            columnsTransaction
        };
    },
};
</script>