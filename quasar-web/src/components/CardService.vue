<style lang="sass" scoped>
.component-card-service-price
    top: 0
    right: 21px
    transform: translateY(-50%)
</style>

<template>

    <q-card class="q-ma-sm-xs q-my-xs" bordered flat>
        <div class="flex justify-center">
            <q-icon :name="iconName ? iconName : 'badge'" color="primary" :size="iconSize ? iconSize : '220px'" />
        </div>
        <q-card-section>
            <div class="absolute component-card-service-price">
                <div>
                    <q-btn
                        fab
                        color="primary"
                        icon="add_card"
                        class=""
                    />
                    <div class="text-grey text-caption q-mt-xs text-center items-center">
                        <span class="w-100">{{ price }}</span><br>
                        <span class="w-100">{{ priceTag }}</span>
                    </div>
                </div>
            </div>
            <div class="row no-wrap items-center">
                <div class="col text-h6 ellipsis">
                    {{ title }}
                </div>
            </div>
            <p class="text-caption q-mt-sm">
                {{ description }}
            </p>
        </q-card-section>

        <!-- Features -->
        <q-card-section class="q-pt-none">
            <div class="text-subtitle2">Features</div>
            <q-list dense padding class="text-caption">
                <q-item v-for="(item, index) in items" :key="index">
                    <q-item-section avatar>
                        <q-icon :name="item.icon" color="green"/>
                    </q-item-section>
                    <q-item-section>
                        <b>{{ item.title }}</b> 
                        {{ item.description }}
                    </q-item-section>
                </q-item>
            </q-list>
        </q-card-section>

        
    </q-card>
    
</template>

<script>
export default {
    name: 'CardService',
    props: {
        iconName: String,
        iconSize: String,
        price: String,
        priceTag: String,
        title: String,
        description: String,
        items: Array
    },
};
</script>
