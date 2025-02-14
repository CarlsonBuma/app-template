<template>
    
    <q-table
        flat
        title="My access"
        row-key="id"
        :rows="prices"
        :columns="columnsProducts"
        :pagination="{
            rowsPerPage: 5
        }"
    >
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
                <q-td key="name" :props="props">
                    <span>{{ props.row.name }}</span><br>
                    <span class="text-caption">'{{ props.row.access_token }}'</span>
                </q-td>
                <q-td key="price" :props="props">
                    <span> {{ props.row.currency_code + ' ' + props.row.price }}</span><br>
                    <span class="text-caption">Tax: {{ props.row.tax_mode }}</span>
                </q-td>
                <q-td key="billing_type" :props="props">
                    {{ props.row.type }}
                </q-td>
                <q-td key="billing_period" :props="props">
                    {{ 
                        props.row.billing_frequency 
                            ? props.row.billing_frequency + ' ' + props.row.billing_interval 
                            : props.row.duration_months 
                                ? props.row.duration_months + ' months' 
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
                    
                    <!-- Cancel subscriptions -->
                    <q-btn 
                        v-if="props.row.has_active_subscription"
                        label="Deactivate"
                        icon="generating_tokens"
                        size="sm"
                        color="purple"
                        outline
                        @click="$emit('cancel', props.row)"
                    >
                        <q-tooltip v-if="props.row.has_access">
                            <b>Limits:</b><br>
                            Expires: {{ $tp.date(props.row.has_access.expiration_date) }}<br>
                            Credits: {{ props.row.has_access.quantity }}
                        </q-tooltip>
                    </q-btn>

                    <!-- Get Access -->
                    <q-btn 
                        v-else
                        icon="generating_tokens"
                        size="sm"
                        outline
                        :label="props.row.has_access && !props.row.is_subscription 
                            ? 'Active'
                            : 'Get access'"
                        :color="props.row.has_access
                            ? 'green' 
                            : 'primary'"
                        @click="$emit('action', props.row)"
                    >
                        <q-tooltip v-if="props.row.has_access">
                            <b>Limits:</b><br>
                            Expires: {{ $tp.date(props.row.has_access.expiration_date) }}<br>
                            Credits: {{ props.row.has_access.quantity }}
                        </q-tooltip>
                    </q-btn>
                </q-td>
            </q-tr>
        </template>
    </q-table>

</template>

<script>
export default {
    name: 'UserPricesTable',

    props: {
        prices: Array
    },

    emits: [
        'action',
        'cancel'
    ],

    setup() {
        const columnsProducts = [
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
                sortable: false,
                note: 'Tokens allow you gain access to provided app features.'
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
                note: 'One-time purchases are charged a single time. Subscriptions allow periodic renewal of access, with the option to cancel at any time.'
            }, {
                name: 'billing_period',
                label: 'Access period',
                field: 'billing_period',
                align: 'left',
                note: 'Duration of access to the provided featurs.'
            }, {
                name: 'trial_mode',
                label: 'Trial mode',
                field: 'trial_mode',
                align: 'left',
            }, {
                name: 'status',
                label: 'Status',
                field: 'status',
            },
        ];

        return {
            columnsProducts
        };
    },
};
</script>