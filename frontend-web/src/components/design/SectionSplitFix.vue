<template>

    <div class="row w-100">
        <div class="col-auto">
            <slot name="left" />
        </div>
        <div class="col-grow flex justify-center q-py-lg q-py-md-none">
            <slot name="right" />
        </div>
    </div>
    
</template>

<script>
export default {
    name: 'SectionSplitFix',
};
</script>
