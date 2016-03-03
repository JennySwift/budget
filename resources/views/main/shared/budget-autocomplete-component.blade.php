<script id="budget-autocomplete-template" type="x-template">

    <div class="budget-wrapper">
        <div class="budget-input-wrapper">

            <input
                    v-model="typing"
                    v-on:focus="showDropdown = true"
                    v-on:blur="showDropdown = false"
                    v-on:keyup="filterBudgets($event.keyCode)"
                    placeholder="budgets"
                    type='text'
                    id="@{{ id }}-input">

            <div v-show="showDropdown" class="budget-dropdown">
                <div
                        v-for="budget in results"
                        v-on:mousedown="chooseBudget($index)"
                        v-on:mouseover="hoverItem($index)"
                        v-bind:class="{'selected': $index == currentIndex}"
                        class="dropdown-item">
                    <div v-html="budget.html"></div>
                    <div>
                        <span class="label label-default @{{ budget.type }}-label">@{{ budget.type }}</span>
                    </div>
                </div>
            </div>

        </div>

        <div v-cloak v-if="chosenBudgets" v-show="chosenBudgets.length > 0" class="budget-display">

            <li
                    v-for="budget in chosenBudgets"
                    v-on:click="removeBudget(budget)"
                    v-bind:class="{
                        'tag-with-fixed-budget': budget.type === 'fixed',
                        'tag-with-flex-budget': budget.type === 'flex',
                        'tag-without-budget': budget.type === 'unassigned'
                    }"
                    class="label label-default removable-budget"
            >
                <span>@{{ budget.name }}</span>
                <span class="type">@{{ budget.type }}</span>
                <i class="fa fa-times"></i>
            </li>

        </div>
    </div>

</script>