
<script type="text/v-template" id="tag-autocomplete-template">

    <div class="tag-wrapper">
        <div class="tag-input-wrapper">

            <input
                v-model="typing"
                v-focus="showDropdown()"
                v-blur="hideDropdown()"
                v-on:keyup="filterTags($event.keyCode)"
                placeholder="tags"
                type='text'
                id="[[id]]-input">

            <div v-show="dropdown" class="tag-dropdown">
                <div
                    v-repeat="budget in results"
                    v-mousedown="chooseTag($index)"
                    v-mouseover="hoverItem($index)"
                    v-class="{'selected': $index == currentIndex}"
                    class="dropdown-item">
                    <div v-bind-html="budget.html"></div>
                    <div>
                        <span class="label label-default [[budget.type]]-label">[[budget.type]]</span>
                    </div>
                </div>
            </div>

        </div>


        <div v-cloak v-show="chosenTags.length > 0" class="tag-display">

            <li
                v-repeat="tag in chosenTags"
                v-on:click="removeTag(tag)"
                v-class="{'tag-with-fixed-budget': tag.type === 'fixed', 'tag-with-flex-budget': tag.type === 'flex', 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"
                class="label label-default removable-tag">
                [[tag.name]]
                <i class="fa fa-times"></i>
            </li>

        </div>
    </div>

</script>
