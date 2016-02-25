<script type="text/v-template" id="filter-types-template">

    <div filter-dropdowns-directive
         types="types"
         class="section">

        <h4 class="center">types</h4>

        <div class="types transition content">

            <div>
                <input type="checkbox">
                <label for="">all</label>
            </div>

            <div>
                <input type="checkbox">
                <label for="">none</label>
            </div>

            <div v-show="filterTab === 'show'" v-repeat="type in types">
                <input
                        checklist-model="filter.types.in"
                        checklist-value="type"
                        checklist-change="runFilter()"
                        v-disabled="filter.types.out.indexOf(type) !== -1"
                        type="checkbox">
                <label
                        v-bind:class="{'disabled': filter.types.out.indexOf(type) !== -1}"
                        for="">
                    [[type]]
                </label>
            </div>

            <div v-show="filterTab === 'hide'" v-repeat="type in types">
                <input
                        checklist-model="filter.types.out"
                        checklist-value="type"
                        checklist-change="runFilter()"
                        v-disabled="filter.types.in.indexOf(type) !== -1"
                        type="checkbox">
                <label
                        v-bind:class="{'disabled': filter.types.in.indexOf(type) !== -1}"
                        for="">
                    [[type]]
                </label>
            </div>

        </div>

    </div>

</script>