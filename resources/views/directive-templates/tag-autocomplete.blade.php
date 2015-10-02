
<script type="text/ng-template" id="tag-autocomplete-template">

    <div class="tag-wrapper">
        <div class="tag-input-wrapper">

            <input
                ng-model="typing"
                ng-focus="showDropdown()"
                ng-blur="hideDropdown()"
                ng-keyup="filterTags($event.keyCode)"
                placeholder="tags"
                type='text'
                id="[[id]]-input">

            <div ng-show="dropdown" class="tag-dropdown">
                <div
                    ng-repeat="budget in results"
                    ng-mousedown="chooseTag($index)"
                    ng-mouseover="hoverItem($index)"
                    ng-class="{'selected': $index == currentIndex}"
                    class="dropdown-item">
                    <div ng-bind-html="budget.html"></div>
                    <div>
                        <span class="label label-default [[budget.type]]-label">[[budget.type]]</span>
                    </div>
                </div>
            </div>

        </div>


        <div ng-cloak ng-show="chosenTags.length > 0" class="tag-display">

            <li
                ng-repeat="tag in chosenTags"
                ng-click="removeTag(tag)"
                ng-class="{'tag-with-fixed-budget': tag.type === 'fixed', 'tag-with-flex-budget': tag.type === 'flex', 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"
                class="label label-default removable-tag">
                [[tag.name]]
                <i class="fa fa-times"></i>
            </li>

        </div>
    </div>

</script>