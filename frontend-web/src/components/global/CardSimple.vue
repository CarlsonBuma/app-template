<template>
    
    <q-card class="q-ma-xs" bordered :flat="!shadow">

        <!-- Head -->
        <q-card-section v-if="title" header>
            <div class="row">
                <div class="col-auto flex items-center">
                    
                    <!-- Navigate Back -->
                    <q-btn 
                        v-if="goBack"
                        size="sm"
                        class="q-py-none q-mr-xs"
                        icon="arrow_left" 
                        dense flat 
                        @click="$router.go(-1)"
                    />
                    
                    <!-- Title -->
                    <slot name="title">
                        <div class="flex items-center">
                            <span class="q-item__label--header p-0 m-0">{{ title }}</span>
                        </div>
                    </slot>

                    <!-- Tooltip -->
                    <q-icon
                        class="q-px-xs"
                        v-if="tooltip"
                        name="info"
                        size="12px"
                        :color="tooltipColor ? tooltipColor : 'primary'"
                    >
                        <q-tooltip>
                            <slot name="tooltip">
                                <span class="text-caption w-tooltip">{{ tooltip }}</span>
                            </slot>
                        </q-tooltip>
                    </q-icon>
                </div>
                <div class="col-grow flex justify-end">
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
            <span class="text-caption text-grey"><b>Note:</b> {{ note }}</span>
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
        tooltipColor: String,
        note: String,
        flat: Boolean,
        bordered: Boolean,
        custom: Boolean,
        width: String,
        shadow: Boolean,
        goBack: Boolean
    },
};
</script>
