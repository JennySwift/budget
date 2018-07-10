<template>
    <tr
        v-if="shared.me.preferences.show.totals[field]"
        class="tooltipster"
        :title="title"
    >
        <td><slot></slot>:</td>
        <td><span class="badge" :class="{'badge-success': !color, 'badge-danger': color}">{{ shared.sideBarTotals[field] | numberFilter(2) }}</span></td>
        <td><span v-if="shared.totalChanges[field]">{{ shared.totalChanges[field] | numberFilter(2) }}</span></td>
    </tr>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state
            }
        },
        props: [
            'field',
            'title',
            'label',
            'color'
        ]
    }
</script>

<style lang="scss" type="text/scss">
    //@import '../../sass/shared/index';
</style>