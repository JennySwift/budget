<template>
    <tr>
        <td>
            <div>
            <span
                v-bind:class="{'tag-with-fixed-budget': budget.type === 'fixed', 'tag-with-flex-budget': budget.type === 'flex'}"
                class="label label-default"
            >
                @{{ budget.name }}
            </span>
            </div>
        </td>

        <td>
            <div class="editable">

                <!--Input-->
                <div
                    v-if="editingAllocatedFixed"
                    class="input-btn-container xs"
                >
                    <input
                        v-model="allocatedFixed" type="text"
                        v-on:keyup.13="updateAllocation('fixed', allocatedFixed)"
                        class="form-control"
                    >

                    <span class="input-group-btn">
                    <button
                        v-on:click="updateAllocation('fixed', allocatedFixed)"
                        class="btn btn-default"
                    >
                        Done
                    </button>
                </span>
                </div>

                <!--Edit button-->
                <button
                    v-if="!editingAllocatedFixed"
                    v-on:click="editingAllocatedFixed = true"
                    class="edit btn-default btn-xs">edit
                </button>

                <!--Amount-->
                <span
                    v-if="!editingAllocatedFixed"
                >
                @{{ budget.pivot.allocated_fixed }}
            </span>

            </div>
        </td>

        <td>
            <div class="editable">

                <!--Input-->
                <div
                    v-if="editingAllocatedPercent"
                    class="input-btn-container xs"
                >
                    <input

                        v-model="allocatedPercent" type="text"
                        v-on:keyup.13="updateAllocation('percent', allocatedPercent)"
                        class="form-control"
                    >

                    <span class="input-group-btn">
                    <button
                        v-on:click="updateAllocation('percent', allocatedPercent)"
                        class="btn btn-default"
                    >
                        Done
                    </button>
                </span>
                </div>

                <!--Edit button-->
                <button
                    v-if="!editingAllocatedPercent"
                    v-on:click="editingAllocatedPercent = true"
                    class="edit btn btn-default btn-xs">edit
                </button>

                <!--Amount-->
                <span
                    v-if="!editingAllocatedPercent"
                >
                @{{ budget.pivot.allocated_percent }}
            </span>

            </div>
        </td>

        <td>
            <div>
                <span>@{{ budget.pivot.calculated_allocation }}</span>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        data: function () {
            return {
                editingAllocatedFixed: false,
                editingAllocatedPercent: false,
                allocatedFixed: '',
                allocatedPercent: ''
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateAllocation: function (type, value) {
                $.event.trigger('show-loading');

                var data = {
                    type: type,
                    value: value
                };

                this.$http.put('/api/budgets/' + this.budget.id + '/transactions/' + this.transaction.id, data, function (response) {
                    this.$dispatch('budget-allocation-updated', response);
                    $.event.trigger('provide-feedback', ['Allocation updated', 'success']);
                    $.event.trigger('hide-loading');
                })
                    .error(function (response) {
                        HelpersRepository.handleResponseError(response);
                    });
            }


        },
        props: [
            'budget',
            'transaction'
        ],
        mounted: function () {

        }
    }
</script>

