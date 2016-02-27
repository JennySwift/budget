<script id="budget-autocomplete-template" type="x-template">

    <div class="tag-wrapper">
        <div class="tag-input-wrapper">

            <input
                    v-model="typing"
                    v-on:focus="showDropdown()"
                    v-on:blur="hideDropdown()"
                    v-on:keyup="filterTags($event.keyCode)"
                    placeholder="tags"
                    type='text'
                    id="@{{ id }}-input">

            <div v-show="dropdown" class="tag-dropdown">
                <div
                        v-for="budget in results"
                        v-on:mousedown="chooseTag($index)"
                        v-on:mouseover="hoverItem($index)"
                        v-bind:class="{'selected': $index == currentIndex}"
                        class="dropdown-item">
                    <div v-bind-html="budget.html"></div>
                    <div>
                        <span class="label label-default @{{ budget.type }}-label">@{{ budget.type }}</span>
                    </div>
                </div>
            </div>

        </div>


        <div v-cloak v-show="chosenTags.length > 0" class="tag-display">

            <li
                    v-for="tag in chosenTags"
                    v-on:click="removeTag(tag)"
                    v-bind:class="{'tag-with-fixed-budget': tag.type === 'fixed', 'tag-with-flex-budget': tag.type === 'flex', 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"
                    class="label label-default removable-tag">
                @{{ tag.name }}
                <i class="fa fa-times"></i>
            </li>

        </div>
    </div>

</script>