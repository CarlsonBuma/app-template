<template>

    <q-card class="q-ma-sm-xs q-my-xs" bordered >
        <!-- Actions -->
        <q-card-section v-if="allowActions" horizontal>
            <slot name="head" />
            <q-card-actions class="justify-around" vertical>
                <slot name="actions" />
            </q-card-actions>
        </q-card-section>
        <q-separator v-if="allowActions" />

        <!-- Card Body -->
        <q-card-section>

            <!-- Title -->
            <div v-if="title" class="flex items-center _overflow-hidden">
                
                <!-- Navigation: Go back -->
                <q-btn 
                    @click="goBack ? $router.go(-1) : ''"
                    flat 
                    round
                    dense 
                    text-color="primary" 
                    :icon="goBack ? 'arrow_left' : 'arrow_right'"
                />

                <!-- Tooltip -->
                <q-icon
                    v-if="iconClass" 
                    :name="iconClass"
                    :color="iconColor"
                    class="q-ma-sm"
                >
                    <q-tooltip self="center left">
                        <slot name="tooltip" />
                    </q-tooltip>
                </q-icon>

                <!-- Title -->
            <p class="q-ma-none text-subtitle1">{{ title }}</p>
            </div>

            <!-- Subtitle -->
            <p v-if="subtitle" class="q-ml-sm q-mr-sm text-caption">{{ subtitle }}</p>
        </q-card-section>

        <!-- Header Icon -->
        <q-separator v-if="title && iconHeader" />
        <q-card-section>
            <div v-if="iconHeader" class="flex justify-center">
                <q-icon
                    :name="iconHeader"
                    color="primary" 
                    size="150px" 
                />
            </div>
        </q-card-section>

        <q-separator />
        <q-card-section>
            <slot />
        </q-card-section>
        
        <!-- Note -->
        <q-separator v-if="note" />
        <q-card-section v-if="note">
            <span class="text-caption"><em>{{ note }}</em></span>
        </q-card-section>
    </q-card>

</template>

<script>
export default {
    name: 'PageWrapper',
    props: {
        goBack: Boolean,
        iconHeader: String,
        title: String,
        note: String,
        subtitle: String,
        iconClass: String,
        iconColor: String,
        cardWidth: String,
        allowActions: Boolean,
    },
};
</script>
