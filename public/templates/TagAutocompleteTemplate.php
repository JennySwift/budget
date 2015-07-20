
<div class="tag-wrapper">
    <div class="tag-input-wrapper">

        <input
            ng-model="typing"
            ng-focus="showDropdown()"
            ng-blur="hideAndClear()"
            ng-keyup="filterTags($event.keyCode)"
            placeholder="tags"
            type='text'>

        <div ng-show="dropdown" class="tag-dropdown">
            <div
                ng-repeat="tag in results"
                ng-mousedown="chooseTag($index)"
                ng-mouseover="hoverItem($index)"
                ng-class="{'selected': $index == currentIndex}"
                class="dropdown-item"
                data-id="[[tag.id]]">
                <div ng-bind-html="tag.html"></div>
            </div>
        </div>

    </div>


    <div ng-cloak ng-show="chosenTags.length > 0" class="tag-display">

        <li
            ng-repeat="tag in chosenTags"
            ng-click="removeTag(tag)"
            ng-class="{'tag-with-budget': tag.fixed_budget !== null || tag.flex_budget !== null, 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"
            class="label label-default removable-tag"
            data-id="[[tag.id]]"
            data-allocated-percent="[[tag.allocated_percent]]"
            data-allocated-fixed="[[tag.allocated_fixed]]"
            data-amount="[[tag.amount]]">
            [[tag.name]]
            <i class="fa fa-times"></i>
        </li>

    </div>
</div>