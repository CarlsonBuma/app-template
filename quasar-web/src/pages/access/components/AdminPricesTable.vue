<template>
    
    <q-table
        flat
        :title="title"
        row-key="id"
        :rows="prices"
        :columns="columnsProducts"
        :pagination="{
            rowsPerPage: 5
        }"
    >
        <!-- Prices -->
        <template v-slot:header-cell="props">
            <q-th :props="props">
                {{ props.col.label }}
                <q-icon v-if="props.col.note" name="help_outline" size="14px" color="primary" >
                    <q-tooltip>
                        <p class="q-ma-none w-tooltip">{{ props.col.note }}</p>
                    </q-tooltip> 
                </q-icon>
            </q-th>
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
                    <span>{{ props.row.name }}</span><br>
                    <span class="text-caption"><em>"{{ props.row.access_token }}"</em></span>
                </q-td>
                <q-td key="price" :props="props">
                    {{ props.row.currency_code + ' ' + props.row.price }}
                </q-td>
                <q-td key="billing_type" :props="props">
                    {{ props.row.type }}
                </q-td>
                <q-td key="billing_period" :props="props">
                    {{ 
                        props.row.billing_frequency 
                            ? props.row.billing_frequency + ' ' + props.row.billing_interval 
                            : props.row.duration_months 
                                ? props.row.duration_months + ' month' 
                                : 'none'
                    }}
                </q-td>
                <q-td key="trial_mode" :props="props">
                    {{ 
                        props.row.trial_frequency 
                            ? props.row.trial_frequency + ' ' + props.row.trial_interval + 's'
                            : 'none' 
                    }}
                </q-td>
                <q-td key="status" :props="props">
                    {{ props.row.status }}
                </q-td>
                <q-td key="actions" :props="props">
                    <q-btn 
                        icon="update"
                        size="sm"
                        outline
                        color="primary"
                        @click="$emit('update', props.row)"
                    />
                </q-td>
            </q-tr>
        </template>
    </q-table>

</template>

<script>


export default {
    name: 'AdminPricesTable',

    props: {
        title: String,
        prices: Array
    },

    emits: [
        'search',
        'update',
    ],

    setup() {
        const columnsProducts = [
            {
                name: 'id',
                label: 'ID',
                field: 'id',
                align: 'left',
            }, {
                name: 'is_active',
                label: 'Published',
                field: 'is_active',
                note: 'Whether the price is active within app.'
            }, {
                name: 'name',
                label: 'Access Token',
                field: 'name',
                align: 'left',
                sortable: false,
                note: 'Tokens grant users access to specific features within the app.'
            }, {
                name: 'price',
                label: 'Price',
                field: 'price',
                align: 'left',
                sortable: false
            }, {
                name: 'billing_type',
                label: 'Billing type',
                field: 'billing_type',
                align: 'left',
                note: 'One-time purchases are charged a single time. Subscriptions allow for periodic renewal of access, with the option to cancel at any time.'
            }, {
                name: 'billing_period',
                label: 'Access period',
                field: 'billing_period',
                align: 'left',
                note: 'Duration of access to the provided token.'
            }, {
                name: 'trial_mode',
                label: 'Trial mode',
                field: 'trial_mode',
                align: 'left',
            }, {
                name: 'status',
                label: 'Paddle status',
                field: 'status',
                note: 'Whether the price is active within Paddle.'
            }, {
                name: 'actions',
                label: 'Actions',
                field: 'actions',
            },
        ];

        return {
            columnsProducts
        };
    },
};
</script>