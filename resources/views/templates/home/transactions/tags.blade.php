<tr
    ng-show="show.tags && transaction.tags.length > 0"
    class="results-tag-location-container tag-location-container">

    <td colspan="9">
        <li
            ng-click="removeResultTag(this)"
            ng-repeat="tag in transaction.tags"
            ng-class="{'tag-with-fixed-budget': tag.fixed_budget !== null, 'tag-with-flex-budget': tag.flex_budget !== null, 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"
            class="label label-default"
            data-id="[[tag.id]]"
            data-allocated-percent="[[tag.allocated_percent]]"
            data-allocated-fixed="[[tag.allocated_fixed]]"
            data-allocated_fixed="[[tag.allocated_fixed]]">
            [[tag.name]]
        </li>
    </td>

</tr>