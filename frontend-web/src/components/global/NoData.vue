<template>

    <div class="row w-100 flex justify-center">
        <div class="w-card-sm q-py-md text-center">
            <div class="col-12 q-mb-sm">
                <q-icon name="festival" size="96px" :color="color ? color : 'primary'" />
            </div>
            <div class="col-12" v-if="!noText">
                <span :class="'text-' + color" class="text-overline text-grey">{{ text }}</span><br>
                <span v-if="cta" class="text-caption text-grey"><i>{{ cta }}</i></span>
            </div>
        </div>
        
    </div>
    
</template>

<script>
export default {
    name: 'NoData',
    props: {
        text: String,
        color: String,
        cta: String,
        noText: Boolean,
    },
};
</script>
