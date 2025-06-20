<template>
    
    <q-table
        title="Transactions"
        :loading="loading"
        :rows="filteredTransactions"
        :columns="columnsTransaction"
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
                <q-td key="limit_quantity" :props="props">
                    {{ props.row.price?.limit_quantity ?? '0' }}
                </q-td>
                <q-td key="limit_storage" :props="props">
                    {{ props.row.price?.limit_storage ?? '0' }} GB
                </q-td>

                <q-td key="created_at" :props="props">
                    <span>{{ $tp.date(props.row.created_at) }}</span><br>
                    <span class="text-caption"><i>{{ props.row.status }}</i></span>
                </q-td>
                <q-td key="expiration_date" :props="props">
                    {{ $tp.date(props.row.access?.expiration_date ?? '-') }}
                </q-td>

                <q-td key="price" :props="props">
                    {{ props.row.currency_code + ' ' + props.row.total }}<br>
                </q-td>
                <q-td key="tax" :props="props">
                    {{ props.row.tax }}
                </q-td>
            </q-tr>
        </template>
        
    </q-table>

</template>

<script>
import { ref } from 'vue';

export default {
    name: 'UserTransactionsTable',

    props: {
        //
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
                label: 'Token',
                field: 'name',
                align: 'left',
            },{
                name: 'limit_quantity',
                label: 'Event Credits',
                field: 'limit_quantity',
                align: 'left',
            }, {
                name: 'limit_storage',
                label: 'Cloud Storage',
                field: 'limit_storage',
                align: 'left',
            }, {
                name: 'created_at',
                label: 'Created at',
                field: 'created_at',
                align: 'left',
            }, {
                name: 'expiration_date',
                label: 'Renewal date',
                field: 'expiration_date',
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
            }
        ];

        return {
            loading: ref(false),
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
    },

    data() {
        return {
            transactions: [],
        }
    },

    async mounted() {
        this.loadAccess();
    },

    methods: {

        async loadAccess(){
            try {
                this.loading = true;
                const response = await this.$axios.get('user-load-transactions')
                this.transactions = response.data.transactions;
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>