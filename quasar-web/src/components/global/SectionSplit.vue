<template>

    <div class="row items-center w-100">
        <div class="col-12 col-md-6 flex justify-center">
            <slot name="left" />
        </div>
        <div class="col-12 col-md-6 flex justify-center">
            <slot name="right" />
        </div>
    </div>
    
</template>

<script>
export default {
    name: 'SectionSplit',
    props: {
        // 
    },
};
</script>
