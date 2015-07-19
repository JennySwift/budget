
<div class="tag-wrapper">
    <div class="tag-input-wrapper">

        <input
            ng-model="typing.new_transaction.tag"
            ng-focus="new_transaction.dropdown = true"
            ng-blur="new_transaction.dropdown = false"
            ng-keyup="filterTags($event.keyCode, typing.new_transaction.tag, new_transaction.tags, 'new_transaction')"
            placeholder="tags"
            type='text'>

        <div ng-if="new_transaction.dropdown" class="tag-dropdown">
            <li
                ng-repeat="tag in autocomplete.tags"
                ng-class="{'selected': tag.selected}"
                data-id="[[tag.id]]">[[tag.name]]
            </li>
        </div>

    </div>


    <div ng-cloak ng-show="new_transaction.tags.length > 0" class="tag-display">

        <li
            ng-repeat="tag in new_transaction.tags"
            ng-click="removeTag(tag, new_transaction.tags, 'new_transaction')"
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