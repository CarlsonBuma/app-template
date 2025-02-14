<template>
    
    <q-table
        flat
        :rows="filteredTransactions"
        :columns="columnsTransaction"
        :title="title"
        row-key="id"
        :pagination="{
            rowsPerPage: 7
        }"
    >
        <template v-slot:top-right>
            <q-input borderless dense debounce="300" v-model="filterInput" placeholder="Search">
                <template v-slot:append>
                    <q-icon name="search" />
                </template>
            </q-input>
        </template>

        <template v-slot:body="props">
            <q-tr :props="props">
                <q-td key="id" :props="props">
                    {{ props.rowIndex + 1 }}
                </q-td>
                <q-td key="name" :props="props">
                    {{ props.row.price?.name ?? 'No price assigned.' }}<br>
                    <span class="text-caption"><em>"{{ props.row.price?.access_token ?? 'undefined' }}"</em></span>
                </q-td>
                <q-td key="quantity" :props="props">
                    {{ props.row.quantity }}
                </q-td>
                <q-td key="price" :props="props">
                    {{ props.row.currency_code + ' ' + props.row.total }}
                </q-td>
                <q-td key="tax" :props="props">
                    {{ props.row.tax }}
                </q-td>
                <q-td key="active" :props="props">
                    <q-icon name="verified" :color="props.row.access ? 'green' : 'grey'" />
                </q-td>
                <q-td key="expiration_date" :props="props">
                    {{ $tp.date(props.row.access?.expiration_date ?? '-') }}
                </q-td>
                <q-td key="status" :props="props">
                    {{ props.row.status }}<br>
                    <span class="text-caption">{{ props.row.message }}</span>
                </q-td>
                <q-td key="updated_at" :props="props">
                    {{ $tp.date(props.row.updated_at) }}
                </q-td>
            </q-tr>
        </template>
    </q-table>

</template>

<script>
import { ref } from 'vue';

export default {
    name: 'AdminTransactionsTable',

    props: {
        title: String,
        transactions: Array
    },

    setup() {
        const columnsTransaction = [
            {
                name: 'id',
                label: 'ID',
                field: 'id',
                align: 'left',
            }, {
                name: 'name',
                label: 'Access',
                field: 'name',
                align: 'left',
                sortable: false
            }, {
                name: 'quantity',
                label: 'Quantity',
                field: 'quantity',
                align: 'left',
            }, {
                name: 'price',
                label: 'Total (incl. Tax)',
                field: 'price',
                align: 'left',
            }, {
                name: 'tax',
                label: 'Tax',
                field: 'tax',
                align: 'left',
            }, {
                name: 'active',
                label: 'has access',
                field: 'active',
            }, {
                name: 'expiration_date',
                label: 'Expiration date',
                field: 'expiration_date',
                align: 'left',
                sortable: false
            }, {
                name: 'status',
                label: 'Status',
                field: 'status',
                align: 'left',
            }, {
                name: 'updated_at',
                label: 'Latest update',
                field: 'updated_at',
                align: 'left',
            },
        ];

        return {
            columnsTransaction,
            filterInput: ref(''),
        };
    },

    computed: {
        filteredTransactions() {
            if (!this.filterInput) return this.transactions;
            const filter = this.filterInput.toLowerCase();
            return this.transactions.filter(row => {
                const price = row.price || {};
                return (price.name && price.name.toLowerCase().includes(filter)) ||
                    (price.access_token && price.access_token.toLowerCase().includes(filter));
            });
        }
    }
};
</script>