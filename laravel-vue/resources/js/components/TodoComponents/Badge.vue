<template>
    <div
            :class="`${color.color} `"  title="Priority">
<!--          class="px-3 h-6 rounded-full text-xs font-semibold flex items-center"  :class="`${color}-100 text-${color}-700`"-->

        <span class="w-2 h-2 rounded-full mr-1" :class="`${color.color}`"></span>
        <span>
      <slot>{{color.name}}</slot>
    </span>
    </div>
</template>
<script>
export default {
    props: {
        color: {
            type: Object,
            default: () => ({})
        }
    }
};
</script>
