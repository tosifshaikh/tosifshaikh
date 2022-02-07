<template>
    <div
            class="px-3 h-6 rounded-full text-xs font-semibold flex items-center" :class="`bg-${data.color}-100 text-${data.color}-700`" title="Type"

    >
        <span class="w-2 h-2 rounded-full mr-1" :class="`bg-${data.color}-400`" ></span>
        <span>
      <slot>{{ data.name }}</slot>
    </span>
    </div>
</template>

<script>
export default {
    name: "Label",
    props: {
        data: {
            type: Object,
            default: () => ({})
        }
    }
}
</script>

<style scoped>

</style>