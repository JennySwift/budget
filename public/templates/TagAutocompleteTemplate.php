
<div class="tag-wrapper">
    <div class="tag-input-wrapper">

        <input
            ng-model="typing"
            ng-focus="dropdown = true"
            ng-blur="dropdown = false"
            ng-keyup="filterTags($event.keyCode, typing)"
            placeholder="tags"
            type='text'>

        <div ng-if="dropdown" class="tag-dropdown">
            <li
                ng-repeat="tag in results"
                ng-mousedown="chooseItem($index)"
                ng-mouseover="hoverItem($index)"
                ng-class="{'selected': $index == currentIndex}"
                data-id="[[tag.id]]">[[tag.name]]
            </li>
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