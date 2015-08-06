
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">date</h4>

    <div class="content">

        <div ng-show="filterTab === 'show'" class="group">

            <input
                ng-model="filter.single_date.in.user"
                ng-keyup="filterDate($event.keyCode)"
                type="text"
                placeholder="single date">

            <span class="input-group-btn">
                <button
                    ng-click="clearDateField('single_date', 'in')"
                    class="clear-search-button">
                    clear
                </button>
            </span>

        </div>

        <div ng-show="filterTab === 'hide'" class="group">

            <input
                    ng-model="filter.single_date.out.user"
                    ng-keyup="filterDate($event.keyCode)"
                    type="text"
                    placeholder="single date">

            <span class="input-group-btn">
                <button
                        ng-click="clearDateField('single_date', 'out')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

        </div>

        <div ng-show="filterTab === 'show'" class="group">

            <input
                ng-model="filter.from_date.in.user"
                ng-keyup="filterDate($event.keyCode)"
                type="text"
                placeholder="from a date">

            <span class="input-group-btn">
                <button
                    ng-click="clearDateField('from_date', 'in')"
                    class="clear-search-button"
                    type="button">
                    clear
                </button>
            </span>

        </div>

        {{--<div ng-show="filterTab === 'hide'" class="group">--}}

            {{--<input--}}
                    {{--ng-model="filter.from_date.out.user"--}}
                    {{--ng-keyup="filterDate($event.keyCode)"--}}
                    {{--type="text"--}}
                    {{--placeholder="from a date">--}}

            {{--<span class="input-group-btn">--}}
                {{--<button--}}
                        {{--ng-click="clearDateField('from_date', 'out')"--}}
                        {{--class="clear-search-button"--}}
                        {{--type="button">--}}
                    {{--clear--}}
                {{--</button>--}}
            {{--</span>--}}

        {{--</div>--}}

        <div ng-show="filterTab === 'show'" class="group">

            <input
                ng-model="filter.to_date.in.user"
                ng-keyup="filterDate($event.keyCode)"
                type="text"
                placeholder="to a date">

            <span class="input-group-btn">
                <button
                    ng-click="clearDateField('to_date', 'in')"
                    class="clear-search-button"
                    type="button">
                    clear
                </button>
            </span>

        </div>

        {{--<div ng-show="filterTab === 'hide'" class="group">--}}

            {{--<input--}}
                    {{--ng-model="filter.to_date.out.user"--}}
                    {{--ng-keyup="filterDate($event.keyCode)"--}}
                    {{--type="text"--}}
                    {{--placeholder="to a date">--}}

            {{--<span class="input-group-btn">--}}
                {{--<button--}}
                        {{--ng-click="clearDateField('to_date', 'out')"--}}
                        {{--class="clear-search-button"--}}
                        {{--type="button">--}}
                    {{--clear--}}
                {{--</button>--}}
            {{--</span>--}}

        {{--</div>--}}

    </div>

</div>