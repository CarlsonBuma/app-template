<template>
    
    <q-card class="q-my-xs q-mx-sm-xs" bordered :flat="shadow ? false : true">

        <!-- Head -->
        <q-card-section v-if="title" header>
            <div class="row">
                <div class="col-grow flex items-center">
                    <q-icon
                        class="q-pr-xs"
                        v-if="tooltip"
                        name="info"
                        size="12px"
                        :color="tooltipIconColor ? tooltipIconColor : 'red'"
                    >
                        <q-tooltip>
                            <p class="text-caption w-tooltip">{{ tooltip }}</p>
                        </q-tooltip>
                    </q-icon>
                    <div class="flex items-center">
                        <span class="q-item__label--header p-0 m-0">{{ title }}</span>
                    </div>
                </div>
                <div class="col-12 col-sm-auto flex justify-end">
                    <slot name="actions"/>
                </div>
                <div class="col-12 q-mt-sm" v-if="description">
                    <span class="text-caption">{{ description }}</span>
                </div>
            </div>
        </q-card-section>
        
        <!-- Content-->
        <q-separator v-if="title" />
        <slot />

        <!-- Note -->
        <q-separator v-if="note" />
        <q-card-section v-if="note">
            <span class="text-caption"><em><b>Note:</b> {{ note }}</em></span>
        </q-card-section>
    </q-card>
    
</template>

<script>
export default {
    name: 'CardSimple',
    props: {
        title: String,
        description: String,
        tooltip: String,
        tooltipIconColor: String,
        note: String,
        flat: Boolean,
        bordered: Boolean,
        custom: Boolean,
        width: String,
        shadow: Boolean
    },
};
</script>
