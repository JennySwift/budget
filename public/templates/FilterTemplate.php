
<div ng-cloak ng-show="showFilter" id="filter" class="bg-grey margin-bottom">

    <!-- ===============accounts=============== -->

    <h4 class="center">accounts</h4>

    <div class="accounts">

        <div>
            <input type="checkbox">
            <label for="">all</label>
        </div>

        <div>
            <input type="checkbox">
            <label for="">none</label>
        </div>

        <div ng-repeat="account in accounts">
            <input checklist-model="filter.accounts" checklist-value="account.id" type="checkbox">
            <label for="">[[account.name]]</label>
        </div>

    </div>

    <!-- ===============types=============== -->

    <h4 class="center">types</h4>

    <div class="types">

        <div>
            <input type="checkbox">
            <label for="">all</label>
        </div>

        <div>
            <input type="checkbox">
            <label for="">none</label>
        </div>

        <div ng-repeat="type in types">
            <input checklist-model="filter.types" checklist-value="type" type="checkbox">
            <label for="">[[type]]</label>
        </div>

    </div>

    <!-- ===============description=============== -->

    <h4 class="center">description</h4>

    <div class="input-group input-group-sm">
        <input ng-model="filter.description" type="text" placeholder="description">
        <span class="input-group-btn">
            <button ng-click="clearFilterField('description')" class="clear-search-button">clear</button>
        </span>
    </div>

    <!-- ===============merchant=============== -->

    <h4 class="center">merchant</h4>

    <div class="input-group input-group-sm">
        <input ng-model="filter.merchant" type="text" placeholder="merchant">
        <span class="input-group-btn">
            <button ng-click="clearFilterField('merchant')" class="clear-search-button">clear</button>
        </span>
    </div>

    <!-- ================tag wrapper================ -->

    <h4 class="center">tags</h4>

    <div class="tag-wrapper">
        <div class="tag-input-wrapper">

            <!-- tag input group -->
            <div class="input-group input-group-sm">
                <input ng-model="typing.filter.tag" ng-focus="filter.dropdown = true" ng-blur="filter.dropdown = false" ng-keyup="filterTags($event.keyCode, typing.filter.tag, filter.tags, 'filter')" placeholder="tags" type='text'>
                <span class="input-group-btn">
                    <button ng-click="clearFilterField('tags')" class="clear-search-button">clear</button>
                </span>
            </div>


            <div ng-if="filter.dropdown" class="tag-dropdown">
                <li ng-repeat="tag in autocomplete.tags" ng-class="{'selected': tag.selected}" data-id="[[tag.id]]">[[tag.name]]</li>
            </div>

        </div>


        <div ng-show="filter.tags.length > 0" class="tag-display">
            <li ng-repeat="tag in filter.tags" ng-click="removeTag(tag, filter.tags, 'filter')" ng-class="{'tag-with-budget': tag.has_budget === 'yes', 'tag-without-budget': tag.has_budget === 'no'}" class="label label-default removable-tag" data-id="[[tag.id]]" data-allocated-percent="[[tag.allocated_percent]]" data-allocated-fixed="[[tag.allocated_fixed]]" data-amount="[[tag.amount]]">
                [[tag.name]]
                <i class="fa fa-times"></i>
            </li>
        </div>
    </div>

    <!-- ================end tag wrapper================ -->

    <!-- ===============date=============== -->

    <h4 class="center">date</h4>

    <div class="input-group input-group-sm">
        <input ng-model="filter.single_date" ng-keyup="checkKeycode($event.keyCode, multiSearch, true)" type="text" placeholder="single date">
        <span class="input-group-btn">
            <button ng-click="clearFilterField('single_date')" class="clear-search-button">clear</button>
        </span>
    </div>

    <div class="input-group input-group-sm">
        <input ng-model="filter.from_date" ng-keyup="checkKeycode($event.keyCode, multiSearch, true)" type="text" placeholder="from a date">
        <span class="input-group-btn">
            <button ng-click="clearFilterField('from_date')" class="clear-search-button" type="button">clear</button>
        </span>
    </div>

    <div class="input-group input-group-sm">
        <input ng-model="filter.to_date" ng-keyup="checkKeycode($event.keyCode, multiSearch, true)" type="text" placeholder="to a date">
        <span class="input-group-btn">
            <button ng-click="clearFilterField('to_date')" class="clear-search-button" type="button">clear</button>
        </span>
    </div>

    <!-- ===============amount=============== -->

    <h4 class="center">amount</h4>

    <div class="input-group input-group-sm">
        <input ng-model="filter.total" type="text" placeholder="amount">
        <span class="input-group-btn">
            <button ng-click="clearFilterField('total')" class="clear-search-button" type="button">clear</button>
        </span>
    </div>

    <!-- ===============reconciled=============== -->

    <h4 class="center">status</h4>

    <div class="status">

        <div>
            <input ng-model="filter.reconciled" type="radio" name="status" value="any">
            <label for="">Any</label>
        </div>

        <div>
            <input ng-model="filter.reconciled" type="radio" name="status" value="true">
            <label for="">Reconciled</label>
        </div>

        <div>
            <input ng-model="filter.reconciled" type="radio" name="status" value="false">
            <label for="">Unreconciled</label>
        </div>

    </div>

    <!-- ===============single/multiple budgets=============== -->

    <h4 class="center">Budget</h4>

    <div class="budget">

        <div>
            <input ng-model="filter.budget" type="radio" name="budget" value="all">
            <label for="">All</label>
        </div>

        <div>
            <input ng-model="filter.budget" type="radio" name="budget" value="none">
            <label for="">None</label>
        </div>

        <div>
            <input ng-model="filter.budget" type="radio" name="budget" value="single">
            <label for="">Single</label>
        </div>

        <div>
            <input ng-model="filter.budget" type="radio" name="status" value="multiple">
            <label for="">Multiple</label>
        </div>

    </div>

</div>