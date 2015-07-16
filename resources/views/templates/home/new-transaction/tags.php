<div>
    <div class="help-row">
        <label>Add tags to your transaction (optional)</label>

        <div dropdown class="dropdown-directive">
            <button ng-click="showDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                Help
                <span class="caret"></span>
            </button>

            <div class="dropdown-content animated">
                <div class="help">
                    <div>To add tags to your transaction, start typing the name of your tag in the field, then use the up or down arrow keys to select a tag, then press enter.</div>
                    <div>Repeat the process to enter more than one tag.</div>
                    <div>The tag must first be created on the <a href="/tags">tags page</a> in order for it to show up as an option here.</div>
                    <div>If you press enter with no tag selected, the transaction will be entered.</div>
                </div>
            </div>
        </div>
    </div>


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
</div>
